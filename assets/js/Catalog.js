/**
 * Виджет каталога Маркетплейс.
 
 * Этот файл является частью GM Panel.
 *
 * Copyright (c) 2015 Веб-студия GearMagic
 * 
 * Contact: https://gearmagic.ru
 *
 * @author    Anton Tivonenko
 * @copyright (c) 2015, by Anton Tivonenko, anton.tivonenko@gmail.com
 * @date      Oct 01, 2015
 * @version   $Id: 1.0 $
 *
 * @license Catalog.js is licensed under the terms of the Open Source
 * LGPL 3.0 license. Commercial use is permitted to the extent that the
 * code/component(s) do NOT become part of another Open Source or Commercially 
 * development library or toolkit without explicit permission.
 */

/**
 * Шаблон макета для отображения компонентов Маркетплейс.
 * @class Gm.marketplace.catalog.View
 * @extends Ext.view.View
 */
Ext.define('Gm.be.mp.catalog.View', {
    extend: 'Ext.view.View',
    xtype: 'gm-be-mp-catalog-view',
    cls: 'g-mcatalog',
    padding: 0,
    multiSelect: true,
    trackOver: true,
    overItemCls: 'g-mcatalog__item_over',
    selectedItemCls: 'g-mcatalog__item_selected',
    itemSelector: 'div.g-mcatalog__item',
    tpl: [
        '<tpl for=".">',
            '<div class="g-mcatalog__item g-mcatalog__item_{type}" title="{tooltip}">',
                '<div class="g-mcatalog__thumb thumb-mcatalog">',
                    '<div class="thumb-mcatalog__icon">',
                        '<img class="{iconCls}" src="{icon}" title="{title:htmlEncode}">',
                        '<div class="thumb-mcatalog__title">{name}</div>',
                        '<div class="thumb-mcatalog__desc">{description}</div>', 
                    '</div>',
                    '<div class="thumb-mcatalog__ver">{version}</div>', 
                    '<div class="thumb-mcatalog__type">{type} <span>{use}</span></div>', 
                    '<div class="thumb-mcatalog__param"><label>Author:</label> {developer}</div>', 
                    '<div class="thumb-mcatalog__param"><label>Edition:</label> {editions}</div>', 
                    '<div class="thumb-mcatalog__param"><label>Category:</label> {category}</div>', 
                    '<div class="thumb-mcatalog__cost">{price}</div>', 
                    '<div class="thumb-mcatalog__status">{stateTitle}</div>', 
                    '<span class="thumb-mcatalog__state thumb-mcatalog__state-{state}"></span>',
                '</div>',
            '</div>',
        '</tpl>',
        '<div class="x-clear"></div>'
    ],

    /**
     * @cfg {String} itemUrl
     * URL-адрес для получения информации о компоненте Маркетплейс.
     */
    itemUrl: '',

    /**
     * Обработчик событий макета.
     * @cfg {Object}
     */
    listeners: {
        /**
         * Событие после рендера компонента.
         * @param {Gm.view.shortcuts.Shortcuts} me
         * @param {Object} eOpts Параметры слушателя.
         */
        afterrender: function (me) {
            me.store.load();
        },
        /**
         * Событие при клике на элементе.
         * @param {Gm.view.shortcuts.Shortcuts} me
         * @param {Ext.data.Model} record Запись, которая принадлежит элементу.
         * @param {HTMLElement} Элемент.
         * @param {Number} Индекс элемента.
         * @param {Ext.event.Event} e Необработанный объект события.
          *@param {Object} eOpts Параметры слушателя.
         */
        itemclick: function (me, record, item, index, e, eOpts) {
            Gm.getApp().widget.load(me.itemUrl, {id: record.data.componentId});
        }
    }
});


/**
 * Панель компонентов Маркетплейс.
 * @class Gm.be.mp.catalog.Panel
 * @extends Ext.Panel
 */
Ext.define('Gm.be.mp.catalog.Panel', {
    extend: 'Ext.Panel',
    xtype: 'gm-be-mp-catalog-panel',
    controller: 'gm-be-mp-catalog-view',
    autoScroll: true,

    /**
     * @cfg {Gm.be.mp.catalog.View} catalogView
     * Шаблон макета компонентов Маркетплейс.
     */
    catalogView: {},

    /**
     * Инициализация хранилища.
     * @return {Ext.data.Store}
     * @private
     */
    initStore: function () {
        var me = this;

        me.store = {
            model: Ext.create('Ext.data.Model', {
                fields: [
                   {name: 'title'},
                   {name: 'description'},
                   {name: 'tooltip'},
                   {name: 'icon'},
                   {name: 'iconCls'},
                   {name: 'disabled'}
                ]
            }),
            proxy: {
                type: 'ajax',
                url: Gm.url.build(me.router.build('data')),
                actionMethods: { read: 'POST' },
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            setFilter: function (filter) {
                this.proxy.setExtraParams({filter: Ext.encode(filter)});
                this.reload();
                return this;
            },
            listeners: {
                /**
                 * Загрузка.
                 * @param {Ext.data.Store} me
                 * @param {Ext.data.Model} records  Записи.
                 * @param {Boolean} successful Если true, операция прошла успешно.
                 * @param {Object} eOpts Параметры слушателя.
                */
                load: function (store, records, successful, operation, eOpts) {
                    var response;
                    if (!successful) {
                        if (!Ext.isDefined(operation.error))
                            response = operation._response;
                        else
                            response = operation.error.response;
                        Ext.Msg.exception(response, true, true);
                    }
                }
            }
        };
        return Ext.create('Ext.data.Store', me.store);
    },

    /**
     * Инициализация компонента.
     * @param {Object} config Параметры инициализации.
     */
    initComponent: function (config) {
        var me = this;

        me.router = new Gm.ActionRouter(me.router || {});

        me.store = me.initStore();
        me.items = [
            Ext.applyIf({
                xtype: 'gm-be-mp-catalog-view',
                store: me.store,
                itemUrl: me.router.build('component')
            }, me.catalogView)
        ];

        /**
         * Инициализация панели инструментов и пагинатора.
         */
        if (this.dockedItems == null) {
            this.dockedItems = [];
        }
        if (Ext.isDefined(this.pagingtoolbar)) {
            this.pagingtoolbar.store = me.store;
            this.dockedItems.push(this.pagingtoolbar);
        }

        me.callParent(arguments);
    }
});