/*!
 * Контроллер загрузки компонента Маркетплейс.
 * Расширение "Каталог Маркетплейс".
 * Модуль "Маркетплейс".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.mp.catalog.DownloadController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-be-mp-catalog-download',

    /**
     * Событие после удаления панели формы компонента Маркетплейс.
     */
    destroy: () => {
        if (this.checker) {
            this.checker.stop();
        }
    },

    /**
     * Событие после отображения панели формы компонента Маркетплейс.
     * @param {Object} form Ext.form.Panel
     * @param {Object} eOpts
     */
     afterRender: (form, eOpts) => {
        var checker;
        let progress = Ext.getCmp('g-progress-download');
        let window = form.up('window');

        if (form.useFCGI) {
            checker = Gm.StatusChecker.create({
                url: Gm.url.build(form.router.build('status')),
                listeners: {
                    statusCheck: (status, response, checker) => {
                        if (status == 'loading') {
                            let value = checker.step / 10;
                            progress.setValue(value > 1 ? 1 : value);
                        } else
                        if (status == 'stop')
                            progress.setValue(1);
                    }
                }
            });
            checker.start();
        } else {
            progress.addListener('update', (me, value, text, eOpts ) => {
                me.updateText(Math.round(value * 100) + '%');
                if (me.isWaiting() && value > 0.6) {
                    me.clearTimer();
                }
            });
            progress.endWait = function () {
                this.clearTimer();
                setTimeout((scope) => { scope.setValue(1); }, 2000, this);
            };
            progress.wait({
                interval: 2000,
                duration: 50000,
                increment: 20,
                animate: true,
                scope: this
            });
        }

        form.submit({
            clientValidation: false,
            url: Gm.url.build(form.router.build('run')),
            params: {id: form.componentId, state: window.state},
            /**
             * Успешное выполнение запроса.
             * @param {Ext.form.Basic} self
             * @param {Object} action
             */
            success: (self, action) => {
                var response = Gm.response.normalize(action.response);
                if (checker) checker.stop();
                if (response.success) {
                    if (progress.isWaiting()) {
                        progress.endWait();
                    }
                } else {
                    Ext.Msg.exception(response, false, true);
                }
                window.close();
            },
            /**
             * Ошибка запроса.
             * @param {Ext.form.Basic} self
             * @param {Object} action
             */
            failure: (self, action) => {
                if (checker) checker.stop();
                window.close();
                Ext.Msg.exception(action, true);
            }
        });
        this.checker = checker;
    }
});
