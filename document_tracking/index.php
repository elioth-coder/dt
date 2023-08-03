<?php
session_start();

if (empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ./login.php"));
}
include_once "./components/access_settings.php";
?>
<?php $page = "dashboard"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management</title>
    <?php require_once "./components/favicons.php"; ?>
    <link rel="stylesheet" href="./assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./assets/bootstrap-icons-1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./style.css?t=<?php echo time(); ?>">
</head>

<body>
    <div class="sidebar-menu shadow-sm">
        <?php include_once "./components/navbar.php"; ?>
    </div>
    <div class="content">
        <div class="card m-3">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="./">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card m-3">
            <div class="card-body">
                <h2 class="text-primary">
                    <i class="bi bi-bar-chart-line"></i>
                    System Overview
                </h2>
                <hr>

                <?php
                require_once "connection.php";
                require_once "./components/counter_data.php";
                $parameters = [
                    [
                        "key"    => "document_tracking",
                        "title"  => "Document Tracking",
                        "active" => true,
                    ],
                    [
                        "key"   => "task_management",
                        "title" => "Task Management",
                        "active" => false,
                    ],
                ];

                if ($_SESSION['user']['role'] == 'ADMIN') {
                    $parameters[] = [
                        "key"   => "human_resource",
                        "title" => "Human Resource",
                        "active" => false,
                    ];
                }
                ?>
                <div class="d-flex align-items-start w-75">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php
                        foreach ($parameters as $param) { ?>
                            <button class="nav-link <?php echo $param['active'] ? 'active' : ''; ?>" id="v-pills-<?php echo str_replace(" ", "-", strtolower($param['title'])); ?>-tab" data-bs-toggle="pill" data-bs-target="#v-pills-<?php echo str_replace(" ", "-", strtolower($param['title'])); ?>" type="button" role="tab" aria-controls="v-pills-<?php echo str_replace(" ", "-", strtolower($param['title'])); ?>" aria-selected="false">
                                <?php echo $param['title']; ?>
                            </button>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="tab-content w-75" id="v-pills-tabContent">
                        <?php
                        foreach ($parameters as $param) { ?>
                            <div class="tab-pane fade  <?php echo $param['active'] ? 'show active' : ''; ?>" id="v-pills-<?php echo str_replace(" ", "-", strtolower($param['title'])); ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo str_replace(" ", "-", strtolower($param['title'])); ?>-tab" tabindex="0">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="fs-4 text-center text-secondary" colspan="3"><?php echo $param['title']; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($counter_data[$param['key']] as $data) {
                                            if ($data['count'] <= 0) continue;
                                            if (!in_array($data['page'], $accessible_pages)) continue;
                                        ?>
                                            <tr>
                                                <td class=""><?php echo $data['title']; ?></td>
                                                <td class="text-center"><?php echo $data['count']; ?></td>
                                                <td class="text-center" style="width: 75px;">
                                                    <a href="<?php echo $data['link']; ?>" class="btn btn-primary">
                                                        <i class="bi bi-arrow-up-right"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                        } // end of foreach..
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } // end of foreach..
                        ?>
                    </div>
                </div>
            </div>
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