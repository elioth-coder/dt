<?php
session_start();
require_once "../connection.php";

try {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=(SELECT user_id FROM document WHERE id=:document_id)");
    $stmt->execute(["document_id" => $_GET['document_id']]);
    $creator = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM department WHERE id=(SELECT department_id FROM document WHERE id=:document_id)");
    $stmt->execute(["document_id" => $_GET['document_id']]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);
    $creator['department'] = $department;

    $sql = "
        SELECT 
            D.document_type, 
            D.name AS document_name,             
            DH.datetime, 
            DH.status, 
            DH.receiver_type, 
            DH.remarks, 
            (SELECT CONCAT(first_name, ' ', last_name) FROM user WHERE id=DH.user_id) AS actor, 
            (SELECT name FROM department WHERE id=DH.department_id) AS actor_department, 
            DT.name AS department, 
            (SELECT name FROM department WHERE id=DU.department_id) AS user_department
        FROM document D 
            INNER JOIN document_history DH 
                ON D.id = DH.document_id 
            LEFT JOIN document_user DU 
                ON DH.id = DU.document_history_id 
            LEFT JOIN document_department DD 
                ON DH.id = DD.document_history_id 
            LEFT JOIN department DT 
                ON DD.department_id = DT.id 
        WHERE D.id=:document_id 
        ORDER BY D.id ASC, DH.id ASC, DH.datetime ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "document_id" => $_GET['document_id']
    ]);

    $rows = [];

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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