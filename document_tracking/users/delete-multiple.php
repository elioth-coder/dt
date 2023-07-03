<?php
require_once "../connection.php";

try {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id IN (". $_GET['id'] .")");
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if($row['profile'] == 'profile.png') continue;

        unlink("../upload/" . $row['profile']);
    }

    $stmt = $conn->prepare(
        "DELETE FROM user WHERE id IN (". $_GET['id'] .")"
    );
    $stmt->execute();

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
