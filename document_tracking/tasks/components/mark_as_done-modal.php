<div id="MarkAsDoneModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-primary">MARK TASK AS DONE</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="MarkAsDoneTaskForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="task_id">
                    <input type="hidden" name="department_id">
                    <div class="mb-3">
                        <textarea placeholder="Type some remarks." required class="form-control" rows="5" name="remarks"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments:</label>
                        <input type="file" class="form-control" name="attachments[]" id="attachments" multiple />
                    </div>                    
                    <button id="doneButton" type="submit" class="d-none btn btn-outline-primary"></button>
                </form>
                <div id="ModalAlertContainer" class="py-1"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button onclick="doneButton.click();" type="button" class="btn btn-primary">Done</button>
            </div>
        </div>
    </div>
</div>