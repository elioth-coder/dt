<?php
session_start();

if(empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ./login.php"));
} 
include_once "./components/access_settings.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management System</title>
    <link rel="stylesheet" href="./assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./assets/bootstrap-icons-1.10.4/font/bootstrap-icons.css">
    <style>
    body {
        background-color: #ddd;
    }
    </style>
</head>
<body>
    <?php $page = "dashboard"; ?>
    <?php include_once "./components/navbar.php"; ?>
    <?php include_once "./components/greeting.php"; ?>
    <div class="p-3 d-flex flex-wrap">
        <?php 
        require_once "connection.php";
        require_once "./components/counter_data.php"; 
        $parameters = [
            [
                "key"   => "document_tracking",
                "title" => "Document Tracking",
            ],
            [
                "key"   => "task_management",
                "title" => "Task Management",
            ], 
        ];

        if($_SESSION['user']['role'] == 'ADMIN') {
            $parameters[] = [
                "key"   => "human_resource",
                "title" => "Human Resource",
            ];           
        }
        foreach($parameters as $param) { ?>
            <div class="p-3">
                <h4 class="text-primary text-center"><?php echo $param['title']; ?></h4>
                <hr>
                <div class="p-3 d-flex gap-3 mb-5">
                    <?php
                    foreach($counter_data[$param['key']] as $data) { 
                        if($data['count'] <= 0) continue;
                        if(!in_array($data['page'], $accessible_pages)) continue;
                        ?>
                        <a href="<?php echo $data['link']; ?>" class="text-decoration-none text-dark">
                            <div class="shadow-lg rounded-3" 
                                style="max-width: 120px; max-height: 110px; min-width: 120px; min-height: 110px;">
                                <div class="bg-<?php echo $data['bg-color']; ?> w-100 h-75 rounded-top-3 text-center align-middle"
                                    style="padding: 16px 0">
                                    <h1 class="<?php echo $data['font-color']; ?>"><?php echo $data['count']; ?></h1>    
                                </div>
                                <div class="w-100 h-25 rounded-bottom-3 bg-white text-center py-1">
                                    <span class="text-primary"><?php echo $data['title']; ?></span>
                                </div>
                            </div>
                        </a>
                    <?php
                    } // end of foreach..
                    ?>
                </div>
            </div>
        <?php
        } // end of foreach..
        ?>
    </div>
    <script src="./assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script>
        function logout() {
            Swal.fire({
                html: [
                    `<p class="text-center">`,
                    `   <img style="height: 100px;" src='./assets/images/spinner.gif' />`,
                    `</p>`,
                ].join("\n"),
                title: "Logging out...",
                timer: 3000,
                showConfirmButton: false,
            }).then(async () => {
                window.location.href = "./process/logout.php";
            });
        }        
    </script>
</body>

</html>