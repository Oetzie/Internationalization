Internationalization.grid.Resources = function(config) {
    config = config || {};

    config.tbar = ['->', {
        xtype       : 'textfield',
        name        : 'internationalization-filter-resources-search',
        id          : 'internationalization-filter-resources-search',
        emptyText   : _('search') + '...',
        listeners   : {
            'change'    : {
                fn          : this.filterSearch,
                scope       : this
            },
            'render'    : {
                fn          : function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key     : Ext.EventObject.ENTER,
                        fn      : this.blur,
                        scope   : cmp
                    });
                },
                scope       : this
            }
        }
    }, {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'internationalization-filter-resources-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var fields = [{
        header      : _('internationalization.label_resource'),
        dataIndex   : 'pagetitle',
        sortable    : false,
        editable    : false,
        width       : 250,
        renderer    : this.renderPageTitle
    }];

    Ext.iterate(Internationalization.config.record, (function(context) {
        var name = context.name;

        if (!Ext.isEmpty(context.locale)) {
            name += ' (' + context.locale.split('_').pop().toUpperCase() + ')';
        }

        fields.push({
            xtype       : 'actioncolumn',
            header      : name,
            dataIndex   : 'context[' + context.key + ']',
            sortable    : false,
            editable    : false,
            width       : 125,
            fixed       : true,
            items       : [{
                handler     : function (d, c) {
                    if (c.json.contexts[context.key]) {
                        Internationalization.openResource(this, c, context, 'grid');
                    } else {
                        Internationalization.createResource(this, c, context, 'grid');
                    }
                },
                getClass    : function (d, c, e) {
                    if (e.json.contexts[context.key]) {
                        return 'icon-external-link green';
                    }

                    return 'icon-plus red';
                },
                getTooltip  : function(d, c, e) {
                    if (e.json.contexts[context.key]) {
                        if (MODx.perm.tree_show_resource_ids) {
                            var resource = '<strong>' + e.json.contexts[context.key].translation.translate_pagetitle + ' (' + e.json.contexts[context.key].translation.translate_id + ')</strong>';
                        } else {
                            var resource = '<strong>' + e.json.contexts[context.key].translation.translate_pagetitle + '</strong>';
                        }

                        return _('internationalization.resource_open', {
                            resource : resource
                        });
                    }

                    return _('internationalization.resource_create');
                }
            }, {
                handler     : function (d, c) {
                    if (c.json.contexts[context.key]) {
                        Internationalization.unlinkResource(this, c, context, 'grid');
                    } else {
                        Internationalization.linkResource(this, c, context, 'grid');
                    }
                },
                getClass    : function (d, c, e) {
                    if (e.json.contexts[context.key]) {
                        return 'icon-unlink green';
                    }

                    return 'icon-link red';
                },
                getTooltip  : function(d, c, e) {
                    if (e.json.contexts[context.key]) {
                        if (MODx.perm.tree_show_resource_ids) {
                            var resource = '<strong>' + e.json.contexts[context.key].translation.translate_pagetitle + ' (' + e.json.contexts[context.key].translation.translate_id + ')</strong>';
                        } else {
                            var resource = '<strong>' + e.json.contexts[context.key].translation.translate_pagetitle + '</strong>';
                        }

                        return _('internationalization.resource_unlink', {
                            resource : resource
                        });
                    }

                    return _('internationalization.resource_link');
                }
            }]
        });
    }).bind(this));

    var columns = new Ext.grid.ColumnModel({
        columns     : fields
    });

    Ext.applyIf(config, {
        cm          : columns,
        id          : 'internationalization-grid-resources',
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/resources/getlinklist',
            context     : MODx.request.context || MODx.config.default_context
        },
        autosave    : false,
        fields      : ['id', 'context_key', 'pagetitle', 'contexts'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'pagetitle'
    });

    Internationalization.grid.Resources.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.grid.Resources, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';
        
        Ext.getCmp('internationalization-filter-resources-search').reset();
        
        this.getBottomToolbar().changePage(1);
    },
    renderPageTitle: function(d, c, e) {
        return '<a href="?a=resource/update&id=' + e.data.resource_id + '" title="' + _('edit') + '" class="x-grid-link">' + Ext.util.Format.htmlEncode(d) + '</a>';
    },
    renderBoolean: function(d, c) {
        c.css = parseInt(d) === 1 || d ? 'green' : 'red';

        return parseInt(d) === 1 || d ? _('yes') : _('no');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }
        
        return a;
    }
});

Ext.reg('internationalization-grid-resources', Internationalization.grid.Resources);