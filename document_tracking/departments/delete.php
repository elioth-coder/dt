<?php
require_once "../connection.php";

try {
    $stmt = $conn->prepare("SELECT * FROM department WHERE id=:id");
    $stmt->execute($_GET);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['profile'] != 'profile.png') {
        unlink("../upload/" . $row['profile']);
    }
    
    $stmt = $conn->prepare(
        "DELETE FROM department WHERE id=:id"
    );
    $stmt->execute($_GET);

    $response = [ 
        "message" => "Deleted successfully",
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
