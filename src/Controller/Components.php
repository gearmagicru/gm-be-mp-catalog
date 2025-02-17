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
use Gm\Panel\Widget\TabWidget;
use Gm\Panel\Helper\ExtCombo;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Controller\BaseController;
use Gm\Backend\Marketplace\Catalog\Widget\TabComponents;

/**
 * Контроллер компонентов панели Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Controller
 * @since 1.0
 */
class Components extends BaseController
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
     * {@inheritdoc}
     */
    public function createWidget(): TabComponents
    {
        /** @var TabComponents $tab Каталог компонентов Маркетплейс (Gm.be.mp.catalog.View GmJS) */
        $tab = new TabComponents();

        $tab->components->id = $this->module->viewId('panel');
        $tab->components->router['route'] = $this->module->route();

        /** @var \Gm\Session\Container $storage  */
        $storage = $this->module->getStorage();
        $terms = $storage->terms;

        // фильтр каталога компонентов
        $tab->components->rbar['items'][] = [
            'xtype'      => 'splitbutton',
            'iconCls'    => 'g-icon-tool g-icon-tool_default x-tool-search',
            'tooltip'    =>  Gm::t(BACKEND, 'Filtering records'),
            'arrowAlign' => 'bottom',
            'menu'       => [
                'mouseLeaveDelay' => 0,
                'items' => [
                    'xtype'       => 'form',
                    'cls'         => 'g-form-filter',
                    'flex'        => 1,
                    'width'       => 400,
                    'autoHeight'  => true,
                    'bodyPadding' => 10,
                    'defaults'    => [
                        'labelAlign' => 'right'
                    ],
                    'items' => [
                        ExtCombo::local('#Type', 'type', ExtCombo::store($terms['types'] ?? [], true, true)),
                        ExtCombo::local('#Category', 'category', ExtCombo::store($terms['categories'] ?? [], true, true)),
                        ExtCombo::local('#Developer', 'developer', ExtCombo::store($terms['developers'] ?? [], true, true)),
                        ExtCombo::local('#Edition', 'edition', ExtCombo::store($terms['editions'] ?? [], true, true)),
                        ExtCombo::local('#License type', 'license', ExtCombo::store($terms['licenseTypes'] ?? [], true, true)),
                        ExtCombo::side('#Use', 'use', true),
                        ExtCombo::local('#Payment', 'payment', [
                            'fields' => ['id', 'name'],
                                'data'   => [
                                    ['id' => 'all',  'name' => '#All'],
                                    ['id' => 'paid', 'name' => '#Paid'],
                                    ['id' => 'free', 'name' => '#Free']
                                ]
                            ]
                        ),
                        [
                            'xtype'      => 'textfield',
                            'name'       => 'name',
                            'fieldLabel' => '#Name',
                            'maxLength'  => 255,
                            'width'      => '100%',
                            'allowBlank' => true
                        ]
                    ],
                    'buttons'     => [
                        [
                            'text'    => '#Apply',
                            'handler' => 'onApplyFilter'
                        ], 
                        [
                            'text'    => '#Reset',
                            'handler' => 'onResetFilter'
                        ]
                    ]
                ]
            ]
        ];

        $tab
            ->setNamespaceJS('Gm.be.mp.catalog')
            ->addRequire('Gm.be.mp.catalog.CatalogController')
            ->addRequire('Gm.be.mp.catalog.Catalog')
            ->addRequire('Gm.view.plugin.PageSize')
            ->addCss('/catalog.css');
        return $tab;
    }

    /**
     * Действие "view" выводит интерфейса панели.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Backend\Marketplace\ApiCommand\ApiCommand $command */
        $command = Gm::$app->modules->getObject('ApiCommand\ApiCommand', 'gm.be.mp');
        /** @var false|array $terms Термины каталога Маркетплейс */
        $terms = $command->getTerms();

        // если была ошибка запроса
        if ($terms === false) {
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

        /** @var \Gm\Session\Container $storage  */
        $storage = $this->module->getStorage();
        $storage->terms = $terms;

        /** @var TabWidget $widget Виджет каталога Маркетплейс */
        $widget = $this->getWidget();
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

    /**
     * Действие "data" выводит элементы панели.
     * 
     * @return Response
     */
    public function dataAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Session\Container $storage  */
        $storage = $this->module->getStorage();

        /** @var \Gm\Backend\Marketplace\Catalog\Model\Components $model Модель данных каталога компонентов */
        $model = $this->getModel('Components', [
            'categories'   => $storage->terms['categories'] ?? [], // категории компонентов
            'editions'     => $storage->terms['editions'] ?? [], // редакции
            'types'        => $storage->terms['types'] ?? [], // виды компонентов
            'typeCodes'    => $storage->terms['typeCodes'] ?? [], // коды видов компонентов
            'licenseTypes' => $storage->terms['licenseTypes'] ?? [], // виды лицензий
            'developers'   => $storage->terms['developers'] ?? [], // разработчики
            'uses'         => [
                'backend'  => $this->t('backend'),
                'frontend' => $this->t('frontend')
            ],
            'stateTitles' => [
                'download'  => $this->t('download and install'),
                'update'    => $this->t('update to the latest current version'),
                'installed' => $this->t('latest version installed'),
                'install'   => $this->t('install latest version'),
                'disabled'  => $this->t('not available')
            ]
        ]);
        if ($model === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['Components']));
            return $response;
        }

        /** @var array|false $items Компоненты каталога Маркетплейс */
        $items = $model->getItems();
        /** @var \Gm\Backend\Marketplace\ApiCommand\ApiCommand $command */
        $command = $model->getApiCommand();

        // если была ошибка запроса
        if ($items === false) {
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

        $response
            ->meta->total = $items['total'];
        return $response->setContent($items['items']);
    }
}
