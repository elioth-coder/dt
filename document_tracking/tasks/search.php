<?php
session_start();
require_once "../connection.php";

try {
    $q = "%" . $_GET['q'] . "%";
    $stmt = $conn->prepare(
        "SELECT *, name AS task_name FROM task WHERE user_id=:user_id AND name LIKE :q"
    );
    $stmt->bindValue(':q', $q);
    $stmt->execute([
        "q"       => $q,
        "user_id" => $_SESSION['user']['id']
    ]);
    $rows = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['creator'] = $_SESSION['user'];
        $stmt_1 = $conn->prepare("
            SELECT * FROM task_history_files 
            WHERE task_history_id=(SELECT id FROM task_history WHERE task_id=:task_id AND status='ASSIGNED')
        ");
        $stmt_1->execute(["task_id" => $row['id']]);
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
