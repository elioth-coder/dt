<?php
session_start();
require_once "../connection.php";

try {
    $stmt = $conn->prepare("SELECT *, name AS document_name FROM document WHERE user_id=:user_id");
    $stmt->execute(["user_id" => $_SESSION['user']['id']]);
    $rows = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['creator'] = $_SESSION['user'];
        $rows[] = $row;
    }

    $response = [ 
        "message" => "Fetched rows successfully!",
        "rows"    => $rows,
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
