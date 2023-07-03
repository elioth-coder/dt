<?php
require_once "../vendor/autoload.php";
require_once "../connection.php";

use Intervention\Image\ImageManagerStatic as Image;

try {
    if($_POST['profile']) {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['profile']));
        $_POST['profile'] = "_department-" . time() . ".png";
        file_put_contents("../upload/" . $_POST['profile'], $data);
        $img = Image::make("../upload/" . $_POST['profile']);
        $img->resize(200, 200);
        $img->save("../upload/" . $_POST['profile']);
    } else {
        $_POST['profile'] = "profile.png";
    }
    unset($_POST['id']);

    $stmt = $conn->prepare(
        "INSERT INTO department (profile, name)
        VALUES (:profile, :department_name)"
    );
    $stmt->execute($_POST);

    $response = [ 
        "message" => "New department created successfully",
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