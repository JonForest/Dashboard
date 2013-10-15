/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.views.tasks = Backbone.View.extend({

                        
    events : {
        'click #saveAndClose' : 'saveAndClose',
        'click #saveAndNext' : 'saveAndNext',
        'click #close' : 'cleanAndClose',
        'click #cleanTasks' : 'cleanTasks',
        'click .datepicker' : 'datePicker'
        
    },
    
    initialize : function () {
        console.log('Collection fom view' + JSON.stringify(this.collection));
    },
    
    render : function () {
        this.$el.find('tbody').empty();
        var collection = _.sortBy(this.collection.models, function(model) {
            return model.get('status');
        });
        
        var els = new Array();

        _.each(collection, function(model) {
            if (model.get('status') != 3) {
                var task = new ablefutures.views.task({model:model}); 
                els.push(task.render().$el);
                task.on('refresh:tasks', this.render);
            }
        });

        this.$el.find('tbody').append(els);
        
        //return this.$el;
        
        
    },

    
    /**
     * @param collection
     */
    sort : function (collection) {
        var sortedCollection;
        
        //Sort the collection
        
        //Split into read and unread
        
        //read to be sorted on Due Date
        
        //unread to be sorted on Last Updated
        
        //Join the the two together
        
        //Return the collection
        return sortedCollection;
    },
            
    saveAndClose : function(e) {
        e.stopPropagation();
        this.saveAndSend(e); 
        this.cleanAndClose(e);
         
    },
            
    saveAndNext :function(e) {
        e.stopPropagation();
        this.saveAndSend(e)
    },
            
    saveAndSend : function(e) {
        var description = $(e.target).parents('.modal').find('#description').val();
        var dueDate = $(e.target).parents('.modal').find('#dueDate').val();
        var data = {description : description, dueDate : dueDate, status : 1};
        that = this;
        
        //Dont't want this, need to model.save
        this.collection.create(data, {success : function() {
                $(e.target).parents('.modal').find('input').val('');
                //that.render();      
        } });
    },        
    
    cleanAndClose : function(e) {
        e.stopPropagation();
        $(e.target).parents('.modal').find('input').val('');
        $(e.target).parents('.modal').modal('hide');
        Backbone.Events.trigger('refresh:tasks');
    },
            
    cleanTasks : function(e) {
        //loop through all task and set status top 3
        _.each(this.collection.models, function(model) {
            if (model.get('status') === 2) {
                model.set('status',3);
                model.save();
            }
        });
        Backbone.Events.trigger('refresh:tasks');
    },
    
    datePicker : function(e) {
        e.stopPropagation();
        $(e.target).datepicker();
        //TODO: work out why this doesn't fire on the first click into the box
        //Or rather, may fire, but does not display
    }
   
});

