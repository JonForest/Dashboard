/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.views.notes = Backbone.View.extend({
    template : $('#notes-template'),
   
    events : {
        'click a.tablink' : 'editNote',
        'click button#saveNote' : 'saveNote',
        'click button#deleteNote' :  'deleteNote'
    },
    
    initialize : function () {
        console.log('Collection fom view' + JSON.stringify(this.collection));
    },
    
    render : function () 
    {
        that = this;       
        
        //Conscious use of .html - we want to empty() then append()
        this.$el.html(_.template(this.template.html(), {}));
        
        _.each(this.collection.models, function(model) {
            if (model.get('status') !== 3) {
                var noteId = 'note' + model.get('id');

                $title = $('<li><a data-toggle="tab"></a></li>');
                $title.find('a').attr('href','#' + noteId);
                $title.find('a').attr('class', 'tablink');
                $title.find('a').attr('id', model.get('id') );
                $title.find('a').html(model.get('title'));

                $content = $('<div class="tab-pane content"></div>');
                $content.attr('id', noteId);
                $content.html(model.get('content'));


                that.$el.find('.nav-stacked').append($title);
                that.$el.find('.tab-content').append($content);
            }
        });
        
        if (this.$el.find('.tab-content').length > 1) {
            this.$el.find('.tab-content').eq(1).addClass('active');
        }

        
        return this;
        
        
    },
            
    deleteNote: function(e) {
        e.stopPropagation();
        var id = $(e.target).attr('data-id');
        
        data = {id : id, status: 3};
        
        this.collection.get(id).save(data,  {
                                        url: 'api/notes.php?action=update',
                                        method: 'POST',
                                        success: function() { 
                                            bootbox.alert('Deleted');
                                            that.render();
                                        } } ); 
        
    },
    
    editNote: function(e) {
        //Don't block propagation - we want the Backbone events to fire
        var id = $(e.target).attr('id');
        
        //Clean the contenteditable conditions across any tab
        this.$el.find('.tab-pane').attr('contenteditable', 'false')
                                    .removeClass('editting');
        this.$el.find('a').removeClass('editting');
        
        
        //Link
        $(e.target).attr('contentEditable','true')
                    .addClass('editting'); //ensure you can edit
        
        //Content Div
        this.$el.find($(e.target).attr('href')).attr('contenteditable','true')
                                                .addClass('editting');
        
        //Save button
        this.$el.find('#saveNote').removeClass('hidden')
                                  .attr('data-id', id);
        //Delete button
        if (id !== 'newNote') {
            this.$el.find('#deleteNote').removeClass('hidden')
                                        .attr('data-id', id);
        }
         
    },
    
    saveNote: function (e) {
        e.stopPropagation();
        var id = $(e.target).attr('data-id');
        
        //Link
        title= $('#' + id).removeClass('editting').html();
        
        //Content Div
        content = this.$el.find($('#' + id).removeClass('editting').attr('href')).html();
        var data = {id: id, title : title, content : content, status : 1};
        
        
        if (id !== 'newNote') {
           //Dont't want this, need to model.save
            this.collection.get(id).save(data,  {
                                        url: 'api/notes.php?action=update',
                                        method: 'POST',
                                        success: function() { 
                                            bootbox.alert('Saved');
                                            that.render();
                                        } } );  
        } else {
            this.collection.create ( data, {
                                     url: 'api/notes.php?action=addNote' ,
                                     method: 'POST',
                                     success: function() {
                                        bootbox.alert('Added');
                                        that.render();
                                     } } );
        }
        
        
        //Clean the contenteditable conditions across any tab
        this.$el.find('.tab-pane').attr('contenteditable', 'false');
        
        //Make the button hidden again
        this.$el.find('#saveNote').addClass('hidden');
        this.$el.find('#deleteNote').addClass('hidden');
    
    }
});

