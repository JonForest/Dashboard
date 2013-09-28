/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.views.link = Backbone.View.extend({
    className: 'link',
    events : {
        'click button' : 'remove'
    },
    
    initialize : function () {
        console.log('Collection fom view' + JSON.stringify(this.model));
    },
    
    render : function () {
        this.$el.empty();
        var compiled = _.template($('#item-template').html(), 
                        {model : this.model});
                        
        this.$el.append(compiled);
        
        return this;
        
    },
    
    remove : function(e) {
        this.model.destroy({'url': 'api/deleteLinks.php?id=' + this.model.get('id'),
                        'success' :  function() { alert('deleted') } });
    } 
});

