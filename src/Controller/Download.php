<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\Catalog\Controller;

use Gm;
use Gm\Backend\Marketplace\Catalog\Widget\DownloadWindow;
use Gm\Panel\Controller\BaseController;
use Gm\FilePackager\FilePackager;
use Gm\Mvc\Module\BaseModule;
use Gm\Filesystem\Filesystem;
use Gm\Panel\Http\Response;
use Gm\Helper\Html;

/**
 * Контроллер компонента кателога Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Controller
 * @since 1.0
 */
class Download extends BaseController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\Catalog\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    protected string $defaultAction = 'view';

    /**
     * Создаёт виджет для формирования интерфейса окна загрузки компонента.
     * 
     * @param array $params Параметры компонента Маркетплейс.
     * 
     * @return DownloadWindow
     */
    public function createDownloadWidget(array $params): DownloadWindow
    {
        /** @var DownloadWindow Окно загрузки компонента (Ext.window.Window Sencha ExtJS) */
        $window = new DownloadWindow();
        $window->title = $this->t('{download.title}');

        // идентификатор компонента Маркетплейс
        $window->form->componentId = $params['componentId'];
        $window->form->id = 'progress'; // => g-marketplace-catalog-progress
        $window->form->items = [
            [
                'xtype' => 'displayfield',
                'cls'   => 'g-form__display__header g-form__display__header_icon',
                'width' => '100%',
                'value' => Html::tags([
                    Html::img($params['icon'], ['class' => 'g-icon g-icon_size_32'], false),
                    Html::tag(
                        'div',
                        ($params['type'] ? $params['type'] : SYMBOL_NONAME) . ' "' . $params['name'] . '"',
                        ['class' => 'g-form__display__text']
                    ),
                    Html::tag('div', $this->t('after downloading, installation will be performed'), ['class' => 'g-form__display__subtext'])
                ])
            ],
            [
                'xtype'    => 'container',
                'layout'   => 'form',
                'padding'  => '0 0 0 20px',
                'defaults' => [
                    'xtype'      => 'displayfield',
                    'ui'         => 'parameter',
                    'labelAlign' => 'right'
                ],
                'items' => [
                    [
                        'fieldLabel' => $this->t('version'),
                        'value'      => $params['version'],
                        'labelWidth' => 250
                    ],
                    [
                        'fieldLabel' => $this->t('author'),
                        'value'      => $params['developer'] ? $params['developer']['name'] : $this->t('not specified'),
                    ],
                    [
                        'fieldLabel' => $this->t('loaded'),
                        'value'      => Gm::$app->formatter->toSizeDataUnit($params['packageSize'])
                    ]
                ]
            ],
            [
                'id'     => 'g-progress-download',
                'xtype'  => 'progressbar',
                'height' => 20
            ]
        ];

        $window
            ->setNamespaceJS('Gm.be.mp.catalog')
            ->addRequire('Gm.be.mp.catalog.DownloadController')
            ->addRequire('Gm.StatusChecker')
            ->addCss('/download.css');
        return $window;
    }

    /**
     * Действие "status" выполняет проверу статуса загрузки компонента Маркетплейс.
     * 
     * @return Response
     */
    public function statusAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $response->setContent('loading');
        return $response;
    }

    /**
     * Действие "run" выполняет загрузку компонента Маркетплейс.
     * 
     * @return Response
     */
    public function runAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request = Gm::$app->request;

        /** @var string|null Идентификатор компонента Маркетплейс */
        $componentId = $request->post('id');
        if (empty($componentId)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['id']));
            return $response;
        }

        /** @var string|null Состояние компонента: 'install', 'update' */
        $state = $request->post('state');
        if (empty($state)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['state']));
            return $response;
        }

        /** @var \Gm\Backend\Marketplace\ApiCommand\ApiCommand $command */
        $command = Gm::$app->modules->getObject('ApiCommand\ApiCommand', 'gm.be.mp');
        /** @var false|array $component Атрибуты компонента Маркетплейс */
        $component = $command->getComponent($componentId);

        // если была ошибка запроса
        if ($component === false) {
            // если была ошибка при проверке лицензионного ключа
            if ($command->licenseKeyHasError()) {
                $response
                    ->meta
                        ->error()
                        ->set('mask', [
                            'title'   => Gm::t('app', 'Error'),
                            'message' => $command->getLocalizedError(),
                            'icon'    => 'license'
                        ]);
                // для отладки
                Gm::debug('API Marketplace', ['Error message (license)' => $command->getError()]);
            } else {
                $error = $command->getLocalizedError(true);
                if ($error === '') {
                    $error = $this->t($command->getError());
                }
                $response
                    ->meta->error($error);
                // для отладки
                Gm::debug('API Marketplace', ['Error message (components)' => $error]);
            }
            return $response;
        }
        if (empty($component)) {
            $response
                ->meta->error($this->t('Unable to get Marketplace catalog component'));
            return $response;
        }

        // создание каталога загрузки компонента Маркетплейс
        $downloadPath = Gm::alias('@runtime') . DS. 'components' . DS . pathinfo($component['package'], PATHINFO_FILENAME);
        if (file_exists($downloadPath)) {
            // удаляем всё в каталоге компонента если он был ранее создан
            Filesystem::deleteDirectory($downloadPath, true);
        } else {
            // создаём каталог компонента
            Filesystem::makeDirectory($downloadPath, 0755, true);
        }

        // имя загружаемого файла компонента Маркетплейс 
        $downloadFilename = $downloadPath . DS . $component['package'];

        // открытие файла для записи компонента
        $downloadHandle = fopen($downloadFilename, 'w+', true);
        if ($downloadHandle === false) {
            $response
                ->meta->error($this->t('Unable to open file {0} for writing Marketplace component', [$downloadFilename]));
            return $response;
        }

        /** @var \Gm\Backend\Marketplace\ApiCommand\ApiCommand $command */
        $command = Gm::$app->modules->getObject('ApiCommand\ApiCommand', 'gm.be.mp');
        /** @var false|array $downloaded Атрибуты компонента Маркетплейс */
        $downloaded = $command->getDownload($downloadHandle, $componentId);

        // закрытие файла компонента
        fclose($downloadHandle);

        // если была ошибка запроса
        if ($downloaded === false) {
            // если была ошибка при проверке лицензионного ключа
            if ($command->licenseKeyHasError()) {
                $response
                    ->meta
                        ->error()
                        ->set('mask', [
                            'title'   => Gm::t('app', 'Error'),
                            'message' => $command->getLocalizedError(),
                            'icon'    => 'license'
                        ]);
                // для отладки
                Gm::debug('API Marketplace', ['Error message (license)' => $command->getError()]);
            } else {
                $error = $command->getLocalizedError(true);
                if ($error === '') {
                    $error = $this->t($command->getError());
                }
                $response
                    ->meta->error($error);
                // для отладки
                Gm::debug('API Marketplace', ['Error message (components)' => $error]);
            }
            return $response;
        }
        if (empty($downloaded)) {
            $response
                ->meta->error($this->t('Unable to get Marketplace catalog component'));
            return $response;
        }

        /** @var FilePackager $packager */
        $packager = new FilePackager(['filename' => $downloadFilename]);

        /** @var \Gm\FilePackager\Package $package */
        $package = $packager->getPackage([
            'path'   => $downloadPath,
            'format' => 'json'
        ]);

        if (!$packager->unpack($package)) {
            $response
                ->meta->error($packager->getError());
            return $response;
        }

        if (!$package->load(true)) {
            $response
                ->meta->error($package->getError());
            return $response;
        }

        if (!$package->extract()) {
            $response
                ->meta->error($package->getError());
            return $response;
        }

        // если выполняется обновление компонента Маркетплейс
        if ($state === 'update') {
            // вид обновляемого компонента Маркетплейс
            $type = $component['typeId'] ?? '';
            switch ($type) {
                // через менеджер модулей
                case 1: $route = '@backend/marketplace/mmanager/update'; break;
                // через менеджер расширений модулей
                case 2: $route = '@backend/marketplace/emanager/update'; break;
                // через менеджер виджетов
                case 3: $route = '@backend/marketplace/wmanager/update'; break;
                default: $route = '';
            }

            // если известно кто выполняет обновление
            if ($route) {
                $response
                    ->meta->cmdLoadWidget($route, ['id' => $componentId]);
            } else {
                $response
                    ->meta->error($this->t('Cannot invoke component installer because component type is not defined'));
                return $response;
            }
        } else
        // если выполняется установка компонента Маркетплейс
        if ($state === 'install') {
            $response
                ->meta->cmdLoadWidget('@backend/marketplace/catalog/selection');
        }

        $response
            ->meta->success('ok');
        return $response;
    }

    /**
     * Действие "view" выводит интерфейс панели.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request = Gm::$app->request;

        /** @var string|null Идентификатор компонента Маркетплейс */
        $componentId = $request->post('id');
        if (empty($componentId)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['id']));
            return $response;
        }

        /** @var string|null Состояние компонента: 'install', 'update' */
        $state = $request->post('state');
        if (empty($state)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['state']));
            return $response;
        }

        /** @var \Gm\Backend\Marketplace\ApiCommand\ApiCommand $command */
        $command = Gm::$app->modules->getObject('ApiCommand\ApiCommand', 'gm.be.mp');
        /** @var false|array $component Атрибуты компонента Маркетплейс */
        $component = $command->getComponent($componentId);

        // если была ошибка запроса
        if ($component === false) {
            // если была ошибка при проверке лицензионного ключа
            if ($command->licenseKeyHasError()) {
                $response
                    ->meta
                        ->error()
                        ->set('mask', [
                            'title'   => Gm::t('app', 'Error'),
                            'message' => $command->getLocalizedError(),
                            'icon'    => 'license'
                        ]);
                // для отладки
                Gm::debug('API Marketplace', ['Error message (license)' => $command->getError()]);
            } else {
                $error = $command->getLocalizedError(true);
                if ($error === '') {
                    $error = $this->t($command->getError());
                }
                $response
                    ->meta->error($error);
                // для отладки
                Gm::debug('API Marketplace', ['Error message (components)' => $error]);
            }
            return $response;
        }
        if (empty($component)) {
            $response
                ->meta->error($this->t('Unable to get Marketplace catalog component'));
            return $response;
        }

        /** @var DownloadWindow $widget */
        $widget = $this->createDownloadWidget($component);
        // если была ошибка при формировании виджета
        if ($widget === false) {
            return $response;
        }
        // устанавливаем окну виджета состояние загрузки компонента (install, update), 
        // чтобы контроллер (DownloadController) после загрузке компонента знал, что необходимо сделать
        $widget->state = $state;

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }
}
