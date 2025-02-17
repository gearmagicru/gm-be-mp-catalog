/*!
 * Контроллер окна выбора компонента Маркетплейс.
 * Расширение "Каталог Маркетплейс".
 * Модуль "Маркетплейс".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.mp.catalog.SelectionController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.gm-be-mp-catalog-selection',

    /**
     * Событие после отображения окна выбора компонента Маркетплейс.
     * @param {Ext.window.Window} wnd
     * @param {Object} eOpts
     */
     afterRender: function (wnd, eOpts) {
        let select = wnd.el.select('.g-selection-item__btn', false);
        for (let i = 0; i < select.elements.length; i++) {
            select.elements[i].onclick = function () {
                let id = this.getAttribute('data-id'),
                    type = this.getAttribute('data-type');
                if (type) {
                    if (id) {
                        let route = '';
                        switch (type) {
                            case 'module': route = '@backend/marketplace/mmanager/install'; break;
                            case 'extension': route = '@backend/marketplace/emanager/install'; break;
                            case 'widget': route = '@backend/marketplace/wmanager/install'; break;
                        }
                        if (route)
                            Gm.app.widget.load(route, {installId: id});
                        else
                            Ext.Msg.error('Unable to determine component installer route');
                    } else
                        Ext.Msg.error('Unable to determine component install ID');
                } else
                    Ext.Msg.error('Unable to determine type component');
            };
        }
    }
});
