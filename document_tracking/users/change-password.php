<?php
require_once "../connection.php";

try {
    $stmt = $conn->prepare(
        "UPDATE user SET password=SHA1(:password)
        WHERE id=:id"
    );
    $stmt->execute($_GET);

    $response = [ 
        "message" => "Password changed successfully",
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
