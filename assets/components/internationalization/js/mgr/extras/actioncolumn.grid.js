Ext.grid.ActionColumn = Ext.extend(Ext.grid.Column, {
    header: '&#160;',
    actionIdRe: /x-action-col-(\d+)/,

    constructor: function(cfg) {
        var me = this,
            items = cfg.items || (me.items = [me]),
            l = items.length,
            i,
            item;

        Ext.grid.ActionColumn.superclass.constructor.call(me, cfg);

        me.renderer = function(v, meta) {
            v = Ext.isFunction(cfg.renderer) ? cfg.renderer.apply(this, arguments)||'' : '';

            for (i = 0; i < l; i++) {
                item = items[i];
                v += '<span class="icon x-grid-icon x-action-col-' + String(i) +
                     ' ' + (Ext.isFunction(item.getClass) ? item.getClass.apply(item.scope||this.scope||this, arguments) : '') + '"' +
                     ((item.getTooltip) ? ' ext:qtip="' + (Ext.isFunction(item.getTooltip) ? item.getTooltip.apply(item.scope||this.scope||this, arguments) : '') + '"' : '') + '></span>';
            }
            return v;
        };
    },

    destroy: function() {
        delete this.items;
        delete this.renderer;
        return Ext.grid.ActionColumn.superclass.destroy.apply(this, arguments);
    },

    processEvent : function(name, e, grid, rowIndex, colIndex){
        var m = e.getTarget().className.match(this.actionIdRe),
            item, fn;
        if (m && (item = this.items[parseInt(m[1], 10)])) {
            if (name == 'click') {
                (fn = item.handler || this.handler) && fn.call(item.scope||this.scope||this, grid, grid.getStore().getAt(rowIndex), rowIndex, colIndex, item, e);
            } else if ((name == 'mousedown') && (item.stopSelection !== false)) {
                return false;
            }
        }

        return Ext.grid.ActionColumn.superclass.processEvent.apply(this, arguments);
    }
});

Ext.apply(Ext.grid.Column.types, {
    actioncolumn: Ext.grid.ActionColumn
});