var formElements = [
    department_name,
    submit,
    reset
];

function disableForm() {
    formElements.forEach(element => element.setAttribute('disabled', true));
}

function enableForm() {
    formElements.forEach(element => element.removeAttribute('disabled'));
}

departmentForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(departmentForm);

    let options = {
        container: AlertContainer,
        message: [
            `<img class='me-2' style='height: 25px;' src='../assets/images/spinner.gif' />`,
            ` Saving info...`,
        ].join("\n"),
        type: "info"
    };
    let alertWrapper = appendAlert(options);
    disableForm();
    try {
        let response = await fetch((department_id.value) ? 'update.php' : 'create.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw ({
                title: response.status,
                message: response.statusText,
            });
        }

        let { status, message } = await response.json();

        alertWrapper.remove();

        if (status == 'success') {
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: message,
                timer: 1000,
                showConfirmButton: false,
            }).then(async () => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: message,
            }).then(() => {
                enableForm();
            });
        }
    } catch (error) {
        alertWrapper.remove();
        Swal.fire({
            icon: "error",
            title: error.title,
            text: error.message,
        }).then(() => {
            enableForm();
        });
    }
}

cancel.onclick = () => {
    reset.style = '';
    reset.click();
    department_id.value = '';
    clearDepartmentProfile();
    cancel.style.display = 'none';

    FormModal.querySelector('.btn-close').click();
}

CameraButton.onclick = () => {
    Swal.fire({
        icon: 'info',
        title: 'Set department profile',
        showDenyButton: true,
        confirmButtonText: 'Take selfie',
        denyButtonText: 'Upload picture',
    }).then(async (result) => {
        if (result.isConfirmed) {
            takePicture();
        }
        if (result.isDenied) {
            uploadPicture();
        }
    });
}

function openCamera() {
    Webcam.set({
        width: 320,
        height: 240,
        dest_width: 640,
        dest_height: 480,
        image_format: 'png',
        flip_horiz: true,
    });
    Webcam.attach('#snapshotPreview');
}

function closeCamera() {
    if (document.querySelector('#snapshotPreview video')) {
        Webcam.reset();
    }
}

function takeSnapshot() {
    Webcam.snap(function (data_uri) {
        document.querySelector('#imageToCrop').src = data_uri;
        document.querySelector('#imagePreview').src = data_uri;
    });

    closeCamera();
}

function createCropper() {
    const image = Swal.getPopup().querySelector('#imageToCrop')
    const cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 1,
        width: 200,
        height: 200,
        crop: throttle(function () {
            const croppedCanvas = cropper.getCroppedCanvas()
            const preview = Swal.getHtmlContainer().querySelector('#imagePreview')
            preview.setAttribute('src', croppedCanvas.toDataURL())
        }, 25)
    });
}

function previewPhoto(input) {
    let file = input.files[0];

    imageToCrop.setAttribute('src', URL.createObjectURL(file));
    imagePreview.setAttribute('src', URL.createObjectURL(file));
    ImageCropper.style.display = 'block';
    PictureBrowser.style.display = 'none';

    createCropper();
}

function capturePhoto() {
    takeSnapshot();
    ImageCropper.style.display = 'block';
    SnapshotTaker.style.display = 'none';
    
    createCropper();
}

function setDepartmentProfile() {
    let dataUrl = imagePreview.src;
    avatar.setAttribute('src', dataUrl);
    profile.value = imagePreview.src;
    ClearDepartmentProfile.style.display = 'block';
    Swal.close();
}

function viewImage(image) {
    Swal.fire({
        title: " ",
        showCancelButton: false,
        showConfirmButton: false,
        showCloseButton: true,
        html: [
            `<div>`,
            `   <img style="height: 400px;" src="${image}">`,
            `</div>`,
        ].join("\n")
    });
}

ClearDepartmentProfile.onclick = () => {
    Swal.fire({
        icon: 'warning',
        title: 'Remove current profile?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
    }).then(async (result) => {
        if (result.isConfirmed) {
            clearDepartmentProfile();
        }
    })
}

