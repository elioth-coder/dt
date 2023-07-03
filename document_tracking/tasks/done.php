<?php
session_start();
require_once "../connection.php";

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

try {
    $conn->beginTransaction();
    $_POST['department_id'] = ($_POST['department_id']=='null') ? NULL : $_POST['department_id'];

    $stmt = $conn->prepare(
        "INSERT INTO task_history (task_id, remarks, user_id, department_id, status, tasker_type)
        VALUES (:task_id, :remarks, :user_id, :department_id, 'DONE', 'PERSONNEL')"
    );
    $stmt->execute([
        "task_id"       => $_POST['task_id'],
        "remarks"       => $_POST['remarks'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
    ]);
    $task_history_id = $conn->lastInsertId();

    if($_FILES['attachments']['error'][0] == 0) { // checks if there is an uploaded file
        $file_ary = reArrayFiles($_FILES['attachments']);
        $i = 0;
        foreach ($file_ary as $file) {
            $i++;
            $file['generated_name'] = "file_" . time() . "_" . $i . "." . pathinfo($file["name"], PATHINFO_EXTENSION);
            move_uploaded_file($file['tmp_name'], "./files/".$file["generated_name"]);
            
            $stmt = $conn->prepare(
                "INSERT INTO task_history_files (task_history_id, filename, generated_name)
                VALUES (:task_history_id, :filename, :generated_name)"
            );
            $stmt->execute([
                "task_history_id" => $task_history_id,
                "filename"        => $file['name'],
                "generated_name"  => $file['generated_name'],
            ]);
        }
    }
    $stmt = $conn->prepare(
        "SELECT user_id, department_id FROM task WHERE id=:task_id"
    );
    $stmt->execute(["task_id" => $_POST['task_id']]);
    $tasker = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("
        INSERT INTO task_user (task_history_id, user_id, department_id) 
        VALUES (:task_history_id, :user_id, :department_id)
    ");
    $stmt->execute([
        "task_history_id" => $task_history_id,
        "user_id"         => $tasker['user_id'],
        "department_id"   => $tasker['department_id'],
    ]);

    $stmt = $conn->prepare(
        "UPDATE task SET status='DONE' WHERE id=:task_id"
    );
    $stmt->execute([
        "task_id" => $_POST['task_id'],
    ]);

    $conn->commit();

    $response = [ 
        "message" => "Task marked as done successfully",
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