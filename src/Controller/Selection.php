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
use Gm\Backend\Marketplace\Catalog\Widget\SelectionWindow;

/**
 * Контроллер компонента каталога Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Controller
 * @since 1.0
 */
class Selection extends BaseController
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
     * Создаёт виджет для формирования интерфейса окна выбора компонента.
     * 
     * @return SelectionWindow
     */
    public function createSelectionWidget(): SelectionWindow
    {
        /** @var SelectionWindow Окно выбора компонента (Ext.window.Window Sencha ExtJS) */
        $window = new SelectionWindow();
        $window->title = $this->t('{selection.title}');
        $window->footerText = $this->t('{selection.footer}');

        /** @var \Gm\Extension\Marketplace\Catalog\Model\Selection */
        $selection = $this->getModel('Selection');

        /** @var array $items Все загруженные компоненты в веб-приложение */
        $items = $selection->getItems();
        $count = sizeof($items);
        $window->setSelections($items);
        $window->headerText = $this->module->t('{selection.header}', ["@plural", $count]);
        if ($count > 0)
            $window->headerText .= '<div style="font-size:13px">' . $this->t('You can install the components now or later using the Marketplace catalog') . '</div>';
        else {
            $window->headerText .= '<div style="font-size:13px">' . $this->t('To download components, use the Marketplace catalog') . '</div>';
            $window->height = 200;
        }

        $window
            ->setNamespaceJS('Gm.be.mp.catalog')
            ->addRequire('Gm.be.mp.catalog.SelectionController')
            ->addCss('/selection.css');
        return $window;
    }

    /**
     * Действие "view" выводит интерфейса окна.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var SelectionWindow $widget */
        $widget = $this->createSelectionWidget();
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