function clearDepartmentProfile() {
    avatar.setAttribute('src', '../upload/banner.png');
    profile.value = "";
    ClearDepartmentProfile.style.display = 'none';
}

function discardDepartmentProfile() {
    Swal.close();
}

function takePicture() {
    Swal.fire({
        title: 'Take a picture',
        showCancelButton: false,
        showConfirmButton: false,
        html: [
            `<div id="SnapshotTaker">`,
            `   <div id="snapshotPreview"></div><br>`,
            `   <button onclick="capturePhoto();" class="btn btn-outline-success btn-lg">`,
            `       <i class="bi-camera-fill"></i> Capture`,
            `   </button>`,
            `</div>`,
            `<div id="ImageCropper" style="display: none;">`,
            `   <img id="imagePreview" src="">`,
            `   <div>`,
            `       <img id="imageToCrop" src="">`,
            `   </div><br>`,
            `   <button onclick="setDepartmentProfile();" class="btn btn-outline-success btn-lg">`,
            `       <i class="bi-save-fill"></i> Save`,
            `   </button>`,
            `   <button onclick="discardDepartmentProfile();" class="btn btn-outline-danger btn-lg">`,
            `       <i class="bi-trash-fill"></i> Discard`,
            `   </button>`,
            `</div>`,
        ].join("\n"),
        didRender: () => {
            openCamera();
        }
    }).then((result) => {
        if (result.isDismissed) {
            closeCamera();
        }
    })
}

function uploadPicture() {
    Swal.fire({
        title: 'Upload a picture',
        showCancelButton: false,
        showConfirmButton: false,
        html: [
            `<div id="PictureBrowser" class="py-5">`,
            `   <button onclick="BrowsePicture.click();" class="btn btn-outline-dark btn-lg">`,
            `       <i class="bi-image-fill"></i> Browse picture`,
            `   </button>`,
            `   <input id="BrowsePicture" onchange="previewPhoto(this);" type="file" style="display: none;">`,
            `</div>`,
            `<div id="ImageCropper" style="display: none;">`,
            `   <img id="imagePreview" src="">`,
            `   <div>`,
            `       <img id="imageToCrop" src="">`,
            `   </div><br>`,
            `   <button onclick="setDepartmentProfile()" class="btn btn-outline-success btn-lg">`,
            `       <i class="bi-save-fill"></i> Save`,
            `   </button>`,
            `   <button onclick="discardDepartmentProfile();" class="btn btn-outline-danger btn-lg">`,
            `       <i class="bi-trash-fill"></i> Discard`,
            `   </button>`,
            `</div>`,
        ].join("\n"),
    }).then((result) => {
        if (result.isDismissed) {
            closeCamera();
        }
    })
}

function deleteDepartment(id) {
    Swal.fire({
        icon: 'warning',
        title: 'Do you want to delete this department?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
    }).then(async (result) => {
        if (result.isConfirmed) {
            let options = {
                container: ToastContainer,
                message: [
                    `<img class='me-2' style='height: 20px;' src='../assets/images/spinner.gif' />`,
                    ` Deleting department...`,
                ].join("\n")
            };
            let toastWrapper = appendToast(options);
            let response = await fetch('delete.php?id=' + id);
            let { status, message } = await response.json();

            toastWrapper.remove();
            Swal.fire({
                icon: status,
                title: message
            });

            if (status == 'success') {
                departments = await fetchDepartments();
                populateDepartmentsTable(departments);
            }
        }
    })
}

function editDepartment(id) {
    let department = departments.filter(j => j.id == id)[0];

    profile.value = department.profile;
    avatar.setAttribute('src', '../upload/' + department.profile);
    department_id.value = department.id;
    department_name.value = department.name;

    cancel.style = '';
    reset.style.display = 'none';
    ClearDepartmentProfile.style.display = 'block';

    OpenModalButton.click();
}

async function fetchDepartments() {
    let response = await fetch('read.php');
    let { rows } = await response.json();

    return rows;
}

async function searchDepartments(q) {
    let response = await fetch('search.php?q=' + q);
    let { rows } = await response.json();

    return rows;
}

