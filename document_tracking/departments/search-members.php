<?php
require_once "../connection.php";

try {
    $q = "%" . $_GET['q'] . "%";
    $stmt = $conn->prepare(
        "SELECT * FROM (SELECT *, CONCAT(first_name,' ',last_name) AS full_name FROM user) AS user 
        WHERE full_name LIKE :q
        AND id NOT IN(SELECT user_id FROM user_department WHERE department_id=:department_id)"
    );
    $stmt->bindValue(':q', $q);
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
