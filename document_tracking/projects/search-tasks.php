<?php
require_once "../connection.php";

try {
    $q = "%" . $_GET['q'] . "%";
    $stmt = $conn->prepare("SELECT * FROM task 
        WHERE name LIKE :q
        AND id NOT IN(
            SELECT task_id FROM project_tasks
        )");

    $stmt->bindValue(':q', $q);
    $stmt->execute();
    $rows = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row;
    }

    $response = [ 
        "message" => "Fetched tasks successfully!",
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
