<?php
require_once "../connection.php";

try {
    if(isset($_GET['not_selected'])) {
        $sql = "SELECT * FROM task WHERE id NOT IN(
            SELECT task_id FROM project_tasks
        )";
    } else {
        $sql = "SELECT * FROM task WHERE id IN(
            SELECT task_id FROM project_tasks WHERE project_id=:project_id
        )";
    }

    $stmt = $conn->prepare($sql);
    if(!isset($_GET['not_selected'])) {
        $stmt->bindValue(':project_id', $_GET['project_id']);
    }
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
