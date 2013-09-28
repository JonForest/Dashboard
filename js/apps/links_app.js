/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.app.links = function () {
    
    
    function init() 
    {
        var links = new ablefutures.collections.links();
        links.fetch({
            success : function (collection, response, options) {
                renderLinks(collection);
            },
            error : function (collection, response, options) {
                console.log('Error retrieving links - ' + response)
            }
        });
    }
    

    function renderLinks(collection) 
    {
        $('#linkscontent').empty();
        var linksView = new ablefutures.views.links({collection:collection});
        
        $('#linkscontent').append(linksView.render().$el);
        
     
    }
    
    return {
        init: init
    }
    
    
        
}();