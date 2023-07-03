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
    <title>Document Tracking System</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/cropperjs-1.5.13/cropper.min.css">
    <style>
        #snapshotPreview {
            margin: auto;
        }

        #imageToCrop {
            display: block;
            max-width: 100%;
        }

        #imagePreview {
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
            border-radius: 100%;
        }
    </style>
    <style>
    body {
        background-color: #ddd;
    }
    </style>
</head>
<body>
    <?php $page = "departments"; ?>
    <?php include_once "../components/navbar.php"; ?>
    <?php include_once "../components/greeting.php"; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="p-3 d-block d-md-none"><hr></div>
            <div class="col p-3 pt-0">
                <button id="OpenModalButton"
                    type="button" 
                    class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#FormModal">
                    Create new department
                    <i class="bi bi-plus-lg"></i>
                </button>    
                <hr>
                <section class="mb-3">
                    <input id="search" type="text" class="form-control form-control-lg"
                        placeholder="Search name of department."
                    />
                </section>
                <div class="table-container" style="overflow-x: scroll; max-width: 100%;">
                    <?php include_once "./components/table.php"; ?>
                </div>
                <section class="text-end p-3">
                    <button id="DeleteSelected" class="btn btn-outline-danger">
                        <i class="bi-trash-fill"></i>
                        Delete Selected
                    </button>
                </section>
            </div>
        </div>
    </div>
    <?php include_once "./components/form-modal.php"; ?>    
    <?php include_once "./components/member-modal.php"; ?>
    <div class="toast-container p-3 bottom-0 end-0" id="ToastContainer" data-original-class="toast-container p-3"></div>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script src="../assets/cropperjs-1.5.13/cropper.min.js"></script>
    <script src="../assets/webcam.min.js"></script>
    <script src="../assets/js/throttle.js"></script>
    <script src="../assets/js/alert.js"></script>
    <script src="../assets/js/toast.js"></script>
    <script src="./script.js?t=<?php echo time(); ?>"></script>
    <script src="./script-member.js?t=<?php echo time(); ?>"></script>
</body>
</html>