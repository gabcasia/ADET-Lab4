<?php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$dataFile = "users.json";

// Load users
$users = json_decode(file_get_contents($dataFile), true);

// Helper: save users
function saveUsers($users, $file) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

// Get input
$input = json_decode(file_get_contents("php://input"), true);

// ROUTING
$action = $_GET['action'] ?? '';

switch ($action) {

    // 🔹 REGISTER USER
    case "register":
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if (!$username || !$password) {
            echo json_encode(["status" => "error", "message" => "Missing parameters"]);
            exit;
        }

        foreach ($users as $user) {
            if ($user['username'] === $username) {
                echo json_encode(["status" => "error", "message" => "User already exists"]);
                exit;
            }
        }

        $newUser = [
            "id" => count($users) + 1,
            "username" => $username,
            "password" => $password
        ];

        $users[] = $newUser;
        saveUsers($users, $dataFile);

        echo json_encode(["status" => "success", "user" => $newUser]);
        break;

    // 🔹 LOGIN
    case "login":
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        foreach ($users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                echo json_encode(["status" => "success", "message" => "Login successful"]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
        break;

    // 🔹 GET USER
    case "get":
        $id = $_GET['id'] ?? '';

        foreach ($users as $user) {
            if ($user['id'] == $id) {
                echo json_encode(["status" => "success", "user" => $user]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "User not found"]);
        break;

    // 🔹 UPDATE USER
    case "update":
        $id = $input['id'] ?? '';
        $newUsername = $input['username'] ?? '';

        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                $user['username'] = $newUsername;
                saveUsers($users, $dataFile);
                echo json_encode(["status" => "success", "user" => $user]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "User not found"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
}
?>