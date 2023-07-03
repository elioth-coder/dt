<?php
session_start();
require_once "../connection.php";

try {
    $conn->beginTransaction();
    $_POST['department_id'] = ($_POST['department_id']=='null') ? NULL : $_POST['department_id'];

    $stmt = $conn->prepare(
        "INSERT INTO task_history (task_id, remarks, user_id, department_id, status, tasker_type)
        VALUES (:task_id, :remarks, :user_id, :department_id, 'IN-PROGRESS', 'PERSONNEL')"
    );
    $stmt->execute([
        "task_id"       => $_POST['id'],
        "remarks"       => $_POST['remarks'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
    ]);

    $task_history_id = $conn->lastInsertId();

    $stmt = $conn->prepare("
        INSERT INTO task_user (task_history_id, user_id, department_id) 
        VALUES (:task_history_id, :receiver_id, :department_id)
    ");
    $stmt->execute([
        "task_history_id" => $task_history_id,
        "receiver_id"     => $_SESSION['user']['id'],
        "department_id"   => $_POST['department_id'],
    ]);

    $stmt = $conn->prepare(
        "UPDATE task SET status='IN-PROGRESS' WHERE id=:task_id"
    );
    $stmt->execute([
        "task_id" => $_POST['id'],
    ]);

    $conn->commit();

    $response = [ 
        "message" => "Task started successfully",
        "status"  => "success"
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    $conn->rollBack();

    $response = [ 
        "message" => "Error: " . $e->getMessage(),
        "status"  => "error",
        "post"    => $_POST,
        "session" => $_SESSION,
    ];
    echo json_encode($response);
}
$conn = null;