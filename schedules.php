<?php
require 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM schedules WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query("SELECT * FROM schedules");
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO schedules (userId, date, startTime, endTime, task) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['userId'], $data['date'], $data['startTime'], $data['endTime'], $data['task']]);
        echo json_encode(['message' => 'Schedule added']);
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        $stmt = $pdo->prepare("UPDATE schedules SET userId=?, date=?, startTime=?, endTime=?, task=? WHERE id=?");
        $stmt->execute([$data['userId'], $data['date'], $data['startTime'], $data['endTime'], $data['task'], $data['id']]);
        echo json_encode(['message' => 'Schedule updated']);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['message' => 'Schedule deleted']);
        break;
}
?>
