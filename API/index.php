
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();

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

function LoginRequired(){
    $data['error'] = 'Login Required!';
    echo json_encode($data);
}

$action = $_POST['action'];

switch($action){
    // Auth Actions
    case 'login':
        $user = $_POST['user'];
        $password = $_POST['password'];

        if($user == 'demo' && $password == 'demo'){
            $_SESSION['user_id'] = 1;
            $data['success'] = 'Login successful fowarding in 2 seconds ... / ' . $_SESSION['user_id'];
            echo json_encode($data);
        }
        else{
            $data['error'] = 'Username or Password Invalid';
            echo json_encode($data);
        }
    break;
    case 'logout':
        session_destroy();
        $data['success'] = 'Logout successful!';
        echo json_encode($data);
    break;
    // CRUD Actions
    // List ToDos
    case 'todos':
        if(!isset($_SESSION['user_id'])){
            return LoginRequired();
        }
        $query = $db->query('SELECT * FROM todos')->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($query);
    break;
    // Add New ToDos
    case 'add-todo':
        if(!$_SESSION['user_id']){
            return LoginRequired();
        }
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
            echo json_encode($data);
        }
    break;
    // Change Done ToDos
    case 'done-todo':
        if(!$_SESSION['user_id']){
            return LoginRequired();
        }
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
        if(!$_SESSION['user_id']){
            return LoginRequired();
        }
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