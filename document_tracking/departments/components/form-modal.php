<div class="modal fade" id="FormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-2" id="exampleModalLabel">Create new department</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="departmentForm" method="POST">
                    <input type="hidden" name="id" id="department_id">
                    <input type="hidden" name="profile" id="profile">
                    <div id="AlertContainer"></div>
                    <div class="mb-3 text-center position-relative">
                        <style>
                        #CameraButton {
                            display: block;
                            color: white;
                            background-color: black;
                        }
                        #CameraButton:hover {
                            color: black;
                            background-color: white;
                        }
                        #ClearDepartmentProfile {
                            width: 30px; height: 30px;
                            color: #555;
                            background-color: white;
                        }
                        #ClearDepartmentProfile:hover {
                            color: #999;
                            background-color: #eee;
                        }
                        </style>
                        <div class="position-relative d-inline-block w-auto">
                            <a id="ClearDepartmentProfile" href="#" 
                                class="border position-absolute top-0 end-0 p-1 rounded-circle">
                                <i class="bi-x"></i>
                            </a>
                            <img id="avatar" src="../upload/banner.png" 
                                class="rounded-circle border shadow" 
                                style="height: 120px;"
                            />
                            <a id="CameraButton" href="#"
                                style="height:42px; width:42px;"
                                class="border opacity-50 position-absolute bottom-0 end-0 rounded-circle p-2">
                                <i class="bi-camera-fill"></i>
                            </a>
                        </div>

                    </div>
                    <div class="mb-3">
                        <input placeholder="Enter name of department."
                            required type="text" class="form-control" 
                            name="department_name" id="department_name"
                        />
                    </div>
                    <hr>
                    <div class="float-end">
                        <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                        <button id="reset" type="reset" class="btn btn-secondary">Reset</button>
                        <button id="cancel" type="reset" class="btn btn-danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>