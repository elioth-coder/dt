<?php
session_start();

if(empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ../login.php"));
}
include_once "../components/access_settings.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management System</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.10.4/font/bootstrap-icons.css">
    <style>
    body {
        background-color: #ddd;
    }
    </style>
</head>
<body>
    <?php $page = "account"; ?>
    <?php include_once "../components/navbar.php"; ?>
    <?php include_once "../components/greeting.php"; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="bg-light p-3 rounded-3">
                <?php
                require_once "../connection.php";
                try {
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id=:user_id");
                    $stmt->execute(["user_id" => $_SESSION['user']['id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {

                }                  
                ?>
                    <table class="table table-striped table-bordered">
                    <thead>
                    <tr><th colspan="2" class="text-center">Account Details</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-end align-middle">Profile:</th>
                            <td>
                                <img style="height: 70px;"
                                    src="../upload/<?php echo $user['profile']; ?>" 
                                    class="rounded-circle border shadow" 
                                />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end">Username:</th>
                            <td><?php echo $user['username']; ?></td>
                        </tr>                              
                        <tr>
                            <th class="text-end">Password:</th>
                            <td><h4 class="mb-0">* * * * * * * * * *</h4></td>
                        </tr>        
                        <tr>
                            <th class="text-end">User Role:</th>
                            <td class="text-capitalize"><?php echo strtolower($user['role']); ?></td>
                        </tr>  
                        <tr>
                            <th class="text-end">Full Name:</th>
                            <td><?php echo $user['first_name'] . " " . $user['last_name']; ?></td>
                        </tr>

                        <tr>
                            <th class="text-end">Gender:</th>
                            <td><?php echo $user['gender']; ?></td>
                        </tr>
                        <tr>
                            <th class="text-end">Birthday:</th>
                            <td><?php echo $user['birthday']; ?></td>
                        </tr>  
                        <tr>
                            <th class="text-end">Email:</th>
                            <td><?php echo $user['email']; ?></td>
                        </tr>  
                        <tr>
                            <th class="text-end">Affiliated <br> Departments:</th>
                            <td>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM `department` WHERE id IN (SELECT department_id FROM user_department WHERE user_id=:user_id)");
                                $stmt->execute(["user_id" => $_SESSION['user']['id']]);
                                $i = 0;
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $i++; ?>
                                    <p><?php echo $i . ". " . $row['name']; ?></p>
                                <?php
                                } // end of while..
                            } catch (PDOException $e) {

                            }                  
                            ?>
                            </td>
                        </tr>                  
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script src="./script.js?t=<?php echo time(); ?>"></script>
</body>
</html>