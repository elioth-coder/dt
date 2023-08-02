<nav>
    <section>
        <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./"
            style="text-decoration: none;"
            class="d-block bg-primary text-center pt-5 pb-4">
            <img src="<?php echo ($page=="dashboard") ? "" : "." ?>./assets/images/logo.png" 
                class="d-inline-block rounded-circle shadow-lg" style="height: 100px;" />
        </a>
        <h5 class="m-4 text-primary">Document Management</h5>     
    </section>
    <div class="accordion accordion-flush border-top border-bottom" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button <?php echo (in_array($page, ['documents', 'reports'])) ? "" : "collapsed" ?>" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseOne" 
                    aria-expanded="<?php echo (in_array($page, ['documents', 'reports'])) ? "true" : "false" ?>" 
                    aria-controls="collapseOne">
                    <i class="bi bi-file-earmark-richtext me-3"></i>
                    Document
                </button>
            </h2>
            <div id="collapseOne" data-bs-parent="#accordionExample"
                class="accordion-collapse collapse <?php echo (in_array($page, ['documents', 'reports'])) ? "show" : "" ?>">
                <div class="accordion-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./documents/" 
                            class="list-group-item list-group-item-action list-group-item-success <?php echo ($page=='documents') ? "active" : "" ?>">
                            <i class="bi bi-geo-alt me-3"></i>
                            Tracking
                        </a>
                        <?php if(in_array('reports', $accessible_pages)) { ?>
                            <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./reports/"
                                class="list-group-item list-group-item-action list-group-item-success <?php echo ($page=='reports') ? "active" : "" ?>">
                                <i class="bi bi-file-earmark-spreadsheet me-3"></i>
                                Report
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button <?php echo (in_array($page, ['tasks', 'projects'])) ? "" : "collapsed" ?>" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseTwo" 
                    aria-expanded="<?php echo (in_array($page, ['tasks', 'projects'])) ? "true" : "false" ?>" 
                    aria-controls="collapseTwo">
                    <i class="bi bi-bar-chart-steps me-3"></i>
                    Task Management
                </button>
            </h2>
            <div id="collapseTwo" 
                data-bs-parent="#accordionExample"
                class="accordion-collapse collapse <?php echo (in_array($page, ['tasks', 'projects'])) ? "show" : "" ?>">
                <div class="accordion-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./tasks/"
                            class="list-group-item list-group-item-action list-group-item-success <?php echo ($page=='tasks') ? "active" : "" ?>">
                            <i class="bi bi-list-task me-3"></i>
                            Tasks
                        </a>
                        <?php if(in_array('projects', $accessible_pages)) { ?>
                            <a  href="<?php echo ($page=="dashboard") ? "" : "." ?>./projects/"
                                class="list-group-item list-group-item-action list-group-item-success <?php echo ($page=='projects') ? "active" : "" ?>">
                                <i class="bi bi-kanban me-3"></i>
                                Projects
                            </a>
                        <?php } ?>                    
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button <?php echo (in_array($page, ['users', 'departments'])) ? "" : "collapsed" ?>" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#collapseThree" 
                    aria-expanded="<?php echo (in_array($page, ['users', 'departments'])) ? "true" : "false" ?>" 
                    aria-controls="collapseThree">
                    <i class="bi bi-universal-access me-3"></i>
                    Human Resource
                </button>
            </h2>
            <div id="collapseThree" 
                data-bs-parent="#accordionExample"
                class="accordion-collapse collapse <?php echo (in_array($page, ['users', 'departments'])) ? "show" : "" ?>">
                <div class="accordion-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./users/" 
                            class="list-group-item list-group-item-action list-group-item-success <?php echo ($page=='users') ? "active" : "" ?>">
                            <i class="bi bi-person-fill me-3"></i>
                            Users
                        </a>
                        <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./departments/" 
                            class="list-group-item list-group-item-action list-group-item-success <?php echo ($page=='departments') ? "active" : "" ?>">
                            <i class="bi bi-people-fill me-3"></i>
                            Departments
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button <?php echo ($page=='account') ? "" : "collapsed" ?>" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseFour" 
                    aria-expanded="<?php echo ($page=='account') ? "true" : "false" ?>" 
                    aria-controls="collapseFour">
                    <i class="bi bi-person-circle me-3"></i>
                    Account
                </button>
            </h2>
            <div id="collapseFour" 
                data-bs-parent="#accordionExample"
                class="accordion-collapse collapse <?php echo ($page=='account') ? "show" : "" ?>">
                <div class="accordion-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./account/?tab=profile" 
                            class="list-group-item list-group-item-action list-group-item-success <?php echo (!empty($_GET['tab'])) ? (($_GET['tab']=='profile') ? "active" : "") : "" ?>">
                            <i class="bi bi-person-gear me-3"></i>
                            Profile
                        </a>
                        <a href="<?php echo ($page=="dashboard") ? "" : "." ?>./account/?tab=activity_log" 
                            class="list-group-item list-group-item-action list-group-item-success <?php echo (!empty($_GET['tab'])) ? (($_GET['tab']=='activity_log') ? "active" : "") : "" ?>">
                            <i class="bi bi-person-lines-fill me-3"></i>
                            Activity Log
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-grid p-3">
        <button onclick="logout();" 
            class="btn btn-warning">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </button>
    </div>
</nav>