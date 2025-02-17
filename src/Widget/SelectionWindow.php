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
use Gm\Panel\Widget\Window;

/**
 * Виджет для формирования интерфейса окна выбора загруженного компонента Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Widget
 * @since 1.0
 */
class SelectionWindow extends Window
{
    /**
     * Заголовок в теле окна.
     * 
     * @var string
     */
    public string $headerText = '';

    /**
     * Подвал в теле окна.
     * 
     * @var string
     */
    public string $footerText = '';

    /**
     * Атрибуты компонентов Маркетплейс.
     * 
     * @var array
     */
    public array $selection = [];

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        $this->id         = 'selection'; // => g-marketplace-catalog-selection
        $this->padding    = 0;
        $this->controller = 'gm-be-mp-catalog-selection';
        $this->iconCls    = 'g-icon-svg g-icon_size_14 g-icon-m_color_base g-icon-m_save-1';
        $this->ui         = 'light';
        $this->layout     = 'vbox';
        $this->width      = 600;
        $this->height     = 600;
        $this->resizable  = true;
        $this->maximizable = true;
        $this->items     = [
            [
                'xtype'  => 'container',
                'cls'    => 'g-selection-header',
                'width'  => '100%',
                'height' => 60,
                'html'   => &$this->headerText
            ],
            [
                'xtype'      => 'container',
                'cls'        => 'g-selection-items',
                'width'      => '100%',
                'flex'       => 1,
                'autoScroll' => true,
                'items'      => &$this->selection
            ],
            [
                'xtype'  => 'container',
                'cls'    => 'g-selection-warning',
                'width'  => '100%',
                'height' => 90,
                'html'   => &$this->footerText
            ]
        ];
    }

    /**
     * Добавляет атрибуты компонента Маркетплейс.
     * 
     * @param array $selection Атрибуты компонента.
     * 
     * @return void
     */
    public function addSelection(array $selection): void
    {
        $this->selection[] = $selection;
    }

    /**
     * Устанавливает атрибуты компонентов Маркетплейс.
     * 
     * @param array $selection Атрибуты компонентов.
     * 
     * @return void
     */
    public function setSelections(array $selections): void
    {
        $this->selection = $selections;
    }

    /**
     * Возврашает представление атрибутов компонентов в формате HTML.
     * 
     * @return array
     */
    public function renderSelection(): array
    {
        $render = [];
        foreach ($this->selection as $item) {
            $icon = $item['icon'] ?? '';
            $render[] = [
                'xtype' => 'label',
                'html' => Html::tag('div',
                        [
                            Html::tag('div', '', ['class' => 'g-selection-item__icon', 'style' => 'background-image:url(' . $icon . ')']),
                            Html::tag('div', $item['name'] ?? '', ['class' => 'g-selection-item__title']),
                            Html::tag('div', $item['description'] ?? '', ['class' => 'g-selection-item__desc']),
                            Html::tag('div', $this->creator->t('Version') . ': <span>' . ($item['details'] ?? '') . '</span>', ['class' => 'g-selection-item__ver']),
                            Html::tag('div',
                                '<span class="g-icon g-icon-svg g-icon-m_color_base g-icon-m_save-1"></span>',
                                [
                                    'data-type' => $item['type'],
                                    'data-id'   => $item['installId'],
                                    'title'     => $this->creator->t('Go to the installer component'),
                                    'class'     => 'g-selection-item__btn'
                                ]
                            ),
                        ],
                        ['class' => 'g-selection-item']
                    ),
                'listeners' => ['click' => 'install']
            ];
        }
        return $render;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeRender(): bool
    {
        $this->makeViewID();
        $this->selection = $this->renderSelection();
        return true;
    }
}
