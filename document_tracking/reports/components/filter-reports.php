<div class="card m-3 overflow-hidden bg-info-subtle py-2">
    <table class="table table-borderless table-sm mb-0">
        <tbody>
            <tr>
                <th class="text-center align-middle text-secondary align-middle">
                    <label for="department_id">Department</label>
                </th>
                <th>
                    <select id="department_id" class="form-control">
                        <option value="">Select department.</option>
                        <?php
                        foreach ($departments as $department) { ?>
                            <option value="<?php echo $department['id']; ?>">
                                <?php echo $department['name']; ?>
                            </option>
                        <?php
                        } // end of foreach..
                        ?>
                    </select>
                </th>
                <th class="text-center align-middle text-secondary">
                    <label for="from">From</label>
                </th>
                <th>
                    <input type="date" id="from" 
                        class="form-control" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        data-bs-title="Start date." />
                </th>
                <th rowspan="2" class="align-middle text-center">
                    <button type="button" id="generate"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        data-bs-title="Generate Report"
                        class="btn btn-success btn-lg p-3 shadow">
                        Generate
                        <i class="bi bi-dash-circle-dotted"></i>
                    </button>
                </th>
            </tr>
            <tr>
                <th class="text-center align-middle text-secondary">
                    <label for="doctype">Doctype</label>
                </th>
                <th>
                    <select id="doctype" class="form-control">
                        <option value="">Select document type.</option>
                        <?php
                        foreach ($doc_types as $doc_type) { ?>
                            <option value="<?php echo $doc_type; ?>">
                                <?php echo $doc_type; ?>
                            </option>
                        <?php
                        } // end of foreach..
                        ?>
                    </select>
                </th>
                <th class="text-center align-middle text-secondary">
                    <label for="from">To</label>
                </th>
                <th>
                    <input type="date" id="to" 
                        class="form-control" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        data-bs-title="End date." />
                </th>
            </tr>
        </tbody>
    </table>
</div>