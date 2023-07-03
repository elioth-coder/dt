<?php
require_once "../connection.php";

try {
    $stmt = $conn->prepare(
        "SELECT *, CONCAT(first_name,' ',last_name) AS full_name FROM user 
        WHERE id ". ((isset($_GET['not_selected'])) ? "NOT" : "") ." IN(
            SELECT user_id FROM user_department WHERE department_id=:department_id
        )"
    );
    $stmt->bindValue(':department_id', $_GET['department_id']);
    $stmt->execute();
    $rows = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row;
    }

    $response = [ 
        "message" => "Fetched members successfully!",
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
