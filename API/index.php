
<?php
header("Access-Control-Allow-Origin: *");
$dbHost = 'localhost';
$dbName = 'todo';
$dbUser = 'root';
$dbPass = 'root';
try{
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName;", $dbUser, $dbPass);
}
catch(PDOExeception $e){
    die($e->getMessage());
}

$action = $_POST['action'];

switch($action){
    // List ToDos
    case 'todos':
        $query = $db->query('SELECT * FROM todos')->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($query);
    break;
    // Add New ToDos
    case 'add-todo':
        $todo = $_POST['todo'];
        $data = [
            'todo' => $todo,
            'user' => 1,
            'done' => 0,
        ];

        $query = $db->prepare("INSERT INTO todos SET 
        todo = :todo,
        user_id = :user,
        done = :done");
        $insert = $query->execute($data);
        if($insert){
            $data['id'] = $db->lastInsertId();
            echo json_encode($data);
        }
        else{
            $data['error'] = 'Error in inserting data';
        }
    break;
    // Change Done ToDos
    case 'done-todo':
        $id = $_POST['id'];
        $done = $_POST['done'];
        $data = [];
        if(!$id){
            $data['error'] = 'Id missing';
            echo json_encode($data);
            return;
        }
        if(!is_numeric($id)){
            $data['error'] = 'Id invalid';
            echo json_encode($data);
            return;
        }

        
        $query = $db->prepare('UPDATE todos SET done = :done WHERE id = :id');
        $update = $query->execute([
            'id' => $id,
            'done' => $done
        ]);
        if($update){
            $query = $db->query('SELECT * FROM todos')->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($query);
            return;
        }
        else{
            $data['error'] = 'Error in updating data' . $done;
            echo json_encode($data);
            return;
        }
    break;
    // Delete ToDos
    case 'delete-todo':
        $id = $_POST['id'];
        $data = [];
        if(!$id){
            $data['error'] = 'Id missing';
            echo json_encode($data);
            return;
        }
        if(!is_numeric($id)){
            $data['error'] = 'Id invalid';
            echo json_encode($data);
            return;
        }

        $delete = $db->exec('DELETE FROM todos WHERE id = "'. $id . '"');
        if($delete){
            $data['deleted'] = 'success';
            echo json_encode($data);
            return;
        }
        else{
            $data['error'] = 'Error in deleting data';
            echo json_encode($data);
            return;
        }
    break;
}
?>