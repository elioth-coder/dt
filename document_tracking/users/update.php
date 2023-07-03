<?php
require_once "../vendor/autoload.php";
require_once "../connection.php";

use Intervention\Image\ImageManagerStatic as Image;

try {
    if(str_contains($_POST['profile'], 'data:image')) {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['profile']));
        $_POST['profile'] = "_user-" . time() . ".png";
        file_put_contents("../upload/" . $_POST['profile'], $data);
        $img = Image::make("../upload/" . $_POST['profile']);
        $img->resize(200, 200);
        $img->save("../upload/" . $_POST['profile']);
    }
    if($_POST['profile'] == "") {
        $_POST['profile'] = "profile.png";
    }
    $departments = $_POST['department'];
    unset($_POST['department']);

    $conn->beginTransaction();

    $stmt = $conn->prepare("SELECT * FROM user WHERE id=" . $_POST['id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['profile'] != $_POST['profile']) {
        if($row['profile'] != "profile.png") {
            unlink("../upload/" . $row['profile']);
        }
    }

    $stmt = $conn->prepare(
        "UPDATE user SET username=:username, role=:role, profile=:profile, 
        email=:email, birthday=:birthday,
        first_name=:first_name, last_name=:last_name, gender=:gender
        WHERE id=:id"
    );
    $stmt->execute($_POST);

    $stmt = $conn->prepare(
        "DELETE FROM user_department WHERE user_id=:user_id"
    );
    $stmt->execute(["user_id" => $_POST['id']]);

    foreach($departments as $department) {
        $stmt = $conn->prepare(
            "INSERT INTO user_department (user_id, department_id) VALUES (:user_id, :department_id)"
        );
        $stmt->execute(["user_id" => $_POST['id'], "department_id" => $department]);
    }

    $conn->commit();

    $response = [ 
        "message" => "User updated successfully",
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
