Internationalization.panel.Home = function(config) {
    config = config || {};
    
    Ext.apply(config, {
        id          : 'internationalization-panel-home',
        cls         : 'container',
        items       : [{
            html        : '<h2>' + _('internationalization') + '</h2>',
            cls         : 'modx-page-header'
        }, {
            layout      : 'form',
            items       : [{
                html        : '<p>' + _('internationalization.resources_desc') + '</p>',
                bodyCssClass : 'panel-desc'
            }, {
                xtype       : 'internationalization-grid-resources',
                cls         : 'main-wrapper',
                preventRender : true
            }]
        }]
    });

    Internationalization.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(Internationalization.panel.Home, MODx.FormPanel);

Ext.reg('internationalization-panel-home', Internationalization.panel.Home);