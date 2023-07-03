<?php
session_start();

function login() {
    require_once "../connection.php";

    try {
        $stmt = $conn->prepare(
            "SELECT * FROM user 
            WHERE username=:username
            AND password=SHA1(:password)"
        );
        $stmt->execute($_POST);
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            unset($user['password']);

            $_SESSION['user'] = $user;
            $_SESSION['logged_in'] = true;

            $response = [
                "status" => "success",
                "message" => "Successfully logged in"
            ];

            echo json_encode($response);
        } else {
            $_SESSION['last_attempt'] = time();
            if (empty($_SESSION['attempts'])) $_SESSION['attempts'] = 0;
            $_SESSION['attempts']++;

            $response = [
                "status" => "error",
                "message" => "Invalid username or password"
            ];

            echo json_encode($response);
        }
    } catch (PDOException $e) {
        $response = [
            "message" => "Error: " . $e->getMessage(),
            "status"  => "error"
        ];
        echo json_encode($response);
    }
}

if (!empty($_SESSION['attempts']) && !empty($_SESSION['last_attempt'])) {
    $time_set    = 30;
    $max_attempt = 3;
    $wait_time   = $_SESSION['last_attempt'] + $time_set;

    if ($_SESSION['attempts'] >= $max_attempt) {
        if (empty($_SESSION['is_waiting'])) $_SESSION['is_waiting'] = FALSE;
        if ($_SESSION['is_waiting']) {
            if ($wait_time < time()) {
                $_SESSION['attempts'] = 0;
                $_SESSION['is_waiting'] = FALSE;

                login();
                die();
            }

            $response = [
                "status" => "error",
                "message" => "Reached the maximum number of attempts. Try again later."
            ];

            echo json_encode($response);
            die();
        } else {
            $_SESSION['is_waiting'] = TRUE;
            $response = [
                "time_set" => $time_set,
                "limit_reached" => TRUE,
                "status" => "error",
                "message" => "Reached the maximum number of attempts."
            ];

            echo json_encode($response);
            die();
        }
    }
}

login();
