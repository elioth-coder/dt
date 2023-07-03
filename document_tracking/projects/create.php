<?php
session_start();
require_once "../connection.php";

try {
    $stmt = $conn->prepare(
        "INSERT INTO project (name, date_started, deadline, status, user_id, department_id)
        VALUES (:name, :date_started, :deadline, 'CREATED', :user_id, :department_id)"
    );
    $stmt->execute([
        "name"          => $_POST['name'],
        "date_started"  => $_POST['date_started'],
        "deadline"      => $_POST['deadline'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
    ]);

    $response = [ 
        "message" => "New project created successfully",
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