/*!
 * Контроллер компонента каталога Маркетплейс.
 * Расширение "Каталог Маркетплейс".
 * Модуль "Маркетплейс".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.mp.catalog.ComponentController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-be-mp-catalog-component',

    /**
     * Событие при клике на кнопке формы "Загрузить и Установить".
     * @param {Object} me 
     */
     onComponentDownload: (me) => {
        me.up('window').close();
    }
});
