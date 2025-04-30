<?php
require 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query("SELECT * FROM roles");
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO roles (roleName, permissions) VALUES (?, ?)");
        $stmt->execute([$data['roleName'], $data['permissions']]);
        echo json_encode(['message' => 'Role added']);
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        $stmt = $pdo->prepare("UPDATE roles SET roleName=?, permissions=? WHERE id=?");
        $stmt->execute([$data['roleName'], $data['permissions'], $data['id']]);
        echo json_encode(['message' => 'Role updated']);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['message' => 'Role deleted']);
        break;
}
?>
