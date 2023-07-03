<?php
require_once "../connection.php";

try {
    $stmt = $conn->prepare(
        "INSERT INTO project_tasks (project_id, task_id)
        VALUES (:project_id, :task_id)"
    );
    $stmt->execute([
        "project_id" => $_POST['project_id'],
        "task_id"    => $_POST['task_id']
    ]);

    $response = [ 
        "message" => "New task added successfully",
        "status"  => "success"
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    $response = [ 
        "message" => "Error: " . $e->getMessage(),
        "status"  => "error"
    ];
    echo json_encode($response);
}
$conn = null;