<?php
session_start();
require_once "../connection.php";

try {
    $conn->beginTransaction();
    $_POST['department_id'] = ($_POST['department_id']=='null') ? NULL : $_POST['department_id'];

    $stmt = $conn->prepare(
        "SELECT tasker_type FROM task_history 
        WHERE id=(SELECT id FROM task_history WHERE task_id=:task_id ORDER BY id ASC LIMIT 1)"
    );
    $stmt->execute(["task_id" => $_POST['id']]);
    $tasker_type = ($stmt->fetch(PDO::FETCH_ASSOC))['tasker_type'];

    $stmt = $conn->prepare(
        "INSERT INTO task_history (task_id, remarks, user_id, department_id, status, tasker_type)
        VALUES (:task_id, :remarks, :user_id, :department_id, 'RE-ASSIGNED', :tasker_type)"
    );
    $stmt->execute([
        "task_id"       => $_POST['id'],
        "remarks"       => $_POST['remarks'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
        "tasker_type"   => $tasker_type,
    ]);

    $task_history_id = $conn->lastInsertId();

    if($tasker_type == 'DEPARTMENT') {
        $stmt = $conn->prepare(
            "SELECT * FROM task_department 
            WHERE task_history_id=(SELECT id FROM task_history WHERE task_id=:task_id ORDER BY id ASC LIMIT 1)"
        );
        $stmt->execute(["task_id" => $_POST['id']]);
        $tasker = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $conn->prepare("
            INSERT INTO task_department (task_history_id, department_id) 
            VALUES (:task_history_id, :department_id)
        ");

        $stmt->execute([
            "task_history_id" => $task_history_id,
            "department_id"   => $tasker['department_id'],
        ]);
    } else {
        $stmt = $conn->prepare(
            "SELECT * FROM task_user 
            WHERE task_history_id=(SELECT id FROM task_history WHERE task_id=:task_id ORDER BY id ASC LIMIT 1)"
        );
        $stmt->execute(["task_id" => $_POST['id']]);
        $tasker = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $conn->prepare("
            INSERT INTO task_user (task_history_id, user_id, department_id) 
            VALUES (:task_history_id, :tasker_id, :department_id)
        ");

        $stmt->execute([
            "task_history_id" => $task_history_id,
            "tasker_id"       => $tasker['user_id'],
            "department_id"   => $tasker['department_id'],
        ]);
    }

    $stmt = $conn->prepare(
        "UPDATE task SET status='RE-ASSIGNED' WHERE id=:task_id"
    );
    $stmt->execute([
        "task_id" => $_POST['id'],
    ]);

    $conn->commit();

    $response = [ 
        "message" => "Task re-assigned successfully",
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