<?php
session_start();

?>
<?php $page = "dashboard"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management System Login</title>
    <?php require_once "./components/favicons.php"; ?>
    <link rel="stylesheet" href="./assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <style>
        #login {
            background-color: #fff;
            width: 350px;
            display: block;
            margin: 15px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 15px;
        }

        #AlertContainer, #Timer {
            width: 350px;
            margin: 15px auto;
        }

        #header {
            border-bottom: 1px solid #ddd;  
            padding-top: 15px;            
        }
    </style>
</head>

<body style="background-color: #eee;">
    <div id="header" class="pb-2 bg-primary">
        <h3 class="text-center text-white">
            Document Management System
        </h3>
    </div>
    <div style="height: calc(100vh - 180px);">
        <img src="./assets/images/logo.png" style="height: 100px;" 
            class="border shadow-lg d-block my-3 mx-auto rounded-circle"
        />
        <div id="Timer" class="text-danger text-center"></div>
        <form id="login" method="post" action="/process/login.php">
            <h3 class="text-primary">Login to your account</h3><hr>
            <input required type="text" name="username" class="form-control" placeholder="Enter username." id="username" /><br>
            <input required type="password" name="password" class="form-control" placeholder="Enter password." id="password" /><br>
            <button type="submit" class="btn btn-primary" id="submit">Log in</button>
        </form>
        <div id="AlertContainer"></div>
    </div>
    <script src="./assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/alert.js"></script>
    <script src="./assets/js/sleep.js"></script>
    <script src="./login.js?t=<?php echo time(); ?>"></script>
</body>

</html>