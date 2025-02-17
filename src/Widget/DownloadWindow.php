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
use Gm\Panel\Widget\Window;
use Gm\Panel\Widget\Form;

/**
 * Виджет для формирования интерфейса окна загрузки компонента Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Widget
 * @since 1.0
 */
class DownloadWindow extends Window
{
    /**
     * Виджет для формирования интерфейса формы.
     * 
     * @var Form
     */
    public Form $form;

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

        // панель формы (Gm.view.form.Panel GmJS)
        $this->form = new Form([
            'id'         => 'cmp',// => g-marketplace-catalog-cmp
            'cls'        => 'g-catcmp__form',
            'controller' => 'gm-be-mp-catalog-download',
            'useFCGI'    => Gm::$app->request->serverUseFCGI(),
            'router'     => [
                'route' => Gm::alias('@match', '/catalog'),
                'state' => Form::STATE_CUSTOM,
                'rules' => [
                    'run'    => '{route}/download/run',
                    'status' => '{route}/download/status',
                ]
            ],
            'items' => []
        ], $this);

        $this->id        = 'download'; // => g-marketplace-catalog-download
        $this->padding   = 0;
        $this->cls       = 'g-window_profile';
        $this->iconCls   = 'g-icon-svg g-icon_size_14 g-icon-m_color_base g-icon-m_download';
        $this->ui        = 'light';
        $this->layout    = 'fit';
        $this->width     = 500;
        $this->height    = 215;
        $this->resizable = false;
        $this->items     = [$this->form];
    }
}
