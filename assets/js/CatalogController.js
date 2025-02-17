/*!
 * Контроллер каталога Маркетплейс.
 * Расширение "Каталог Маркетплейс".
 * Модуль "Маркетплейс".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

/**
 * @class Gm.be.mp.catalog.ViewController
 * @extends Ext.app.ViewController
 * Контроллер каталога Маркетплейс.
 */
Ext.define('Gm.be.mp.catalog.ViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.gm-be-mp-catalog-view',

    /**
     * Загрузка компонента.
     * @param {Object} me
     */
     loadWidget: function (me) {
        me.handlerArgs.me = me;
        Gm.getApp().widget.loadBy(me.handlerArgs);
    },

    /**
     * Обновление компонентов Маркетплейс.
     * @param {Ext.app.ViewController} me
     */
    onRefresh: (me) => {
        me.up('gm-be-mp-catalog-panel').store.reload();
    },

    /**
     * Применяет фильтр.
     * @param {Ext.button.Button} me
     */
    onApplyFilter: (me) => {
        var values = me.up('panel').getForm().getValues(),
            search = {};

        for (let key of Object.keys(values)) {
            if (values[key]) {
                if (Ext.isNumeric(values[key]) || values[key] !== 'null')
                    search[key] = values[key];
            }
        }
        me.up('gm-be-mp-catalog-panel').store.setFilter(search);
    },

    /**
     * Сбрасывает фильтр.
     * @param {Ext.button.Button} me
     */
    onResetFilter: (me) => {
        me.up('panel').getForm().reset();
        me.up('gm-be-mp-catalog-panel').store.setFilter({});
    }
});
