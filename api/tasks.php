<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



function addTask ($con, $task)
{
    //1. Add task to database and retrieve ID
    //2. Retrieve all key/LastUpdated pairs
    //3. Send to client
    //4. Client works out which ones have been updated elsewhere and requests further information on these
    //5. Information is provided, client updates
    $sql = "INSERT INTO Tasks (dateAdded,dateUpdated,description,catId, dueDate) VALUES (NOW(),NOW(),?,?, '2013-04-07')";

    //type hinting
    /* @var $con mysqli */
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si",$task->description,$task->catId);
    $stmt->execute();
    
    //Retrieve the last Id
    //echo "Inserted ID: ".mysqli_insert_id($con);
    $taskId = mysqli_insert_id($con);
    
    //Use Id to select the last record entered
    $sql = "select t.taskId, t.description, t.catId ,c.description, t.dateAdded from Tasks t inner join Categories c on t.catId = c.catId WHERE taskId=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i",$taskId);
    $stmt->execute();
    $stmt->bind_result($taskId, $description, $catId,$catDesc, $dateAdded);
    
    while ($stmt->fetch()) 
    {
        $task->taskId = $taskId;
        $task->description = $description;
        $task->catId = $catId;
        $task->catDesc = $catDesc;
        $task->dateAdded = $dateAdded;
    }
    //var_dump($task);
    $stmt->close();
    return $task;

}

//using another method of type-hinting in parameters
function updateTask(mysqli $con, $task)
{
    $retTask = new Task();
    $sql = "UPDATE  Tasks SET dateUpdated=NOW(),description=?,catId=? WHERE taskId=?";


    $stmt = $con->prepare($sql);
    $stmt->bind_param("sii",$task->description,$task->catId,$task->taskId);
    $stmt->execute();
    
    //Retrieve the last Id
    //echo mysqli_insert_id($con);
    //$taskId = mysqli_insert_id($con);
    
    //Use Id to select the last record entered
    $sql = "select t.taskId, t.description, t.catId ,c.description, t.dateAdded from Tasks t inner join Categories c on t.catId = c.catId WHERE taskId=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i",$task->taskId);
    $stmt->execute();
    $stmt->bind_result($taskId, $description, $catId,$catDesc, $dateAdded);
    
    while ($stmt->fetch()) 
    {
        $retTask->taskId = $taskId;
        $retTask->description = $description;
        $retTask->catId = $catId;
        $retTask->catDesc = $catDesc;
        $retTask->dateAdded = $dateAdded;
    }
    //var_dump($row);
    $stmt->close();
    return $retTask;
}

function completedeleteTask($con, $taskId, $action) 
{
    $sql = "UPDATE Tasks SET status=? WHERE taskId=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii",$action,$taskId);
    $stmt->execute();
    $stmt->close();
    //echo "sql:".$sql;
    //$result = mysqli_query($con,$sql);
    //echo "result:".$result;
    return "{result:true}";
    
}



function retrieveAll($con)
{

    $sql = "select t.dateAdded, t.dateUpdated, t.taskId,t.description, t.catId, c.description, t.status from Tasks t inner join Categories c on t.catId = c.catId WHERE t.status <> 3 order by t.dateAdded";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($dateAdded,$dateUpdated, $taskId,$description,$catId,$catDesc,$status);
    //$result =  mysqli_query($con, $sql);
    $tasks = array();
    
    while ($stmt->fetch()) {
        $task = new Task();
        $task->dateAdded = $dateAdded;
        $task->lastUpdated = $dateUpdated;
        $task->taskId = $taskId;
        $task->description = $description;
        $task->catId = $catId;
        $task->catDesc = $catDesc;
        $task->status = $status;
        array_push($tasks,$task);
  
    }
    //var_dump($tasks);
    $stmt->close();
    return $tasks;
}

function getCategories($con) 
{
    $sql = "SELECT * FROM Categories where status<>0";
    //$result = mysqli_query($con,$sql);
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($catId,$catDesc,$status);
    $categories = array();
    
    while ($stmt->fetch()) {
        $category = new Category();
        $category->catId = $catId;
        $category->catDesc = $catDesc;
        $category->status = $status;
        array_push($categories,$category);
  
    }
    //var_dump($tasks);
    $stmt->close();
    return $categories;
}

function addCategory($con, $description) 
{
    $cat = new Category();
    //$sql = "INSERT INTO Categories (description) VALUES ('$description')";
    $sql = "INSERT INTO Categories (description) VALUES (?)";
    //echo $sql;
    //mysqli_query($con, $sql);
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s",$description);
    $stmt->execute();
   
    //Retrieve the last Id
    //echo mysqli_insert_id($con);
    $catId = mysqli_insert_id($con);
    
    //Use Id to select the last record entered
    //TODO: Consider changing this to Stored Proc or alternative extraction
    $sql = "select * from Categories WHERE catId=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i",$catId);
    $stmt->execute();
    
    $stmt->bind_result($catId,$description,$status);
    
    while($stmt->fetch())
    {
        $cat = new Category();
        $cat->catId = $catId;
        $cat->catDesc = $description;
        $cat->status = $status;
    }
    /*$result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $cat->catId = $row["catId"];
    $cat->catDesc = $row["description"];
    $cat->status = $row["status"];
*/
    //var_dump($row);
    $stmt->close();
    return $cat;
}


function getTask($con, $taskId) 
{
    $sql = "select dateUpdated from Tasks where taskId=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i",$taskId);
    $stmt->execute();
    $stmt->bind_result($dateUpdated);
    $stmt->fetch();
    $stmt->close();
    return $dateUpdated;
}


