<?php

function retrieveAll()
{
    $con=new mysqli("localhost","ablef014_task", "HopperMyD0g", "ablef014_TaskMan");
    /* check connection */
    if (mysqli_connect_errno($con))
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

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
    //We'll always close the database
    mysqli_close($con);
    return $tasks;
}

function getAllLinks()
{
    $con=new mysqli("localhost","ablef014_dbuser", "HopperMyD0g", "ablef014_dashboard");
    /* check connection */
    if (mysqli_connect_errno($con))
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    $sql = "select linkId, url, description from links";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($linkId, $url, $description);
    //$result =  mysqli_query($con, $sql);
    $links = array();
    
    while ($stmt->fetch()) {
        $link = new Link();
        $link->linkId = $linkId;
        $link->url = $url;
        $link->description = $description;
        array_push($links,$link);
  
    }
    //var_dump($tasks);
    $stmt->close();
    
    mysqli_close($con);
    return $links;
}

function addLink($url,$description)
{
    $con=new mysqli("localhost","ablef014_dbuser", "HopperMyD0g", "ablef014_dashboard");
    /* check connection */
    if (mysqli_connect_errno($con))
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    $sql = "insert into links (url, description, status) values(?,?,1)";
    $stmt->bind_param("ss",$url,$description);
    $stmt->execute();
    $stmt->close();
    
    //Retrieve the last Id
    $linkId = mysqli_insert_id($con);
    
    //Build link object for serialisation into JSON
    $link = new Link();
    $link->linkId = $linkId;
    $link->url = $url;
    $link->description = $description;
    
    
    //var_dump($task);
    $stmt->close();
    return $link;

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

class Link {
    public $linkId;
    public $url;
    public $description;
}



//echo "Connecting..";
//switch ($_POST('action')) {
switch ($_POST["action"]) {
    /*case "RetrieveAll":
        echo json_encode(retrieveAll());
        break;
*/
    
    case "GetAllLinks":
        echo json_encode(getAllLinks());
        break;
    
    case "AddLink":
        echo json_encode(addLink($_POST["url"],$_POST["description"]));
        break;
    
   /* case "Update":
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
        echo "Number of items: ".count($tasks);
        echo json_encode(syncUpdates($con, $_POST["dateUpdated"], $_POST["jsonUpdates"]));*/
        
        
        

}


        

?>
