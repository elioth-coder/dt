<?php
session_start();

if(empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ../login.php"));
}
include_once "../components/access_settings.php";
?>
<?php $page = "departments"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management System</title>
    <?php require_once "../components/favicons.php"; ?>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/cropperjs-1.5.13/cropper.min.css">
    <link rel="stylesheet" href="../assets/DataTables/datatables.min.css">
    <link rel="stylesheet" href="../assets/DataTables/Buttons-2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../style.css">
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
</head>
<body>
    <div class="sidebar-menu bg-light">
        <?php include_once "../components/navbar.php"; ?>
    </div>
    <div class="content">
        <div class="card m-3">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="../">Home</a></li>
                        <li class="breadcrumb-item"><a href="../departments/">Human Resource</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Departments</li>
                    </ol>
                </nav>

                <button id="NewDepartmentModalButton" class="btn-success btn position-absolute my-2 mx-3 end-0 top-0" 
                    data-bs-toggle="modal" 
                    data-bs-target="#NewDepartmentModal">
                    New Department
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
        </div>
        <div class="card m-3">
            <div class="card-body">
                <?php require_once "../connection.php"; ?>
                <?php include_once "./components/data-table-department.php"; ?>
            </div>
        </div>
    </div>
    <?php include_once "./components/modal-new-department.php"; ?>    
    <?php include_once "./components/modal-department-members.php"; ?>
    <div class="toast-container p-3 bottom-0 end-0" id="ToastContainer" data-original-class="toast-container p-3"></div>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script src="../assets/cropperjs-1.5.13/cropper.min.js"></script>
    <script src="../assets/webcam.min.js"></script>
    <script src="../assets/js/throttle.js"></script>
    <script src="../assets/js/alert.js"></script>
    <script src="../assets/js/toast.js"></script>
    <script src="../assets/jquery-3.7.0.min.js"></script>
    <script src="../assets/DataTables/datatables.min.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.dataTables.min.js"></script>
    <script src="../assets/DataTables/JSZip-3.10.1/jszip.min.js"></script>
    <script src="../assets/DataTables/pdfmake-0.2.7/pdfmake.min.js"></script>
    <script src="../assets/DataTables/pdfmake-0.2.7/vfs_fonts.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.html5.min.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.print.min.js"></script>
    <script>let departments = <?php echo json_encode($departments); ?>;</script>
    <script src="./script.js?t=<?php echo time(); ?>"></script>
    <script src="./script-member.js?t=<?php echo time(); ?>"></script>
</body>
</html>