<div class="modal fade" id="NewUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    New User
                    <i class="bi bi-plus-lg"></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="NewUserForm" method="POST">
                    <input type="hidden" name="id" id="user_id">
                    <input type="hidden" name="profile" id="profile">
                    <div id="AlertContainer"></div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-5 text-center position-relative">
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
                                #ClearUserProfile {
                                    width: 30px; height: 30px;
                                    color: #555;
                                    background-color: white;
                                }
                                #ClearUserProfile:hover {
                                    color: #999;
                                    background-color: #eee;
                                }
                                </style>
                                <div class="position-relative d-inline-block w-auto">
                                    <a id="ClearUserProfile" href="#" 
                                        class="border position-absolute top-0 end-0 p-1 rounded-circle">
                                        <i class="bi-x"></i>
                                    </a>
                                    <img id="avatar" src="../upload/profile.png" 
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
                                <input placeholder="Enter first name."
                                    required type="text" class="form-control" 
                                    name="first_name" id="first_name"
                                />
                            </div>
                            <div class="mb-3">
                                <input placeholder="Enter last name."
                                    required type="text" class="form-control" 
                                    name="last_name" id="last_name"
                                />
                            </div>
                            <div class="mb-3">
                                <input placeholder="Enter birthday."
                                    title="Enter birthday."
                                    required type="date" class="form-control" 
                                    name="birthday" id="birthday"
                                />
                            </div>
                            <div class="mb-3 p-2">
                                <div class="form-check form-check-inline">
                                    <input required class="form-check-input" type="radio" name="gender" id="male" value="MALE">
                                    <label class="form-check-label" for="male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input required class="form-check-input" type="radio" name="gender" id="female" value="FEMALE">
                                    <label class="form-check-label" for="female">
                                        Female
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="department" class="form-label">Select department.</label>
                                <select required class="form-control" 
                                    style="height: 119px;"
                                    name="department[]" id="department" multiple>
                                    <?php
                                    foreach($departments as $department) { ?>
                                        <option value="<?php echo $department['id'];  ?>">
                                        <?php echo $department['name']; ?>
                                        </option>
                                    <?php
                                    } // end of foreach..
                                    ?>
                                </select>
                            </div>    
                            <div class="mb-3">
                                <select required class="form-control" name="role" id="role">
                                    <option value="">Select role.</option>
                                    <option value="USER">USER</option>
                                    <option value="ADMIN">ADMIN</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input placeholder="Enter email."
                                    required type="email" class="form-control" 
                                    name="email" id="email"
                                />
                            </div>
                            <div class="mb-3">
                                <input placeholder="Enter username."
                                    required type="text" class="form-control" 
                                    name="username" id="username"
                                />
                            </div>
                            <div class="mb-3">
                                <input placeholder="Enter password."
                                    required type="password" class="form-control" 
                                    name="password" id="password"
                                />
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="d-none"></button>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="NewUserForm.querySelector('button').click();" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>