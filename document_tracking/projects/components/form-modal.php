<div class="modal fade" id="FormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-2" id="exampleModalLabel">Create new project</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="projectForm" method="POST">
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
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline:</label>
                        <input type="date" class="form-control" name="deadline" id="deadline" />
                    </div>                                            
                    <hr>
                    <div class="float-end">
                        <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                        <button id="reset" type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>