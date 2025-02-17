<?php
/**
 * Виджет модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\Catalog\Widget;

use Gm;
use Gm\Panel\Widget\Form;
use Gm\Panel\Widget\Window;
use Gm\Panel\Helper\ExtForm;

/**
 * Виджет для формирования интерфейса окна информации о компоненте Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Widget
 * @since 1.0
 */
class ComponentWindow extends Window
{
    /**
     * Виджет интерфейса формы.
     * 
     * @var Form
     */
    public Form $form;

    /**
     * Виджет интерфейса сайдбара компонента.
     * 
     * @var ComponentSidebar
     */
    public ComponentSidebar $sidebar;

    /**
     * Виджет интерфейса вкладов компонента.
     * 
     * @var ComponentTabs
     */
    public ComponentTabs $tabs;

    /**
     * Идентификатор компонента Маркетплейс.
     * 
     * @see ComponentWindow::setComponentId()
     * 
     * @var string
     */
    protected string $componentId = '';

    /**
     * Состояние окна (update, install, '').
     * 
     * @see ComponentWindow::defineState()
     * 
     * @var null|string
     */
    public string $state;

    /**
     * Доступность элементов управления окна.
     * 
     * @var bool
     */
    public bool $enabled = true;

    /**
     * {@inheritdoc}
     */
    public array $requires = [
        'Gm.view.window.Window',
        'Gm.view.form.Panel'
    ];

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // сайдбар компонента (Ext.panel.Panel ExtJS)
        $this->sidebar = new ComponentSidebar([], $this);
        // панель вкладок компонента (Ext.tab.Panel ExtJS)
        $this->tabs = new ComponentTabs([], $this);

        // панель формы (Gm.view.form.Panel GmJS)
        $this->form = new Form([
            'id'     => 'catcmp',// => g-marketplace-catalog-cmp
            'cls'    => 'g-catcmp__form',
            'layout' => [
                'type'  => 'hbox',
                'align' => 'stretch'
            ],
            'controller' => 'gm-be-mp-catalog-component',
            'router' => [
                //'id'    => $rowId,
                'route' => Gm::alias('@match', '/catalog'),
                'state' => Form::STATE_CUSTOM,
                'rules' => [
                    'download' => '{route}/download/view'
                ],
            ],
            'items' => [
                $this->sidebar,
                $this->tabs
            ]
        ], $this);

        // свойства окна (Ext.window.Window ExtJS)
        $this->cls         = 'g-window_profile';
        $this->ui          = 'install';
        $this->layout      = 'fit';
        $this->width       = 950;
        $this->height      = 600;
        $this->resizable   = true;
        $this->maximizable = true;
        $this->items       = [$this->form];
    }

    /**
     * Устанавливает идентификатор компонента Маркетплейс для загрузки.
     * 
     * @param string $id Идентификатор компонента Маркетплейс.
     * 
     * @return $this
     */
    public function setComponentId(string $id): static
    {
        $this->componentId = $id;
        return $this;
    }

    /**
     * Устанавливает заголовок окну.
     * 
     * @param string $name Название заголовка.
     * @param string $description Описание заголовка.
     * 
     * @return $this
     */
    public function setTitle(string $name, string $description): static
    {
        $this->title = $this->creator->t('{component.title}', [$name,  $description]);
        return $this;
    }

    /**
     * Устанавливает значок окну.
     * 
     * @param string $icon URL-адрес значка.
     * 
     * @return $this
     */
    public function setIcon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeRender(): bool
    {
        $this->form->buttons = $this->renderFormButtons();
        return true;
    }

    /**
     * Выводит кнопки окна.
     * 
     * @return array
     */
    protected function renderFormButtons(): array
    {
        if ($this->enabled) {
            $buttons = [];
            switch ($this->state) {
                case 'install':
                    $buttons['action'] = [
                        'iconCls'     => 'g-icon-svg g-icon_size_14 g-icon-m_download',
                        'text'        => $this->creator->t('Download and Install'),
                        'handler'     => 'loadWidget',
                        'handlerArgs' => [
                            'closeWindow' => true,
                            'params'      => ['id' => $this->componentId, 'state' => 'install'],
                            'route'       => '@backend/marketplace/catalog/download/view'
                        ]
                    ];
                    break;

                case 'update':
                    $buttons['action'] = [
                        'iconCls'     => 'g-icon-svg g-icon_size_14 g-icon-m_download',
                        'text'        => $this->creator->t('Download and Update'),
                        'handler'     => 'loadWidget',
                        'handlerArgs' => [
                            'closeWindow' => true,
                            'params'      => ['id' => $this->componentId, 'state' => 'update'],
                            'route'       => '@backend/marketplace/catalog/download/view'
                        ]
                    ];
                    break;
            }
            $buttons['close'] = ['text' => $this->creator->t('Close')];
            return ExtForm::buttons($buttons);
        }
        return [];
    }

    /**
     * Определяет состояние окна в зависимости от вида компонента и его версии.
     * 
     * Устанавливает {@see ComponentWindow::$state} состояние:
     * - 'install', установка;
     * - 'update', обновление;
     * - '', если установлен, но нет обновления.
     * 
     * @param string $componentId Идентификатор компонента.
     * @param string|null $typeCode Код вида компонента.
     * @param string $version Номер версии компонента.
     * 
     * @return $this
     */
    public function defineState(string $componentId, ?string $typeCode, string $version): static
    {
        // реестры установленных компонентов: модулей, расширений, виджетов
        if ($typeCode == 'module') // модуль
            $registry = Gm::$app->modules->getRegistry();
        else
        if ($typeCode == 'extension') // расширение
            $registry = Gm::$app->extensions->getRegistry();
        else
        if ($typeCode == 'widget') // виджет
            $registry = Gm::$app->widgets->getRegistry();
        else
            $registry = null;

        if ($registry) {
            $this->state = 'install';
            if ($registry->has($componentId)) {
                $clientVersion = $registry->getVersion($componentId);
                if ($clientVersion) {
                    $this->state = version_compare($version, $clientVersion, '>') ? 'update' : '';
                }
            }
        } else
            $this->state =  '';
        return $this;
    }
}
