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
 * Виджет для формирования интерфейса сайдбара компонента Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package  Gm\Backend\Marketplace\Catalog\Widget
 * @since 1.0
 */
class ComponentSidebar extends Widget
{
    /**
     * @var array
     */
    public array $items = [];

    /**
     * {@inheritdoc}
     */
    public Collection|array $params = [
        'cls'        => 'g-catcmp__nav',
        'padding'    => 5,
        'width'      => 280,
        'autoScroll' => true
    ];

    /**
     * @return string
     */
    public function renderItems(): string
    {
        $rows = [];
        foreach ($this->items as $item) {
            $rows[] = '<div class="g-catcmp__nav-item"><i class="' . $item['faIcon'] . '"></i> ' . $item['label'] . ': <span>' . $item['value']  . '</span></div>';
        }

        return implode('', $rows);
    }

    /**
     * Добавляет элемент сайдбару.
     * 
     * @param string $label Название элемента.
     * @param mixed $value Текст.
     * @param string $icon Значок элемента (faIcon).
     * 
     * @return $this
     */
    public function addItem(string $label, mixed $value, string $icon = ''): static
    {
        $this->items[] = ['label' => $label, 'value' => $value, 'faIcon' => $icon];
        return $this;
    }

    /**
     * Добавляет элемент (редакция веб-приложения) сайдбару.
     * 
     * @param string $label Название элемента.
     * @param mixed $value Редакция.
     * @param string $default Значение если редакция отсутствует.
     * @param string $icon Значок элемента (faIcon).
     * 
     * @return $this
     */
    public function addEditionItem(string $label, mixed $value, string $default = '', string $icon = ''): static
    {
        if ($value) {
            $editionLabel = '';
            foreach ($value as $edition) {
                if (is_string($edition))
                    $editionLabel .= '<li><span>' . $edition . '</span></li>';
                else 
                    $editionLabel .= '<li><span>' . $edition[0] . ' ' . $edition[1] . '</span></li>';
            }
            $editionLabel = '<ul>' . $editionLabel . '</ul>';
        } else
            $editionLabel = $default;

        $this->items[] = ['label' => $label, 'value' => $editionLabel, 'faIcon' => $icon];
        return $this;
    }

    /**
     * Добавляет элемент (вид лицензии) сайдбару.
     * 
     * @param string $label Название элемента.
     * @param mixed $value Вид лицензии.
     * @param string $default Значение если лицензия отсутствует.
     * @param string $ownerLicenseName Название если указан своя лицензия.
     * @param string $icon Значок элемента (faIcon).
     * 
     * @return $this
     */
    public function addLicenseItem(string $label, mixed $value, string $default = '', string $ownerLicenseName, string $icon = ''): static
    {
      // лицензия
        if ($value) {
            $tag = '';
            if (sizeof($value) > 1) {
                $tag = [];
                foreach ($value as $license) {
                    if (is_string($license)) {
                        // если указан своя лицензия
                        if ($license === 'license')  $license = $ownerLicenseName;

                        $tag[] = '<span>' . $license . '</span>';
                    } else
                        $tag[] = '<a target="_blank" href="' . $license[1] . '">' . $license[0] . '</a>';
                }
                $tag = implode(', ', $tag);
            } else {
                $license = $value[0];
                if (is_string($license)) {
                    // если указан своя лицензия
                    if ($license === 'license')  $license = $ownerLicenseName;

                    $tag = '<span>' . $license . '</span>';
                } else
                    $tag = '<a target="_blank" href="' . $license[1] . '">' . $license[0] . '</a>';
            }
        } else
            $tag = $default;

        $this->items[] = ['label' => $label, 'value' => $tag, 'faIcon' => $icon];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeRender(): bool
    {
        $this->html = $this->renderItems();
        return true;
    }
}
