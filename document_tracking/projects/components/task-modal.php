<div class="modal fade" id="TaskModal" tabindex="-1" aria-labelledby="TaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-2" id="TaskModalLabel">SET PROJECT TASKS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="TaskModalForm" method="post">
                    <section class="p-3 bg-light" style="clear: both;">
                        <input id="SearchTasks" placeholder="Search available tasks." class="form-control" type="search" name="">
                    </section>
                    <div style="box-sizing: border-box; width: 50%; float: left;">
                        <h3 class="text-center">AVAILABLE TASKS</h3>
                        <table id="TasksTable" class="table table-striped border">
                            <thead class="text-primary">
                            <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">TASK</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div style="box-sizing: border-box; width: 50%; float: left;">
                        <h3 class="text-center">PROJECT TASKS</h3>
                        <table id="SelectedTasksTable" class="table table-striped border">
                            <thead class="text-primary">
                            <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">TASK</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">
                                <i class="bi bi-trash-fill"></i>
                            </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>