<div id="ForwardModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-primary">FORWARD DOCUMENT</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ForwardDocumentForm" method="POST">
                    <input type="hidden" name="document_id">
                    <input type="hidden" name="department_id">
                    <div class="mb-3">
                        <style>
                            #forward-receiver+* .selectize-input,
                            #forward-receiver+* .single.selectize-control .focus.selectize-input {
                                padding: 0.375rem 0.75rem;
                                font-size: 1rem;
                                font-weight: 400;
                                line-height: 1.5;
                            }
                        </style>
                        <select name="receiver" id="forward-receiver">
                            <option></option>
                            <?php
                            foreach($receivers as $receiver) { 
                                $optionValue = ($receiver['type']=='DEPARTMENT')
                                    ? $receiver['type'] ."|". $receiver['id']
                                    : $receiver['type'] ."|". $receiver['id'] ."|". $receiver['department']['id'];
                                $labelValue = ($receiver['type']=='DEPARTMENT')
                                    ? $receiver['name'] . " - All Members" 
                                    : $receiver['department']['name'] ." - ". $receiver['name'];
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
                        <textarea placeholder="Type some remarks." required class="form-control" rows="5" name="remarks"></textarea>
                    </div>
                    <button id="forwardButton" type="submit" class="d-none btn btn-outline-primary"></button>
                </form>
                <div id="ModalAlertContainer" class="py-1"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button onclick="forwardButton.click();" type="button" class="btn btn-primary">Forward</button>
            </div>
        </div>
    </div>
</div>