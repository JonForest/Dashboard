/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.app.tasks = function () {
    var tasksView;
    
    function init() 
    {
        var tasks = new ablefutures.collections.tasks();
        tasks.fetch({
            success : function (collection, response, options) {
                tasksView = new ablefutures.views.tasks({collection:collection, el : '#tasks'});
                renderTasks(collection);
            },
            error : function (collection, response, options) {
                console.log('Error retrieving links - ' + response);
            }
        });
        
        
        
        //events
        //var taskView = new ablefutures.views.task();
        //Backbone.Events.on("refresh:tasks",this.refreshTasks);
        //TODO: Make events work
        Backbone.Events.on("refresh:tasks", renderTasks, this); // listen out for this event
    }
    

    function renderTasks() 
    {

        tasksView.render();
    }

    
    return {
        init: init
    }
    
    
        
}();