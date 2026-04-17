<?php
header("Content-Type: application/json");

$dataFile = "pokemon.json";
$usersFile = "users.json";

$pokemonList = json_decode(file_get_contents($dataFile), true);
if (!is_array($pokemonList)) $pokemonList = [];

function saveData($data, $file) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents("php://input"), true);

// ================= USERS =================

switch ($action) {

    case "register":
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    $usersFile = __DIR__ . "/users.json";

    if (!file_exists($usersFile)) {
        file_put_contents($usersFile, "[]");
    }

    $users = json_decode(file_get_contents($usersFile), true);

    if (!is_array($users)) $users = [];

    // check duplicate
    foreach ($users as $u) {
        if ($u['username'] === $username) {
            echo json_encode([
                "status" => "error",
                "message" => "User already exists"
            ]);
            exit;
        }
    }

    $newUser = [
        "id" => count($users) + 1,
        "username" => $username,
        "password" => $password
    ];

    $users[] = $newUser;

    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

    echo json_encode([
        "status" => "success",
        "message" => "Registered successfully"
    ]);
    break;

    case "login":
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        $users = json_decode(file_get_contents($usersFile), true);

        foreach ($users as $u) {
            if ($u['username'] === $username && $u['password'] === $password) {
                echo json_encode(["status" => "success", "message" => "Login successful"]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
        break;

    // ================= POKEMON =================

    case "add":
        $name = $input['name'] ?? '';
        $type = $input['type'] ?? '';
        $level = $input['level'] ?? '';

        $newPokemon = [
            "id" => count($pokemonList) + 1,
            "name" => $name,
            "type" => $type,
            "level" => $level
        ];

        $pokemonList[] = $newPokemon;
        saveData($pokemonList, $dataFile);

        echo json_encode(["status" => "success", "pokemon" => $newPokemon]);
        break;

    case "get":
        $id = $_GET['id'] ?? '';

        foreach ($pokemonList as $p) {
            if ($p['id'] == $id) {
                echo json_encode(["status" => "success", "pokemon" => $p]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "Not found"]);
        break;

    case "update":
        $id = $input['id'] ?? '';
        $name = $input['name'] ?? '';
        $type = $input['type'] ?? '';
        $level = $input['level'] ?? '';

        foreach ($pokemonList as &$p) {
            if ($p['id'] == $id) {
                if ($name) $p['name'] = $name;
                if ($type) $p['type'] = $type;
                if ($level) $p['level'] = $level;

                saveData($pokemonList, $dataFile);

                echo json_encode(["status" => "success", "pokemon" => $p]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "Not found"]);
        break;

    case "delete":
        $id = $_GET['id'] ?? '';

        foreach ($pokemonList as $i => $p) {
            if ($p['id'] == $id) {
                array_splice($pokemonList, $i, 1);
                saveData($pokemonList, $dataFile);
                echo json_encode(["status" => "success"]);
                exit;
            }
        }

        echo json_encode(["status" => "error"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
}
?>