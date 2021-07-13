var Internationalization = function(config) {
    config = config || {};

    Internationalization.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization, Ext.Component, {
    page    : {},
    window  : {},
    grid    : {},
    tree    : {},
    panel   : {},
    combo   : {},
    config  : {},
    setMenu: function(record) {
        var toolbar = Ext.getCmp('modx-action-buttons');

        if (toolbar) {
            if (record.contexts) {
                var menu = [];

                Ext.iterate(record.contexts, (function(key, context) {
                    var name            = context.name;
                    var description     = '';
                    var icon            = '';
                    var actions         = [];

                    if (!Ext.isEmpty(context.description)) {
                        description = '<span class="x-menu-icon-description">' + context.description + '</span>';
                    }

                    if (!Ext.isEmpty(context.locale)) {
                        name += ' (' + context.locale.split('_').pop().toUpperCase() + ')';
                    }

                    if (context.translation) {
                        icon = 'icon-link';

                        if (MODx.perm.tree_show_resource_ids) {
                            var resource = '<strong>' + context.translation.translate_pagetitle + ' (' + context.translation.translate_id + ')</strong>';
                        } else {
                            var resource = '<strong>' + context.translation.translate_pagetitle + '</strong>';
                        }

                        actions.push({
                            text    : '<i class="x-menu-item-icon icon icon-external-link"></i> ' + _('internationalization.resource_open', {
                                resource : resource
                            }),
                            handler : function () {
                                this.openResource(this, record, context, 'resource');
                            },
                            scope   : this
                        }, '-', {
                            text    : '<i class="x-menu-item-icon icon icon-unlink"></i> ' +  _('internationalization.resource_unlink', {
                                resource : resource
                            }),
                            handler : function () {
                                this.unlinkResource(this, record, context, 'resource');
                            },
                            scope   : this
                        });
                    } else {
                        actions.push({
                            text    : '<i class="x-menu-item-icon icon icon-plus"></i> ' + _('internationalization.resource_create'),
                            handler : function () {
                                Internationalization.createResource(this, record, context, 'resource');
                            },
                            scope   : this
                        }, '-', {
                            text    : '<i class="x-menu-item-icon icon icon-link"></i> ' + _('internationalization.resource_link'),
                            handler : function () {
                                Internationalization.linkResource(this, record, context, 'resource');
                            },
                            scope   : this
                        });
                    }

                    menu.push({
                        text    : '<i class="x-menu-item-icon icon ' + icon + '"></i> ' + name + description,
                        menu    : actions
                    });
                }).bind(this));

                if (menu.length > 0) {
                    var position = 0;

                    toolbar.items.items.forEach(function (item, index) {
                        if (item.id === 'internationalization-menu') {
                            position = index;

                            item.destroy();
                        }
                    });

                    toolbar.insertButton(position, [{
                        text    : '<i class="icon icon-globe"></i> ' + _('internationalization'),
                        id      : 'internationalization-menu',
                        menu    : menu
                    }]);

                    toolbar.doLayout();
                }
            }
        }
    },
    createResource: function(btn, d, c, type) {
        MODx.msg.confirm({
            title       : _('internationalization.resource_create'),
            text        : _('internationalization.resource_create_confirm', {
                context     : c.name
            }),
            url         : Internationalization.config.connector_url,
            params      : {
                action      : 'mgr/resources/create',
                id          : d.id || d.resource,
                context     : c.key
            },
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        MODx.msg.status({
                            title   : _('success'),
                            message : data.message
                        });

                        var tree = Ext.getCmp('modx-resource-tree');

                        if (tree && tree.isVisible()) {
                            tree.refresh();
                        }

                        if (type === 'resource') {
                            Internationalization.setMenu(data.object);
                        } else if (type === 'grid') {
                            Ext.getCmp('internationalization-grid-resources').refresh();
                        }
                    },
                    scope       : this
                }
            }
        });
    },
    linkResource: function(btn, d, c, type) {
        if (Internationalization.window.linkResourceWindow) {
            Internationalization.window.linkResourceWindow.destroy();
        }

        Internationalization.window.linkResourceWindow = MODx.load({
            xtype       : 'internationalization-window-link',
            record      : {
                context     : c.key,
                original_id : d.id || d.resource
            },
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function (data) {
                        MODx.msg.status({
                            title   : _('success'),
                            message : data.a.result.message
                        });

                        if (type === 'resource') {
                            Internationalization.setMenu(data.a.result.object);
                        } else if (type === 'grid') {
                            Ext.getCmp('internationalization-grid-resources').refresh();
                        }
                    },
                    scope      : this
                }
            }
        });

        Internationalization.window.linkResourceWindow.setValues({
            context     : c.key,
            original_id : d.id || d.resource
        });
        Internationalization.window.linkResourceWindow.show();
    },
    unlinkResource: function(btn, d, c, type) {
        var translation = {};

        if (d.resource) {
            translation = d.contexts[c.key].translation;
        } else {
            translation = d.json.contexts[c.key].translation;
        }

        var resource = translation.translate_pagetitle;

        if (MODx.perm.tree_show_resource_ids) {
            resource += ' (' + translation.translate_id + ')';
        }

        MODx.msg.confirm({
            title       : _('internationalization.resource_unlink', {
                resource    : resource
            }),
            text        : _('internationalization.resource_unlink_confirm', {
                resource    :resource
            }),
            url         : Internationalization.config.connector_url,
            params      : {
                action      : 'mgr/resources/unlink',
                original_id : translation.original_id,
                translate_id : translation.translate_id
            },
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        MODx.msg.status({
                            title   : _('success'),
                            message : data.message
                        });

                        if (type === 'resource') {
                            Internationalization.setMenu(data.object);
                        } else if (type === 'grid') {
                            Ext.getCmp('internationalization-grid-resources').refresh();
                        }
                    },
                    scope       : this
                }
            }
        });
    },
    openResource: function(btn, d, c, type) {
        var translation = {};

        if (d.resource) {
            translation = d.contexts[c.key].translation;
        } else {
            translation = d.json.contexts[c.key].translation;
        }

        MODx.loadPage('?a=resource/update&id=' + translation.translate_id);
    }
});

Ext.reg('internationalization', Internationalization);

Internationalization = new Internationalization();

Internationalization.window.LinkResource = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('internationalization.resource_link'),
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/resources/link'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'original_id'
        }, {
            xtype       : 'internationalization-combo-resource',
            fieldLabel  : _('internationalization.label_resource'),
            description : MODx.expandHelp ? '' : _('internationalization.label_resource_desc'),
            name        : 'translate_id',
            hiddenName  : 'translate_id',
            anchor      : '100%',
            allowBlank  : false,
            context     : config.record.context
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('internationalization.label_resource_desc'),
            cls         : 'desc-under'
        }]
    });

    Internationalization.window.LinkResource.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.window.LinkResource, MODx.Window);

Ext.reg('internationalization-window-link', Internationalization.window.LinkResource);