<div class="modal fade" id="NewProjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    New Project
                    <i class="bi bi-plus-lg"></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="NewProjectForm" method="POST">
                    <div id="AlertContainer"></div>
                    <div class="mb-3">
                        <select name="department_id" id="department_id" class="form-control">
                            <?php
                            foreach ($my_departments as $department) { ?>
                                <option value="<?php echo $department['id']; ?>">
                                    <?php echo $department['name']; ?>
                                </option>
                            <?php
                            } // end of foreach..
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input placeholder="Enter name of the project." required type="text" class="form-control" name="name" id="project_name" />
                    </div>
                    <div class="mb-3">
                        <label for="date_started" class="form-label">Date started:</label>
                        <input type="date" class="form-control" name="date_started" id="date_started" />
                    </div> 
                    <div class="">
                        <label for="deadline" class="form-label">Deadline:</label>
                        <input type="date" class="form-control" name="deadline" id="deadline" />
                    </div>                                            
                    <button type="submit" class="d-none"></button>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="NewProjectForm.querySelector('button').click();" type="button" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>        
        </div>
    </div>
</div>