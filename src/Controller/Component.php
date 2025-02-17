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
use Gm\Panel\Http\Response;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Controller\BaseController;
use Gm\Backend\Marketplace\Catalog\Widget\ComponentWindow;

/**
 * Контроллер компонента кателога Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Controller
 * @since 1.0
 */
class Component extends BaseController
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
     * Создаёт виджет для формирования интерфейса окна установки компонента.
     * 
     * @param array $params Параметры компонента Маркетплейс.
     * 
     * @return ComponentWindow
     */
    public function createWindowWidget(array $params): ComponentWindow
    {
        /** @var ComponentWindow Окно установки компонента (Ext.window.Window Sencha ExtJS) */
        $window = new ComponentWindow();
        $window
            ->setComponentId($params['componentId'])
            ->setIcon($params['icon'])
            ->setTitle($params['name'], $params['description'])
            ->defineState($params['componentId'], $params['typeCode'], $params['version']);

        $notSpecified = $this->t('not specified');

        /* Добавление сайдбара (Ext.container.Container Sencha ExtJS) */
        $window
            ->sidebar
                ->addItem(
                    $this->t('Added'),
                    $params['publishDate'] ? Gm::$app->formatter->toDate($params['publishDate']) : $notSpecified,
                    'far fa-calendar-day'
                )
                ->addItem(
                    $this->t('Updated'),
                    $params['updateDate'] ? Gm::$app->formatter->toDate($params['updateDate']) : $notSpecified,
                    'far fa-calendar-day'
                )
                ->addItem(
                    $this->t('Current version'),
                    $params['version'],
                    'far fa-code-branch'
                )
                ->addEditionItem(
                    $this->t('For editions'),
                    $params['editions'],
                    $this->t('for all'),
                    'far fa-compact-disc'
                )
                ->addLicenseItem(
                    $this->t('License'),
                    $params['license'] ? 
                        [[$params['license']['shortname'], $params['license']['url']]] : [],
                    $notSpecified,
                    $this->t('Terms of use'),
                    'fas fa-gavel'
                )
                ->addItem(
                    $this->t('Author'),
                    $params['developer'] ? $params['developer']['name'] : $notSpecified,
                    'fas fa-user'
                );

        /* Добавление сообщений на вкладку "Подробнее" */
        $textAbout = &$params['textAbout'];
        /** @var string $message */
        $message = '';
        /** @var string $messageAl (info, danger) */
        $messageAl = 'info';

        // если установка компонента Маркетплейс
        if ($window->state === 'install') {
            $message = $this->t('Install the new version {1} of the {0} Marketplace component', [$params['version'], $params['type']]);
        } else
        // если обновление компонента Маркетплейс
        if ($window->state === 'update') {
            /** @var null|string $version Версия установленного компонента Маркетплейс */
            $version = null;
            // определение версии установленного компонента
            switch ($params['typeCode']) {
                case 'module':
                    /** @var null|string $version */
                    $version = Gm::$app->modules->getRegistry()->getVersion($params['componentId']);
                    break;

                case 'extension':
                    /** @var null|string $version */
                    $version = Gm::$app->extensions->getRegistry()->getVersion($params['componentId']);
                    break;

                case 'widget':
                    /** @var null|string $version */
                    $version = Gm::$app->widgets->getRegistry()->getVersion($params['componentId']);
                    break;
    

                default:
                    $message   = $this->t('Error: unable to determine type of installed component');
                    $messageAl = 'danger';
            }
            if ($version)
                $message = $this->t('Update the version of your component ({0}) from {1} to {2}', [$params['type'], $version, $params['version']]);
            else {
                $message   = $this->t('Error: unable to determine installed component version');
                $messageAl = 'danger';
            }
        } else
        // если установлена последняя версия
        if ($window->state === '') {
            $message = $this->t('You have the latest version {0} of the Marketplace component ({1})', [$params['version'], $params['type']]);
        } else
            $message = '';
        // если есть сообщение
        if ($message) {
            $message = '<label class="alert alert-' . $messageAl . '">' . $message . '</label>';
            if ($messageAl === 'danger') {
                $window->state = '';
            }
        }
        $textAbout = $message . ($textAbout ?: $this->t('Component description missing'));

        // получение содержимого вкладок компонента
        $tabsContent = $window
            ->tabs->paramsToTabsContent([
                [
                    'name'    => 'details',
                    'content' => $params['textAbout']
                ],
                [
                    'name'    => 'changelog',
                    'content' => $params['textChangelog']
                ],
                [
                    'name'    => 'install',
                    'content' => $params['textInstall']
                ]
            ], ['details', 'screenshots', 'install', 'reviews', 'changelog']);

        // вкладки компонента (Ext.tab.Panel Sencha ExtJS)
        $window
            ->tabs
                ->addTab(
                    $this->t('Details'),
                    $this->t('Description of the Marketplace component'),
                    'details', 
                    $tabsContent['default']['details'] ?? '',
                    $this->t('Component description missing')
                )
                ->addTab(
                    $this->t('Screenshots'),
                    $this->t('Screenshots of the Marketplace component'),
                    'screenshots',
                    $tabsContent['default']['screenshots'] ?? '',
                    $this->t('Screenshots of component missing')
                )
                ->addTab(
                    $this->t('Installing'),
                    $this->t('Marketplace component installation guide'),
                    'install',
                    $tabsContent['default']['install'] ?? '',
                    $this->t('Installation guide missing')
                )
                ->addTab(
                    $this->t('Reviews'),
                    $this->t('Reviews about the Marketplace component'),
                    'reviews',
                    $tabsContent['default']['reviews'] ?? '',
                    $this->t('Log in to leave a review or ask a question to the developer')
                )
                ->addTab(
                    $this->t('Changelog'),
                    $this->t('Contributors, developers, changelog'),
                    'changelog',
                    $tabsContent['default']['changelog'] ?? '',
                    $this->t('There are no entries in the changelog')
                );

        // дополнительные вкладки
        if ($tabsContent['append']) {
            foreach ($tabsContent['append'] as $name => $content) {
                // вкладка своей лицензии
                if ($name === 'license') {
                    $window
                        ->tabs
                            ->addTab(
                                $this->t('Terms of use'),
                                $this->t('Contributors, developers, changelog'),
                                'license',
                                $content ?? '',
                                $this->t('No terms of use')
                            );
                } else
                    $window
                        ->tabs
                            ->addTextTab($name, $content, $this->t('Tab text is temporarily missing'));
            }
        }

        $window
            ->setNamespaceJS('Gm.be.mp.catalog')
            ->addRequire('Gm.be.mp.catalog.ComponentController')
            ->addCss('/component.css');
        return $window;
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

        /** @var ComponentWindow|false $widget */
        $widget = $this->createWindowWidget($component);
        // если была ошибка при формировании виджета
        if ($widget === false) {
            return $response;
        }

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }
}
