<?php
require_once "../connection.php";

sleep(3);

try {
    $stmt = $conn->prepare(
        "DELETE FROM user_department WHERE user_id=:user_id AND department_id=:department_id"
    );
    $stmt->execute([
        "user_id"       => $_POST['user_id'],
        "department_id" => $_POST['department_id'],
    ]);

    $response = [ 
        "message" => "Member removed successfully",
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