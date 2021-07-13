Internationalization.combo.Context = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/contexts/getlist'
        },
        displayField : 'name',
        tpl         : new Ext.XTemplate('<tpl for=".">' +
            '<div class="x-combo-list-item">' +
                '<span style="font-weight: bold">{name:htmlEncode}</span>' +
                '<tpl if="key !== \'\'">' +
                    '<span style="font-style: italic; font-size: small;"> ({key:htmlEncode})</span>' +
                '</tpl>' +
            '</div>' +
        '</tpl>')
    });

    Internationalization.combo.Context.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.combo.Context, MODx.combo.Context);

Ext.reg('internationalization-combo-context', Internationalization.combo.Context);

Internationalization.combo.Resource = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/resources/getlist',
            context     : config.context || null,
            combo       : true
        },
        fields      : ['id', 'context_key', 'pagetitle'],
        hiddenName  : 'resource',
        pageSize    : 15,
        valueField  : 'id',
        displayField : 'pagetitle',
        typeAhead   : true,
        editable    : true,
        emptyText   : _('internationalization.select_a_resource'),
        tpl         : new Ext.XTemplate('<tpl for=".">' +
            '<tpl if="MODx.perm.tree_show_resource_ids == 1">' +
                '<div class="x-combo-list-item">' +
                    '{pagetitle:htmlEncode} <em>({id:htmlEncode})</em>' +
                '</div>' +
            '</tpl>' +
            '<tpl if="MODx.perm.tree_show_resource_ids != 1">' +
                '<div class="x-combo-list-item">' +
                    '{pagetitle:htmlEncode}' +
                '</div>' +
            '</tpl>' +
        '</tpl>')
    });

    Internationalization.combo.Resource.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.combo.Resource, MODx.combo.ComboBox);

Ext.reg('internationalization-combo-resource', Internationalization.combo.Resource);

Internationalization.combo.Namespace = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/namespaces/getlist',
            namespace   : null
        },
        fields      : ['name'],
        hiddenName  : 'namespace',
        valueField  : 'name',
        displayField : 'name',
        emptyText   : _('internationalization.select_a_namespace')
    });

    Internationalization.combo.Namespace.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.combo.Namespace, MODx.combo.ComboBox);

Ext.reg('internationalization-combo-namespace', Internationalization.combo.Namespace);

Internationalization.combo.Topic = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Internationalization.config.connector_url,
        baseParams  : {
            action      : 'mgr/topics/getlist',
            namespace   : null
        },
        fields      : ['id', 'name'],
        hiddenName  : 'topic',
        valueField  : 'id',
        displayField : 'name',
        emptyText   : _('internationalization.select_a_topic'),
        disabled    : true
    });

    Internationalization.combo.Topic.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.combo.Topic, MODx.combo.ComboBox, {
    setNamespace: function(namespace) {
        this.setDisabled(Ext.isEmpty(namespace));

        this.getStore().baseParams.namespace = namespace;
        this.getStore().load();

        this.reset();
    }
});

Ext.reg('internationalization-combo-topic', Internationalization.combo.Topic);