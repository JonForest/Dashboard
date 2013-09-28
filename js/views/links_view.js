/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.views.links = Backbone.View.extend({
    template : $('#items-template'),
                        
    events : {
        'click button.btn-add' : 'addModel'
    },
    
    initialize : function () {
        console.log('Collection fom view' + JSON.stringify(this.collection));
    },
    
    render : function () {
        this.$el.empty();
        var els = new Array();
        console.log('Rendering');
        //TODO: compile up the template and add to array, maybe?
        _.each(this.collection.models, function(model) {
            var link = new ablefutures.views.link({model:model}); 
            els.push(link.render().$el);
        })
        this.$el.append(els);
        this.$el.append(_.template(this.template.html(),{}));
        
        return this;
        
        
    },
    
    addModel : function() {
        //fire modal box
        that = this;
        bootbox.prompt('Enter URL for new link', function (urlTxt) {
            console.log(urlTxt);
            if (urlTxt) {
                that.collection.create({url: urlTxt,
                                        description: urlTxt,
                                        status:1},
                                        {
                                        url: 'api/saveLinks.php',
                                        method: 'POST',
                                        success: function() { 
                                            bootbox.alert('saved'); 
                                            that.render();
                                        },
                                        error: function() { bootbox.alert('failed to save'); }
                })
            } 
      });
        
        
    }
});

