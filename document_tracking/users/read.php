<?php
require_once "../connection.php";

try {
    $stmt = $conn->prepare("SELECT *, (SELECT GROUP_CONCAT(department_id SEPARATOR ',') FROM user_department WHERE user_id=id) AS departments FROM user WHERE id NOT IN(1)");
    $stmt->execute();
    $rows = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
