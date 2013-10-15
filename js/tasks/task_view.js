/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.views.task = Backbone.View.extend({
    tagName: 'tr',
    className: 'rowHighlight',
    events : {
        'click span.glyphicon-unchecked' : 'markRead'
    },
    
    initialize : function () {
        console.log('Collection fom view' + JSON.stringify(this.model));
    },
    
    render : function () {
        this.$el.empty();
        var compiled = _.template($('#task-template').html(), 
                        {model : this.model});
                        
        this.$el.append(compiled);
        
        return this;
        
    },
    
    markRead :  function(e) {
        e.stopPropagation();

        this.model.save('status',2);

        Backbone.Events.trigger('refresh:tasks', this.model.collection);
        //this.trigger('refresh:tasks');
    }
});

