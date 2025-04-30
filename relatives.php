<?php
require 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM relatives WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query("SELECT * FROM relatives");
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO relatives (name, relation, birthdate, userId) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['relation'], $data['birthdate'], $data['userId']]);
        echo json_encode(['message' => 'Relative added']);
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        $stmt = $pdo->prepare("UPDATE relatives SET name=?, relation=?, birthdate=?, userId=? WHERE id=?");
        $stmt->execute([$data['name'], $data['relation'], $data['birthdate'], $data['userId'], $data['id']]);
        echo json_encode(['message' => 'Relative updated']);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $stmt = $pdo->prepare("DELETE FROM relatives WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['message' => 'Relative deleted']);
        break;
}
?>
