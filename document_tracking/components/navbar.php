<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo ($page=="dashboard") ? "" : "." ?>./">
            <img src="<?php echo ($page=="dashboard") ? "" : "." ?>./assets/images/logo.png" 
                class="d-inline-block rounded-circle shadow-lg" style="height: 40px;" />
            Document Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if(in_array('dashboard', $accessible_pages)) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page=="dashboard") ? "active" : "" ?>" 
                            href="<?php echo ($page=="dashboard") ? "" : "." ?>./">
                            Dashboard
                        </a>
                    </li>
                <?php } // end of if ?>
 

                <li class="nav-item dropdown">
                    <a class="nav-link <?php echo (in_array($page, ['documents', 'reports'])) ? "active" : "" ?> dropdown-toggle" href="#" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Document
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./documents/">Tracking</a></li>
                        <?php if(in_array('reports', $accessible_pages)) { ?>
                        <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./reports/">Report Generation</a></li>
                        <?php } // end of if.. ?>
                    </ul>
                </li> 


                <li class="nav-item dropdown">
                    <a class="nav-link <?php echo (in_array($page, ['projects', 'tasks'])) ? "active" : "" ?> dropdown-toggle" href="#" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Task Management
                    </a>
                    <ul class="dropdown-menu">
                        <?php if(in_array('projects', $accessible_pages)) { ?>
                        <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./projects/">Manage Projects</a></li>
                        <?php } // end of if.. ?>
                        <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./tasks/">Manage Tasks</a></li>
                    </ul>
                </li>   

                <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php echo (in_array($page, ['projects', 'tasks'])) ? "active" : "" ?> dropdown-toggle" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Human Resource
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./users/">Users</a></li>
                            <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./departments/">Departments</a></li>
                        </ul>
                    </li>   
                <?php } // end of if ?>

                <?php if(in_array('account', $accessible_pages)) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php echo ($page=="account") ? "active" : "" ?> dropdown-toggle" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./account/#profile">Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo ($page=="dashboard") ? "" : "." ?>./account/#activity_log">Activity Log</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:logout();">Logout</a></li>
                        </ul>
                    </li>                       
                <?php } // end of if ?>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2 bg-white" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>