/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.collections.tasks = Backbone.Collection.extend({
    model: ablefutures.models.task,
    
    url : "api/tasks.php"
    
    
});;