<div class="modal fade" id="MemberModal" tabindex="-1" aria-labelledby="MemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-2" id="MemberModalLabel">SET DEPARTMENT MEMBERS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="MemberModalForm" method="post">
                    <section class="p-3 bg-light" style="clear: both;">
                        <input id="SearchMembers" placeholder="Search available members." class="form-control" type="search" name="">
                    </section>
                    <div style="box-sizing: border-box; width: 50%; float: left;">
                        <h3 class="text-center">AVAILABLE MEMBERS</h3>
                        <table id="MembersTable" class="table table-striped border">
                            <thead class="text-primary">
                            <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">PROFILE</th>
                            <th>FULL NAME</th>
                            <th>GENDER</th>
                            <th class="text-center">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div style="box-sizing: border-box; width: 50%; float: left;">
                        <h3 class="text-center">DEPARTMENT MEMBERS</h3>
                        <table id="SelectedMembersTable" class="table table-striped border">
                            <thead class="text-primary">
                            <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">PROFILE</th>
                            <th>FULL NAME</th>
                            <th>GENDER</th>
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