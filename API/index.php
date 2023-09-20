
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept'); 
header("Content-Type: application/json; charset=UTF-8");

session_start();

// Get Data
$data = json_decode(file_get_contents('php://input'), true);

// Check Data exist
if(!$data){
    $data['error'] = 'Data not found';
    echo json_encode($data);
    exit;
}
// Check Action
if(!isset($data['action'])){
    $data['error'] = 'Action not found';
    echo json_encode($data);
    exit;
}
// Set Action
$action = $data['action'];

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

switch($action){
    // Auth Actions
    case 'login':
        $user = $data['user'];
        $password = $data['password'];
        $user = CheckCredentials($user, $password);
        if(!$user){
            $data['error'] = 'Username or Password Invalid';
            echo json_encode($data);
            exit;
        }
        // Login Successful
        $_SESSION['user'] = $user;
        $_SESSION['loggedIn'] = true;
        $response['user'] = $user;
        $response['success'] = 'Login successful fowarding ...';
        echo json_encode($response);
    break;
    case 'logout':
        session_destroy();
        $response['success'] = 'Logout successful!';
        echo json_encode($response);
    break;
    // CRUD Actions
    // List ToDos
    case 'todos':
        $user = $data['user'];
        $password = $data['password'];
        $user = CheckCredentials($user, $password);
        if(!$user){
            ExitCredentialError();
        }
        $todos = GetUserTodos($user['id']);

        echo json_encode($todos);
    break;
    // Add New ToDos
    case 'add-todo':
        // Data Check
        if(!isset($data['todo'])){
            $response['error'] = 'Id missing';
            echo json_encode($response);
            exit;
        }
        $todo = $data['todo'];

        $user = $data['user'];
        $password = $data['password'];
        $user = CheckCredentials($user, $password);
        if(!$user){
            ExitCredentialError();
        }
        $response = [
            'todo' => $todo,
            'user' => $user['id'],
            'done' => 0,
        ];

        $query = $db->prepare("INSERT INTO todos SET 
        todo = :todo,
        user_id = :user,
        done = :done");
        $insert = $query->execute($response);
        if($insert){
            $response['id'] = $db->lastInsertId();
        }
        else{
            $response['error'] = 'Error in inserting data';
        }
        echo json_encode($response);
    break;
    // Change Done ToDos
    case 'done-todo':
        // Data Check
        $id = $data['id'];
        $done = $data['done'];
        if(!$id){
            $response['error'] = 'Id missing';
            echo json_encode($response);
            exit;
        }
        if(!is_numeric($id)){
            $response['error'] = 'Id invalid';
            echo json_encode($response);
            exit;
        }
        // Credentials Check
        $user = $data['user'];
        $password = $data['password'];
        $user = CheckCredentials($user, $password);
        if(!$user){
            ExitCredentialError();
        }
        // Update
        $query = $db->prepare('UPDATE todos SET done = :done WHERE id = :id');
        $update = $query->execute([
            'id' => $id,
            'done' => $done
        ]);
        if($update){
            $response = GetUserTodos($user['id']);
        }
        else{
            $response['error'] = 'Error in updating data' . $done;
        }
        echo json_encode($response);
    break;
    // Delete ToDos
    case 'delete-todo':
        // Data Check
        $id = $data['id'];
        if(!$id){
            $response['error'] = 'Id missing';
            echo json_encode($response);
            exit;
        }
        if(!is_numeric($id)){
            $response['error'] = 'Id invalid';
            echo json_encode($response);
            exit;
        }
        // Credentials Check
        $user = $data['user'];
        $password = $data['password'];
        $user = CheckCredentials($user, $password);
        if(!$user){
            ExitCredentialError();
        }

        $query = $db->prepare('DELETE FROM todos WHERE id = :id AND user_id = :userId');
        $delete = $query->execute([
            'id' => $id,
            'userId' => $user['id']
        ]);

        if($delete){
            $response['deleted'] = 'success';
            echo json_encode($response);
            return;
        }
        else{
            $response['error'] = 'Error in deleting data';
            echo json_encode($response);
            return;
        }
    break;
}
// CUSTOM FUNCTIONS
function CheckCredentials($u, $p){
    // Check Password is md5 Hash
    if(strlen($p) != 32){
        return null;
    }
    global $db;
    $query = $db->prepare("SELECT * FROM users WHERE name = :user AND password = :pass");
    $query->execute([
        'user' => $u,
        'pass' => $p

    ]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    return $user;
}
function ExitCredentialError(){
    $response['error'] = 'Credentials Failed';
    echo json_encode($response);
    exit;
}
function GetUserTodos($userId){
    global $db;
    $query = $db->prepare("SELECT * FROM todos WHERE user_id = :userId");
        $query->execute([
            'userId' => $userId
        ]);
    $todos = $query->fetchAll(PDO::FETCH_ASSOC);
    return $todos;
}
?>