var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.collections.notes = Backbone.Collection.extend({
    
    url : "api/notes.php"

});;