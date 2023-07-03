<?php
require_once "../connection.php";

try {
    $q = "%" . $_GET['q'] . "%";
    $stmt = $conn->prepare("
        SELECT *, 
        (SELECT COUNT(*) FROM user_department WHERE department_id=id) AS members 
        FROM department
        WHERE name LIKE :q
    ");
    
    $stmt->bindValue(':q', $q);
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
