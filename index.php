<!DOCTYPE html>
<html>
    <head>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/default.css" rel="stylesheet">
    </head>
    <body>
        <div class="page-header">
                <h1>Jon Hollingsworth <small>personalised Dashboard</small></h1>
            </div>    
        
        <div class="row">
            <div id="linkscontent" class="col-md-4">
            </div>
            
            <div id="tasks" class="col-md-8">
                <div id="taskscontent">
                    
                </div>
            </div>
            
        </div>    
        
        
        <!-- templates -->
        <script type="text/template" id="item-template">
            <a href="<%=model.get('url')%>"><%=model.get('description')%></a>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
        </script>
        
        <script type="text/template" id="items-template">
            <button class="btn btn-add">Add</button>
        </script>

        <script type="text/template" id="task-template">
            <tr>
                <td><%=get.model('description')%></td>
                <td><%=get.model
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
        </script>
        
        <script type="text/template" id="tasks-template">
            <table>
                <tr>
                    <th>Task</th>
                    <th>Date</th>
                    <th></th>
                </tr>

        </script>
        
        <script type="text/template" id="task-template">
            This is a test
        </script>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js"></script>  
        <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js"></script>
        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
        <script src="js/assets/bootbox.min.js"></script>
        
        <script src="js/apps/links_app.js"></script>
        <script src="js/models/link_model.js"></script>
        <script src="js/collections/links_collection.js"></script>
        <script src="js/views/links_view.js"></script>
        <script src="js/views/link_view.js"></script>
            
        <script src="js/spin.min.js"></script>
        
        <script language="javascript">
            ablefutures.app.links.init();
            

        </script>
        

    </body>
</html>