function populateDepartmentsTable(departments) {
    let tbody = DepartmentsTable.querySelector('tbody');
    let content = "";

    if (departments.length) {
        departments.forEach(department => {
            content += [
                `<tr>`,
                `<td style="width: 40px;" class="text-center">`,
                `    <input onchange="selectRow(this);" value="${department.id}" `,
                `        class="form-check-input" type="checkbox" />`,
                `</td>`,
                `<td style="width: 160px;" class="text-center">`,
                `    <button onclick="editDepartment(${department.id})" class="btn btn-outline-success">`,
                `        <i class="bi-pencil-fill"></i>`,
                `    </button>`,
                `    <button onclick="deleteDepartment(${department.id})" class="btn btn-outline-danger">`,
                `        <i class="bi-trash-fill"></i>`,
                `    </button>`,
                `</td>`,
                `<td class="text-end">${department.id}</td>`,
                `<td class="text-center">`,
                `    <img onclick="viewImage('../upload/${department.profile}');" class="rounded-circle" style="cursor: pointer; height: 50px; width: 50px;" src="../upload/${department.profile}" />`,
                `</td>`,
                `<td>${department.name}</td>`,
                `<td class="text-center">`,
                `   <a href="#" onclick="showMembersModal(${department.id});">${department.members}</a>`,
                `</td>`,
                `</tr>`,
            ].join("\n");
        });
    } else {
        content += `
            <tr><td colspan="5" class="text-center">No data found</td></tr>
        `;
    }

    tbody.innerHTML = content;
}

function selectRow(checkbox) {
    if(checkbox.checked) {
        selectedRows.push(checkbox.value);
    } else {
        let index = selectedRows.indexOf(checkbox.value);
        selectedRows.splice(index, 1);
    }

    if(selectedRows.length) {
        DeleteSelected.style.display = 'inline-block';
    } else {
        DeleteSelected.style.display = 'none';
    }
}

CheckAll.onclick = () => {
    let checkboxes = DepartmentsTable.querySelectorAll('input[type="checkbox"]');
    let isChecked = CheckAll.checked;

    if(isChecked) {
        checkboxes.forEach(c => {
            if(!c.checked) c.click();
        });    
    } else {
        checkboxes.forEach(c => {
            if(c.checked) c.click();
        });    
    }
}

DeleteSelected.onclick = () => {
    Swal.fire({
        icon: 'warning',
        title: 'Do you want to delete all the selected departments?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
    }).then(async (result) => {
        if (result.isConfirmed) {
            DeleteSelected.setAttribute('disabled', true);
            let options = {
                container: ToastContainer,
                message: [
                    `<img class='me-2' style='height: 20px;' src='../assets/images/spinner.gif' />`,
                    ` Deleting selected departments...`,
                ].join("\n")
            };
            let toastWrapper = appendToast(options);
            let response = await fetch('delete-multiple.php?id=' + selectedRows.join(","));
            let { status, message } = await response.json();

            toastWrapper.remove();
            Swal.fire({
                icon: status,
                title: message
            });

            if (status == 'success') {
                departments = await fetchDepartments();
                populateDepartmentsTable(departments);
                DeleteSelected.style.display = 'none';
            }
            DeleteSelected.removeAttribute('disabled');
        }
    });
}

function logout() {
    Swal.fire({
        html: [
            `<p class="text-center">`,
            `   <img style="height: 100px;" src='../assets/images/spinner.gif' />`,
            `</p>`,
        ].join("\n"),
        title: "Logging out...",
        timer: 3000,
        showConfirmButton: false,
    }).then(async () => {
        window.location.href = "../process/logout.php";
    });
}

search.onkeyup = async (e) => {
    let q = e.target.value;
    
    departments = await searchDepartments(q);
    populateDepartmentsTable(departments);
}

cancel.style.display = 'none';
ClearDepartmentProfile.style.display = 'none';
DeleteSelected.style.display = 'none';

var selectedRows = [];
var departments = [];

(async () => {
    departments = await fetchDepartments();
    populateDepartmentsTable(departments);
})();