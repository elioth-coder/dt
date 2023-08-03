<div class="modal fade" id="ProjectTasksModal" tabindex="-1" aria-labelledby="ProjectTasksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    Set/Get Tasks on Project
                    <i class="bi bi-plus-lg"></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ProjectTasksForm" method="post">
                    <section class="p-3 bg-light" style="clear: both;">
                        <input id="SearchTasks" placeholder="Search available tasks." class="form-control" type="search" name="">
                    </section>
                    <div style="box-sizing: border-box; width: 50%; float: left;">
                        <h3 class="text-center text-secondary">Available Tasks</h3>
                        <div class="overflow-y-scroll" style="max-height: 50vh;">
                            <table id="TasksTable" class="table table-striped border">
                                <thead class="text-primary">
                                    <tr>
                                        <th style="position: sticky; top: 0;" class="bg-white text-center">ID</th>
                                        <th style="position: sticky; top: 0;" class="bg-white text-center">TASK</th>
                                        <th style="position: sticky; top: 0;" class="bg-white ">STATUS</th>
                                        <th style="position: sticky; top: 0;" class="bg-white text-center">
                                            <i class="bi bi-plus-lg"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div style="box-sizing: border-box; width: 50%; float: left;">
                        <h3 class="text-center text-secondary">Project Tasks</h3>
                        <div class="overflow-y-scroll" style="max-height: 50vh;">
                            <table id="SelectedTasksTable" class="table table-striped border">
                                <thead class="text-primary">
                                    <tr>
                                        <th style="position: sticky; top: 0;" class="bg-white text-center">ID</th>
                                        <th style="position: sticky; top: 0;" class="bg-white text-center">TASK</th>
                                        <th style="position: sticky; top: 0;" class="bg-white ">STATUS</th>
                                        <th style="position: sticky; top: 0;" class="bg-white text-center">
                                            <i class="bi bi-trash-fill"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>