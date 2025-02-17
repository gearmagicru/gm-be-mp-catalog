<?php
/**
 * Виджет модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\Catalog\Widget;

use Gm\Helper\Html;
use Gm\Stdlib\Collection;
use Gm\Panel\Widget\Widget;
use Gm\Stdlib\StaticClass;

/**
 * Виджет для формирования интерфейса вкладок компонента Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Widget
 * @since 1.0
 */
class ComponentTabs extends Widget
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

    /**
     * Преобразует указанные вкладки в вкладки по умолчанию и дополнительные.
     * 
     * @param array $params Параметры вкладок.
     * @param array $defaultTabs Вкладки, которые являются вкладками по умолчанию.
     * 
     * @return array
     */
    public function paramsToTabsContent(array $params, array $defaultTabs = []): array
    {
        $result = ['default' => [], 'append' => []];
        foreach ($params as $tab) {
            $name = $tab['name'];
            if (in_array($name, $defaultTabs, true))
                $result['default'][$name] = $tab['content'];
            else
                $result['append'][$name] = $tab['content'];
        }
        return $result;
    }

    /**
     * Добавляет вкладку компоненту.
     * 
     * @param string $title Загловок вкладки.
     * @param string $desc Описание вкладки.
     * @param string $icon CSS значка вкладки.
     * @param string $content Текст вкладки.
     * @param string $default Значение по умолчанию если текст вкладки отсутствует.
     * 
     * @return $this
     */
    public function addTab(string $title, string $desc, string $icon, string $content, string $default = ''): static
    {
        $this->items[] = [
            "title" => $title,
            'items' => [
                [
                    'xtype' => 'displayfield',
                    'cls'   => 'g-form__display__header g-form__display__header_icon',
                    'width' => '100%',
                    'value' => Html::tags([
                        Html::tag('span', '', ['class' => 'g-icon g-icon_size_32 g-icon-svg g-icon-m_color_base g-icon_catcmp-header-' . $icon]),
                        Html::tag('div', $title, ['class' => 'g-form__display__text']),
                        Html::tag('div', $desc, ['class' => 'g-form__display__subtext'])
                    ])
                ],
                [
                    'xtype' => 'container',
                    'html'  => '<text>' . ($content ?: '<p>' . $default . '</p>') . '</text>'
                ]
            ]
        ];
        return $this;
    }

    /**
     * Добавляет вкладку компонента с текстом.
     * 
     * @param string $title Загловок вкладки.
     * @param string $content Текст вкладки.
     * @param string $default Значение по умолчанию если текст вкладки отсутствует.
     * 
     * @return $this
     */
    public function addTextTab(string $title, string $content, string $default = ''): static
    {
        $this->items[] = [
            "title" => $title,
            'items' => [
                [
                    'xtype' => 'container',
                    'html'  => '<text>' . ($content ?: '<p>' . $default . '</p>') . '</text>'
                ]
            ]
        ];
        return $this;
    }
}
