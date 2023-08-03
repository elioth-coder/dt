<div class="modal fade" id="FormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-2" id="exampleModalLabel">Create new task</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="task_id" name="task_id" />
                    <div id="AlertContainer"></div>
                    <div class="row">
                        <div class="col">
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
                                <input placeholder="Enter name of task." required type="text" class="form-control" name="name" id="task_name" />
                            </div>
                            <div class="mb-3">
                                <label for="deadline" class="form-label">Deadline:</label>
                                <input type="date" class="form-control" name="deadline" id="deadline" />
                            </div>
                            <div class="mb-3">
                                <label for="attachments" class="form-label">Attachments:</label>
                                <input type="file" class="form-control" name="attachments[]" id="attachments" multiple />
                            </div>

                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <style>
                                    #tasker+* .selectize-input,
                                    #tasker+* .single.selectize-control .focus.selectize-input {
                                        padding: 0.375rem 0.75rem;
                                        font-size: 1rem;
                                        font-weight: 400;
                                        line-height: 1.5;
                                    }
                                </style>
                                <select name="tasker" id="tasker">
                                    <option></option>
                                    <?php
                                    foreach ($taskers as $tasker) {
                                        $optionValue = ($tasker['type'] == 'DEPARTMENT')
                                            ? $tasker['type'] . "|" . $tasker['id']
                                            : $tasker['type'] . "|" . $tasker['id'] . "|" . $tasker['department']['id'];
                                        $labelValue = ($tasker['type'] == 'DEPARTMENT')
                                            ? $tasker['name'] . " - All Members"
                                            : $tasker['department']['name'] . " - " . $tasker['name'];
                                    ?>
                                        <option value="<?php echo $optionValue; ?>">
                                            <?php echo $labelValue; ?>
                                        </option>
                                    <?php
                                    } // end of foreach..
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <textarea placeholder="Type some remarks." required 
                                    class="form-control" name="remarks" id="remarks" 
                                    rows="8"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="float-end">
                        <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                        <button id="reset" type="reset" class="btn btn-secondary">Reset</button>
                        <button id="cancel" type="reset" class="btn btn-outline-danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>                