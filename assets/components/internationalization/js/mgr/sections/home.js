Ext.onReady(function() {
    MODx.load({
        xtype : 'internationalization-page-home'
    });
});

Internationalization.page.Home = function(config) {
    config = config || {};
    
    config.buttons = [];
    
    if (Internationalization.config.branding_url) {
        config.buttons.push({
            text        : 'Internationalization ' + Internationalization.config.version,
            cls         : 'x-btn-branding',
            handler     : this.loadBranding
        });
    }

    config.buttons.push({
        xtype       : 'internationalization-combo-context',
        value       : MODx.request.context || MODx.config.default_context,
        name        : 'internationalization-filter-context',
        emptyText   : _('internationalization.filter_context'),
        listeners   : {
            'select'    : {
                fn          : this.filterContext,
                scope       : this
            }
        }
    });

    config.buttons.push({
        text        : '<i class="icon icon-download"></i>' + _('internationalization.export'),
        handler     : this.exportLexicons,
        scope       : this
    }, {
        text        : '<i class="icon icon-upload"></i>' + _('internationalization.import'),
        handler     : this.importLexicons,
        scope       : this
    });
    
    if (Internationalization.config.branding_url_help) {
        config.buttons.push({
            text        : _('help_ex'),
            handler     : MODx.loadHelpPane,
            scope       : this
        });
    }
    
    Ext.applyIf(config, {
        components  : [{
            xtype       : 'internationalization-panel-home',
            renderTo    : 'internationalization-panel-home-div'
        }]
    });

    Internationalization.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.page.Home, MODx.Component, {
    loadBranding: function(btn) {
        window.open(Internationalization.config.branding_url);
    },
    filterContext : function(tf) {
        MODx.loadPage('home', 'namespace=' + MODx.request.namespace + '&context=' + tf.getValue());
    },
    exportLexicons: function(btn, e) {
        if (this.exportLexiconsWindow) {
            this.exportLexiconsWindow.destroy();
        }

        this.exportLexiconsWindow = MODx.load({
            xtype       : 'internationalization-window-export-lexicons',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function (data) {
                        window.location = Internationalization.config.connector_url + '?action=mgr/lexicons/export&HTTP_MODAUTH=' + MODx.siteId + '&file=' + data.a.result.object.file;
                    },
                    scope       : this
                }
            }
        });

        this.exportLexiconsWindow.show(e.target);
    },
    importLexicons: function(btn, e) {
        if (this.importLexiconsWindow) {
            this.importLexiconsWindow.destroy();
        }

        this.importLexiconsWindow = MODx.load({
            xtype       : 'internationalization-window-import-lexicons',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function(response) {
                        MODx.msg.status({
                            title   : _('success'),
                            message : response.a.result.message,
                            delay   : 4
                        });
                    },
                    scope       : this
                },
                'failure'   : {
                    fn          : function(response) {
                        MODx.msg.alert(_('failure'), response.message);
                    },
                    scope       : this
                }
            }
        });

        this.importLexiconsWindow.show(e.target);
    }
});

Ext.reg('internationalization-page-home', Internationalization.page.Home);

Internationalization.window.ExportLexicons = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('internationalization.export'),
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/lexicons/export'
        },
        fields      : [{
            xtype       : 'internationalization-combo-namespace',
            fieldLabel  : _('internationalization.label_namespace'),
            description : MODx.expandHelp ? '' : _('internationalization.label_namespace_desc'),
            name        : 'namespace',
            anchor      : '100%',
            allowBlank  : false,
            listeners   : {
                select      : {
                    fn          : this.onHandleNamespace,
                    scope       : this
                }
            }
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('internationalization.label_namespace_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'internationalization-combo-topic',
            fieldLabel  : _('internationalization.label_topic'),
            description : MODx.expandHelp ? '' : _('internationalization.label_topic_desc'),
            name        : 'topic',
            anchor      : '100%',
            allowBlank  : false,
            id          : 'internationalization-combo-topic-export'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('internationalization.label_topic_desc'),
            cls         : 'desc-under'
        }],
        saveBtnText : _('export')
    });

    Internationalization.window.ExportLexicons.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.window.ExportLexicons, MODx.Window, {
    onHandleNamespace: function(tf) {
        var topic = Ext.getCmp('internationalization-combo-topic-export');

        if (topic) {
            topic.setNamespace(tf.getValue());
        }
    }
});

Ext.reg('internationalization-window-export-lexicons', Internationalization.window.ExportLexicons);

Internationalization.window.ImportLexicons = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('internationalization.import'),
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/lexicons/import'
        },
        fileUpload  : true,
        fields      : [{
            xtype       : 'internationalization-combo-namespace',
            fieldLabel  : _('internationalization.label_namespace'),
            description : MODx.expandHelp ? '' : _('internationalization.label_namespace_desc'),
            name        : 'namespace',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('internationalization.label_namespace_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('internationalization.label_topic'),
            description : MODx.expandHelp ? '' : _('internationalization.label_topic_desc'),
            name        : 'topic',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('internationalization.label_topic_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'fileuploadfield',
            fieldLabel  : _('internationalization.label_import_file'),
            description : MODx.expandHelp ? '' : _('internationalization.label_import_file_desc'),
            name        : 'file',
            anchor      : '100%',
            allowBlank  : false,
            buttonText  : _('upload.buttons.choose')
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('internationalization.label_import_file_desc'),
            cls         : 'desc-under'
        }],
        saveBtnText : _('import')
    });

    Internationalization.window.ImportLexicons.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.window.ImportLexicons, MODx.Window);

Ext.reg('internationalization-window-import-lexicons', Internationalization.window.ImportLexicons);