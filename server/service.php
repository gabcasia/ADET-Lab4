<?php
header("Content-Type: application/json");

$dataFile = "pokemon.json";
$pokemonList = json_decode(file_get_contents($dataFile), true);

function saveData($data, $file) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents("php://input"), true);

switch ($action) {

    // 🔹 CREATE Pokémon
    case "add":
        $name = $input['name'] ?? '';
        $type = $input['type'] ?? '';
        $level = $input['level'] ?? '';

        if (!$name || !$type || !$level) {
            echo json_encode(["status" => "error", "message" => "Missing parameters"]);
            exit;
        }

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

    // 🔹 READ Pokémon
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

    // 🔹 UPDATE Pokémon
    case "update":
        $id = $input['id'] ?? '';
        $level = $input['level'] ?? '';

        foreach ($pokemonList as &$p) {
            if ($p['id'] == $id) {
                $p['level'] = $level;
                saveData($pokemonList, $dataFile);
                echo json_encode(["status" => "success", "pokemon" => $p]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "Not found"]);
        break;

    // 🔹 DELETE Pokémon
    case "delete":
        $id = $_GET['id'] ?? '';

        foreach ($pokemonList as $index => $p) {
            if ($p['id'] == $id) {
                array_splice($pokemonList, $index, 1);
                saveData($pokemonList, $dataFile);
                echo json_encode(["status" => "success", "message" => "Deleted"]);
                exit;
            }
        }

        echo json_encode(["status" => "error", "message" => "Not found"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
}
?>