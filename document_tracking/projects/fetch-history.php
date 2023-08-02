<?php
session_start();
require_once "../connection.php";

try {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=(SELECT user_id FROM task WHERE id=:task_id)");
    $stmt->execute(["task_id" => $_GET['task_id']]);
    $creator = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM department WHERE id=(SELECT department_id FROM task WHERE id=:task_id)");
    $stmt->execute(["task_id" => $_GET['task_id']]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);
    $creator['department'] = $department;

    $sql = "
        SELECT 
            T.name AS task_name,  
            TH.id AS h_id,           
            TH.datetime, 
            TH.status, 
            TH.tasker_type, 
            TH.remarks, 
            (SELECT CONCAT(first_name, ' ', last_name) FROM user WHERE id=TH.user_id) AS actor, 
            (SELECT name FROM department WHERE id=TH.department_id) AS actor_department, 
            DT.name AS department, 
            (SELECT name FROM department WHERE id=DU.department_id) AS user_department
        FROM task T 
            INNER JOIN task_history TH 
                ON T.id = TH.task_id 
            LEFT JOIN task_user DU 
                ON TH.id = DU.task_history_id 
            LEFT JOIN task_department DD 
                ON TH.id = DD.task_history_id 
            LEFT JOIN department DT 
                ON DD.department_id = DT.id 
        WHERE T.id=:task_id 
        ORDER BY T.id ASC, TH.id ASC, TH.datetime ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "task_id" => $_GET['task_id']
    ]);

    $rows = [];

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stmt_1 = $conn->prepare("SELECT * FROM task_history_files WHERE task_history_id=:task_history_id");
        $stmt_1->execute([
            "task_history_id" => $row['h_id']
        ]);
        $attachments = [];

        while($attachment = $stmt_1->fetch(PDO::FETCH_ASSOC)) {
            $attachments[] = $attachment;
        }

        $row['attachments'] = $attachments;
        $rows[] = $row;
    }

    $response = [ 
        "message" => "Fetched rows successfully!",
        "rows"    => $rows,
        "creator" => $creator,
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
?>