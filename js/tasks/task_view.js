/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.views.task = Backbone.View.extend({
    tagName: 'tr',
    className: 'rowHighlight',
   
    events : {
        'click span.glyphicon-unchecked' : 'markRead',
        'click span.glyphicon-check' : 'markUnread',
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
        
        var that = this;

        this.model.save({'id': this.model.get('id'), 'status':2}, {
                                        url: 'api/tasks.php?action=update',
                                        method: 'POST',
                                        success: function() { 
                                            that.render();
                                        } } );

        Backbone.Events.trigger('refresh:tasks', this.model.collection);
        //this.trigger('refresh:tasks');
    },
    
    markUnread :  function(e) {
        e.stopPropagation();
        var that = this;

        //TODO: Think about refactoring this and markRead into one function
        this.model.save({'id': this.model.get('id'), 'status':1}, {
                                        url: 'api/tasks.php?action=update',
                                        method: 'POST',
                                        success: function() { 
                                            that.render();
                                        } } );

        Backbone.Events.trigger('refresh:tasks', this.model.collection);
    }
});