function syncUpdates($con, $dateUpdated, $jsonUpdates, $taskId)
{
    $retTasks = array(); //tasks to return to the app
    $recTasks = array(); //tasks received from the app
    $task = new Task();
    
    $recTasks = json_decode($jsonUpdates);
    //echo "date Updated $dateUpdated";
    
    //Loop through all items in $recTasks array

    foreach ($recTasks as &$tempTask)
    {
        //echo "In loop for task description".$tempTask->description;
        if (empty($task->$taskId))
        {
            //echo "Task ID is empty";
            $task = addTask($con,$tempTask);
            //var_dump($task);
        }
        else
        {
            //echo "Update for Task $tempTask->$taskId";
            //echo "GetTask output - ".getTask($con,$tempTask->$taskId);
            //UPDATE the value, if it has not been updated on the server since last sync
            if ($dateUpdated<GetTask($con,$tempTask->$taskId))
            {
               $task = updateTask($con,$tempTask); 
            }

        }
        //Added tasks to be sent back
        array_push($retTasks,$task);
    }
    
    //Now need to get other items that have changed., but TODO
    
    
    //Update database as required
        //For those records already update since $lastUpdate, don't make a change to database
        //Maybe build a conflict list to notify app user
    //Get all rows updated since $dateUpdated, including those just updated
    //Add to $retTasks and return
    /*$sql = "select t.dateAdded, t.dateUpdated, t.taskId,t.description, t.catId, c.description, t.status from Tasks t inner join Categories c on t.catId = c.catId WHERE t.status <> 3 order by t.dateAdded";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($dateAdded,$dateUpdated, $taskId,$description,$catId,$catDesc,$status);
    //$result =  mysqli_query($con, $sql);
    
    
    while ($stmt->fetch()) {
        $task = new Task();
        $task->dateAdded = $dateAdded;
        $task->lastUpdated = $dateUpdated;
        $task->taskId = $taskId;
        $task->description = $description;
        $task->catId = $catId;
        $task->catDesc = $catDesc;
        $task->status = $status;
        array_push($tasks,$task);
  
    }
    //var_dump($tasks);
    $stmt->close();*/
    //var_dump($retTasks);
    return $retTasks;
}


class Task {
    public $result = true;  //to be depreciated
    public $taskId;
    public $localTaskId;
    public $dateAdded;
    public $lastUpdated;
    public $description;
    public $owner;  //TODO
    public $catId;
    public $catDesc;
    public $dueDate;
    public $status;
}

class Category {
    public $catId;
    public $catDesc;
    public $status;
}
//We'll always need the database
//$con=mysqli_connect("localhost","taskUser", "HopperMyD0g", "Taskerv1");
//$con=new mysqli("localhost","taskUser", "HopperMyD0g", "Taskerv1");
$con=new mysqli("localhost","ablef014_task", "HopperMyD0g", "ablef014_TaskMan");
/* check connection */
if (mysqli_connect_errno($con))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
//


//echo "Connecting..";
//switch ($_POST('action')) {
switch ($_POST["action"]) {
    case "RetrieveAll":
        echo json_encode(retrieveAll($con));
        //echo'{"result":true,"taskId":108,"dateAdded":"2013-05-11 13:30:52","lastUpdated":null,"description":"See if I can batch thumbnail images for Jos mum","owner":null,"catId":8,"catDesc":"None","dueDate":null,"status":1},{"result":true,"taskId":118,"dateAdded":"2013-05-21 21:31:35","lastUpdated":null,"description":"Life insurance","owner":null,"catId":13,"catDesc":"New Zealand","dueDate":null,"status":1}';
        break;

    
    case "Add":
        $task = new Task();
        $task->description=$_POST["description"];
        $task->catId=$_POST["catId"];
        echo json_encode(addTask($con,$task));
        break;
    
    case "Update":
        $task = new Task();
        $task->taskId=$_POST["taskId"];
        $task->description=$_POST["description"];
        $task->catId=$_POST["catId"];
        //var_dump($task);
        echo json_encode(updateTask($con,$task));
        break;
    
    case "Done":
        echo json_encode(completedeleteTask($con,$_POST["taskId"],2));
        break;
    
    case "Delete":
        echo json_encode(completedeleteTask($con,$_POST["taskId"],3));
        break;
    
    case "GetCategories" :
        //dump_var($con);
        echo json_encode(getCategories($con));
        break;
    
    case "AddCategory" :
        echo json_encode (addCategory($con,$_POST["description"]));
        break;
    
    case "JSONSync" :
        //echo json_decode($_POST["syncdata"],$_POST["dateUpdated"],$_POST["jsonUpdates"]);
        /*echo "In JSONSync";
        echo $_POST["jsonUpdates"];
        $tasks = json_decode($_POST["jsonUpdates"]);
        echo $tasks[0]->task;
        echo "Number of items: ".count($tasks);*/
        echo json_encode(syncUpdates($con, $_POST["dateUpdated"], $_POST["jsonUpdates"]));
        
        
        
        
    
    /*
      case "Update":
        break;
    
    case "RetrievePair":
        break;
    
    
    
    case "RetrieveSpecific":
        break;
    
    
    
    case "Delete":
        break;*/
    //default :
}


//We'll always close the database
 mysqli_close($con);        

/*
$task = new Task();
if ($_POST["action"] == "Add") {
    $task->taskId = 2;  
}
else {
    $task->taskId = $_POST["taskId"];
}

echo json_encode($task);
*/
?>
