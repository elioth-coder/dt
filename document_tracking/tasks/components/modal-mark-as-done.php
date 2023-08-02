<div id="MarkAsDoneModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    Mark Task as Done
                    <i class="bi bi-check-square"></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="MarkAsDoneForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="task_id">
                    <input type="hidden" name="department_id">
                    <div class="mb-3">
                        <textarea placeholder="Type some remarks." required class="form-control" rows="5" name="remarks"></textarea>
                    </div>
                    <div class="">
                        <label for="attachments" class="form-label">Attachments:</label>
                        <input type="file" class="form-control" name="attachments[]" id="attachments" multiple />
                    </div>                    
                    <button type="submit" class="d-none"></button>
                </form>
                <div id="ModalAlertContainer" class="py-1"></div>
            </div>
            <div class="modal-footer">
                <button onclick="MarkAsDoneForm.querySelector('button').click();" type="button" class="btn btn-primary">Done</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>