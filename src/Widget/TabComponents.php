<?php
/**
 * Этот файл является частью пакета GM Panel.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\Catalog\Widget;

use Gm;
use Gm\Stdlib\Collection;
use Gm\Panel\Widget\TabWidget;

/**
 * Виджет для формирования интерфейса вкладки каталога компонентов Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Widget
 * @since 1.0
 */
class TabComponents extends TabWidget
{
    /**
     * Каталог компонентов Маркетплейс.
     * 
     * @var Collection
     */
    public Collection $components;

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // панель компонентов Маркетплейс (Gm.be.mp.catalog.View GmJS)
        $this->components = Collection::createInstance([
            'cls'    => 'g-panel_background',
            'xtype'  => 'gm-be-mp-catalog-panel',
            'router' => [
                'route' => '',
                'rules' => [
                    'data'      => '{route}/components/data',
                    'component' => '{route}/component/view'
                ]
            ],
            'rbar' => [
                'cls'    => 'g-panel_background-color',
                'items' => [
                    [
                        'xtype'       => 'button',
                        'cls'         => 'g-button-tool',
                        'tooltip'     => '#License',
                        'iconCls'     => 'g-icon_mcatalog-tbar-license',
                        'handler'     => 'loadWidget',
                        'handlerArgs' => [
                            'route' => '@backend/config/license'
                        ]
                    ],
                    [
                        'xtype'       => 'button',
                        'cls'         => 'g-button-tool',
                        'tooltip'     => '#Installing downloaded Marketplace components',
                        'iconCls'     => 'g-icon_mcatalog-tbar-selection',
                        'handler'     => 'loadWidget',
                        'handlerArgs' => [
                            'route' => '@backend/marketplace/catalog/selection'
                        ]
                    ],
                    [
                        'xtype'   => 'button',
                        'cls'     => 'g-button-tool',
                        'tooltip' => '#Refresh catalog',
                        'iconCls' => 'g-icon-tool g-icon-tool_default x-tool-refresh',
                        'handler' => 'onRefresh'
                    ]
                ]
            ],
            'pagingtoolbar' => [
                'xtype'       => 'pagingtoolbar',
                'dock'        => 'bottom',
                'displayInfo' => true,
                'plugins'     => ['pagesize'],
                'cls'         => 'g-panel_background-color'
            ],
            'catalogView' => [
                'tpl' => $this->getShortItemView()
            ]
        ]);

        $this->title   = '#{name}';
        $this->tooltip = [
            'icon'  => $this->imageSrc('/icon.svg'),
            'title' => '#{name}',
            'text'  => '#{description}'
        ];
        $this->icon  = $this->imageSrc('/icon_small.svg');
        $this->items = [$this->components];
    }

    /**
     * @return array
     */
    protected function getFullItemView(): array
    {
        return [
            '<tpl for=".">',
                '<div class="g-mcatalog__item g-mcatalog__item_{typeCls}" title="{tooltip}">',
                    '<div class="g-mcatalog__thumb thumb-mcatalog">',
                        '<div class="thumb-mcatalog__icon">',
                            '<img class="{iconCls}" src="{icon}" title="{title:htmlEncode}">',
                            '<div class="thumb-mcatalog__title">{name}</div>',
                            '<div class="thumb-mcatalog__desc">{description}</div>', 
                        '</div>',
                        '<div class="thumb-mcatalog__ver">{version}</div>', 
                        '<div class="thumb-mcatalog__type">{type} <span>{use}</span></div>', 
                        '<div class="thumb-mcatalog__param"><label>' . $this->creator->t('Developer') . ':</label> {developer}</div>', 
                        '<div class="thumb-mcatalog__param"><label>' . $this->creator->t('Editions') . ':</label> <tpl if="editions">{editions}<tpl else>' . $this->creator->t('for all editions') . '</tpl></div>', 
                        '<tpl if="category"><div class="thumb-mcatalog__param"><label>' . $this->creator->t('Category') . ':</label> {category}</div></tpl>', 
                        '<div class="thumb-mcatalog__param"><label>' . $this->creator->t('License') . ':</label> {license}</div>', 
                        '<div class="thumb-mcatalog__cost"><tpl if="price &gt; 0">{price} руб.<tpl else>' . $this->creator->t('free') . '</tpl></div>', 
                        '<div class="thumb-mcatalog__status">{stateTitle}</div>', 
                        '<span class="thumb-mcatalog__state thumb-mcatalog__state-{state}"></span>',
                    '</div>',
                '</div>',
            '</tpl>',
            '<div class="x-clear"></div>'
        ];
    }

    /**
     * @return array
     */
    protected function getShortItemView(): array
    {
        return [
            '<tpl for=".">',
                '<div class="g-mcatalog__item g-mcatalog__item_{typeCls}" title="{tooltip}">',
                    '<div class="g-mcatalog__thumb thumb-mcatalog">',
                        '<div class="thumb-mcatalog__icon">',
                            '<img class="{iconCls}" src="{icon}" title="{title:htmlEncode}">',
                            '<div class="thumb-mcatalog__title">{name}</div>',
                            '<div class="thumb-mcatalog__desc">{description}</div>', 
                        '</div>',
                        '<div class="thumb-mcatalog__ver">{version}</div>', 
                        '<div class="thumb-mcatalog__type">{type} <span>{use}</span></div>', 
                        '<div class="thumb-mcatalog__param"><label>' . $this->creator->t('Developer') . ':</label> {developer}</div>', 
                        '<tpl if="category"><div class="thumb-mcatalog__param"><label>' . $this->creator->t('Category') . ':</label> {category}</div></tpl>', 
                        '<div class="thumb-mcatalog__param"><label>' . $this->creator->t('License') . ':</label> {license}</div>', 
                        '<div class="thumb-mcatalog__cost"><tpl if="price &gt; 0">{price} руб.<tpl else>' . $this->creator->t('free') . '</tpl></div>', 
                        '<div class="thumb-mcatalog__status">{stateTitle}</div>', 
                        '<span class="thumb-mcatalog__state thumb-mcatalog__state-{state}"></span>',
                    '</div>',
                '</div>',
            '</tpl>',
            '<div class="x-clear"></div>'
        ];
    }
}
