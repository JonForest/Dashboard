/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.app.notes = function () {
    var notesView;
    
    function init() 
    {
        var notes = new ablefutures.collections.notes();
        notes.fetch({
            success : function (collection, response, options) {
                notesView = new ablefutures.views.notes({collection:collection, el : '#notes'});
                renderNotes();
            },
            error : function (collection, response, options) {
                console.log('Error retrieving links - ' + response);
            }
        });
        
        
        
        //events
        //var taskView = new ablefutures.views.task();
        //Backbone.Events.on("refresh:tasks",this.refreshTasks);
        //TODO: Make events work
        Backbone.Events.on("refresh:notes", renderNotes, this); // listen out for this event
    }
    
    function renderNotes() 
    {
        
        notesView.render();
    }

    
    return {
        init: init
    }
    
    
        
}();