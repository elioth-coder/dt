<?php
session_start();
require_once "../connection.php";

try {
    $conn->beginTransaction();
    $_POST['department_id'] = ($_POST['department_id']=='null') ? NULL : $_POST['department_id'];

    $stmt = $conn->prepare(
        "INSERT INTO document_history (document_id, remarks, user_id, department_id, status, receiver_type)
        VALUES (:document_id, :remarks, :user_id, :department_id, 'RECEIVED', 'PERSONNEL')"
    );
    $stmt->execute([
        "document_id"   => $_POST['id'],
        "remarks"       => $_POST['remarks'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
    ]);

    $document_history_id = $conn->lastInsertId();

    $stmt = $conn->prepare("
        INSERT INTO document_user (document_history_id, user_id, department_id) 
        VALUES (:document_history_id, :receiver_id, :department_id)
    ");
    $stmt->execute([
        "document_history_id" => $document_history_id,
        "receiver_id"         => $_SESSION['user']['id'],
        "department_id"       => $_POST['department_id'],
    ]);

    $stmt = $conn->prepare(
        "UPDATE document SET status='RECEIVED' WHERE id=:document_id"
    );
    $stmt->execute([
        "document_id" => $_POST['id'],
    ]);

    $conn->commit();

    $response = [ 
        "message" => "Document received successfully",
        "status"  => "success"
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    $conn->rollBack();

    $response = [ 
        "message" => "Error: " . $e->getMessage(),
        "status"  => "error",
        "post"    => $_POST,
        "session" => $_SESSION,
    ];
    echo json_encode($response);
}
$conn = null;