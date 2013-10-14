<!DOCTYPE html>
<html>
    <head>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/default.css" rel="stylesheet">
        <link href="css/datepicker.css" rel="stylesheet">
    </head>
    <body>
        <div class="page-header">
                <h1>Jon Hollingsworth <small>personalised Dashboard</small></h1>
            </div>    
        
        <div class="row">
            <div id="linkscontent" class="col-md-4">
            </div>
            
            <div class="col-md-1"></div>
            
            <div id="tasks" class="col-md-6">
                <a href="#addTask" role="button" class="btn btn-primary" data-toggle="modal">Add Task</a>
                <a href="#" id="cleanTasks" role="button" class="btn btn-primary" data-toggle="modal">Remove completed</a>
                 <table class="table table-striped table-hover">
                <thead>
                     <tr>
                         <th>Description</th>
                         <th>Due Date</th>
                         <th></th>
                     </tr>
                </thead>
                <tbody id="taskscontent">
                </tbody>
                </table>
                
                <!-- Modal -->
                <div class="modal fade" id="addTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Task</h4>
                      </div>
                      <div class="modal-body">
                          
                          <form class="form">
                              <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" id="description" class="form-control">
                                <label for="dueDate">Due Date</label>
                                <input type="text" id="dueDate" placeholder="dd/mm/yyyy" class="form-control datepicker" data-date-format="dd/mm/yy">
                              </div>
                                                          
                          </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="close" data-dismiss="modal">Close</button>
                        <button type="button" id="saveAndNext" class="btn btn-primary">Save and Next</button>
                        <button type="button" id="saveAndClose"class="btn btn-primary">Save and Close</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                
            </div>
            
            <div class="col-md-1"></div>
            
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
            <td <% if (model.get('status') == 2) { %>class="done"<% } %>><%=model.get('description')%></td>
            <td <% if (model.get('status') == 2) { %>class="done"<% } %>><%=model.get('dueDate')%></td>
            <td><%if (model.get('status')==2) {%>
            <span class="glyphicon glyphicon-check">
            <%} else {%>
            <span class="glyphicon glyphicon-unchecked">
            <% } %>
            </span></td>
        </script>
        
        <script type="text/template" id="task-template">
            This is a test
        </script>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js"></script>  
        <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="js/assets/bootbox.min.js"></script>
        <script src="js/assets/bootstrap-datepicker.js"></script>
        
        <script src="js/apps/links_app.js"></script>
        <script src="js/models/link_model.js"></script>
        <script src="js/collections/links_collection.js"></script>
        <script src="js/views/links_view.js"></script>
        <script src="js/views/link_view.js"></script>
        
        <script src="js/apps/tasks_app.js"></script>
        <script src="js/models/task_model.js"></script>
        <script src="js/collections/tasks_collection.js"></script>
        <script src="js/views/tasks_view.js"></script>
        <script src="js/views/task_view.js"></script>
            
        <script src="js/spin.min.js"></script>
        
        <script language="javascript">
            ablefutures.app.links.init();
            ablefutures.app.tasks.init();
            
            
             
        </script>
        

    </body>
</html>
