<div class="modal fade" id="NewDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    New Document
                    <i class="bi bi-plus-lg"></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="NewDocumentForm" method="POST">
                    <input type="hidden" name="id" id="document_id">
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
                                <input placeholder="Enter name of document." required type="text" class="form-control" name="name" id="document_name" />
                            </div>
                            <div class="mb-3">
                                <input placeholder="Enter type of document." required type="text" class="form-control" list="doc_types" name="document_type" id="document_type" />
                                <datalist id="doc_types">
                                    <?php
                                    foreach ($doc_types as $doc_type) { ?>
                                        <option value="<?php echo $doc_type; ?>">
                                        <?php
                                    } // end of foreach..
                                        ?>
                                </datalist>
                            </div>

                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <style>
                                    #receiver+* .selectize-input,
                                    #receiver+* .single.selectize-control .focus.selectize-input {
                                        padding: 0.375rem 0.75rem;
                                        font-size: 1rem;
                                        font-weight: 400;
                                        line-height: 1.5;
                                    }
                                </style>
                                <select name="receiver" id="receiver">
                                    <option></option>
                                    <?php
                                    foreach ($receivers as $receiver) {
                                        $optionValue = ($receiver['type'] == 'DEPARTMENT')
                                            ? $receiver['type'] . "|" . $receiver['id']
                                            : $receiver['type'] . "|" . $receiver['id'] . "|" . $receiver['department']['id'];
                                        $labelValue = ($receiver['type'] == 'DEPARTMENT')
                                            ? $receiver['name'] . " - All Members"
                                            : $receiver['department']['name'] . " - " . $receiver['name'];
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
                                    rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <button class="d-none" type="submit"></button>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="NewDocumentForm.querySelector('button').click();" type="button" class="btn btn-primary">Send</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>        
        </div>
    </div>
</div>