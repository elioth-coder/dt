<?php
require_once "../connection.php";

sleep(3);

try {
    $stmt = $conn->prepare(
        "INSERT INTO user_department (user_id, department_id)
        VALUES (:user_id, :department_id)"
    );
    $stmt->execute([
        "user_id"       => $_POST['user_id'],
        "department_id" => $_POST['department_id']
    ]);

    $response = [ 
        "message" => "New member added successfully",
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