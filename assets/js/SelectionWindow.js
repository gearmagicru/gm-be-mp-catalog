/*!
 * Контроллер загрузки компонента Маркетплейс.
 * Расширение "Каталог Маркетплейс".
 * Модуль "Маркетплейс".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.mp.catalog.SelectionWindow', {
    extend: 'Ext.window.Window',
    xtype: 'g-selection-window',

    listeners: {
        /**
         * Событие после отображения панели формы компонента Маркетплейс.
         * @param {Object} form Ext.form.Panel
         * @param {Object} eOpts
         */
        show: function (wnd, eOpts) {
            let select = wnd.el.select('.g-selection-item__btn', false);
            for (let i = 0; i < select.elements.length; i++) {
                select.elements[i].onclick = function () {
                };
            }
        }
    }

});
