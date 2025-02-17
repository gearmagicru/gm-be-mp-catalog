<?php
/**
 * Виджет модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\Catalog\Widget;

use Gm\Stdlib\Collection;
use Gm\Panel\Widget\Widget;

/**
 * Виджет для формирования интерфейса вкладок компонента Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Widget
 * @since 1.0
 */
class Components extends Widget
{
    /**
     * {@inheritdoc}
     */
    public Collection|array $params = [
        'xtype'           => 'tabpanel',
        'ui'              => 'flat-light',
        'flex'            => 1,
        'padding'         => '1px',
        'activeTab'       => 0,
        'enableTabScroll' => true,
        'defaults'        => [
            'bodyCls'    => 'g-catcmp__tab_body',
            'autoScroll' => true,
        ],
        'items' => []
    ];

}
