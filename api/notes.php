<?php

require "common/magicquotes.php";
require "common/dbconnection.php";

/**
 * 
 * @param mysqli $con
 * @param type $note
 * @return type
 */
function addNote (mysqli $con, $note)
{
    //1. Add task to database and retrieve ID
    //2. Retrieve all key/LastUpdated pairs
    //3. Send to client
    //4. Client works out which ones have been updated elsewhere and requests further information on these
    //5. Information is provided, client updates
    $sql = "INSERT INTO notes (title, content, lastUpdated) VALUES (?,?,NOW())";

    
    /* @var $con mysqli */
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss",$note->title,$note->content);
    $stmt->execute();
    
    //Retrieve the last Id
    //echo "Inserted ID: ".mysqli_insert_id($con);
    $noteId = mysqli_insert_id($con);
    
    $note->id = $noteId;
    //var_dump($task);
    $stmt->close();
    return $note;

}

/**
 * 
 * @param mysqli $con
 * @param type $task
 * @return type
 */
function updateNote(mysqli $con, $note)
{
    $sql = "UPDATE notes SET lastUpdated=NOW(),title=?, content=?, status=? WHERE noteId=?";


    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssii",$note->title,$note->content, $note->status,$note->id);
    $stmt->execute();
    
    $stmt->close();
    return ($note);
}

/**
 * 
 * @param type $con
 * @return array
 */

function retrieveAll($con)
{
    $notes = array();
    
    $sql = "select n.noteId, n.title, n.content, n.lastUpdated, n.status from notes n WHERE n.status <> 3 order by n.lastUpdated";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($id, $title,$content, $lastUpdated,$status);

    $tasks = array();
    
    while ($stmt->fetch()) {
        $note = new Note();
        $note->id = $id;
        $note->title = $title;
        $note->content = $content;
        $note->lastUpdate = $lastUpdated;
        $note->status = $status;

        array_push($notes,$note);
  
    }
    
    $stmt->close();
    return $notes;
}

/**
 * Note object type
 */
class Note {
    public $id;
    public $title;
    public $content;
    public $lastUpdated;
    public $status;
}

//-----------------------------------------------------

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : 'none';


switch ($method) {
    case "GET":
        //Get all tasks
        echo json_encode(retrieveAll($con));
        break;
    case "POST":
        
        switch ($action) {
            case 'update':
                $task = json_decode(file_get_contents("php://input"));
                echo json_encode(updateNote($con, $task));
                break;
            case 'add':
                $task = json_decode(file_get_contents("php://input"));
                echo json_encode(addNote($con, $task));
                break;
            case 'delete':
                //Delete is handled by status update
                
            default:
                $task = json_decode(file_get_contents("php://input"));
                echo json_encode(addNote($con, $task));
        }
        
        break;
    
//  PUT an DELETE not supported on my hosting provider
//    
//    case "PUT":
//        //$task = json_decode(file_get_contents("php://input"));
//        //echo json_encode(updateTask($con, $task));
//        break;
//    case "DELETE":
//        break;
        
}


