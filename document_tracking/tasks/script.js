var formElements = [
    tasker,
    task_name,
    remarks ,
    submit,
    reset
];

var BACKGROUNDS = {
    'RE-ASSIGNED' : "bg-info",
    'DONE'        : "bg-success",
    'ASSIGNED'    : "bg-info",
    'IN-PROGRESS' : "bg-warning",
    'COMPLETED'   : "bg-danger",
}

function disableForm() {
    formElements.forEach(element => element.setAttribute('disabled', true));
}

function enableForm() {
    formElements.forEach(element => element.removeAttribute('disabled'));
}

taskForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(taskForm);

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
        let response = await fetch('create.php', {
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
    task_id.value = '';
    cancel.style.display = 'none';
}

function deleteTask(id) {
    Swal.fire({
        icon: 'warning',
        title: 'Do you want to delete this task?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
    }).then(async (result) => {
        if (result.isConfirmed) {
            let options = {
                container: ToastContainer,
                message: [
                    `<img class='me-2' style='height: 20px;' src='../assets/images/spinner.gif' />`,
                    ` Deleting task...`,
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
                tasks = await fetchTasks();
                populateTasksTable(tasks);
            }
        }
    })
}

function editTask(id) {
    let task = tasks.filter(j => j.id == id)[0];

    task_id.value = task.id;
    task_name.value = task.name;

    cancel.style = '';
    reset.style.display = 'none';
}

const taskHistoryModal = new bootstrap.Modal('#TaskHistoryModal', {
    keyboard: false
});

async function viewTaskHistory(task) {
    taskHistoryModal.show();

    let response = await fetch('fetch-history.php?task_id=' + task.id);
    let { status, message, rows, creator } = await response.json();
    
    if(status == 'success') {
        let tbodyContent = "";
        rows.forEach(row => {
            let department = (row.tasker_type=='DEPARTMENT') 
                ?   row.department 
                :   row.user_department;

            tbodyContent += [
                `<tr>`,
                `<td class="position-relative" style="width: 50px; border-right: 2px solid #0D6EFD">`,
                `   <div class="bg-secondary-subtle position-absolute end-0 rounded-circle" `,
                `       style="border: 2px solid #0D6EFD; margin-right: -16px; width: 30px; height: 30px;"></div>`,
                `</td>`,
                `<td style="width: 50px;"></td>`,
                `<td style="width: 190px;" class="text-center">${row.datetime}</td>`,
                `<td style="width: 170px;" class="text-center">`,
                `   <span class="fs-6 p-2 d-block w-100 badge ${BACKGROUNDS[row.status]}">${row.status}</span>`,
                `</td>`,
                `<td class="text-center">${department}</td>`,
                `<td>`,
                `   <p>${row.remarks}</p>`,
                (row.attachments.length) 
                    ? `<p>Attachments: ${row.attachments.map(file => `<a target="_blank" href="./files/${file.generated_name}" download="${file.filename}">${file.filename}</a>`).join(", ")}</p>` 
                    : "",
                `</td>`,
                `<td>${row.actor_department}<br> - ${row.actor}</td>`,
                `</tr>`,
            ].join("\n");
        });

        let table = [
            `<table class="table table-striped table-bordered">`,
            `   <thead>`,
            `   <tr>`,
            `   <td class="position-relative" style="width: 50px; border-right: 2px solid #0D6EFD"></td><td></td>`,
            `   <th class="text-center text-primary">DATETIME</th>`,
            `   <th class="text-center text-primary">STATUS</th>`,
            `   <th class="text-center text-primary">DEPARTMENT</th>`,
            `   <th class="text-primary">REMARKS</th>`,
            `   <th class="text-primary">BY</th>`,
            `   </tr>`,
            `   </thead>`,
            `   <tbody>`,
                    tbodyContent,
            `   <tbody>`,
            `</table>`,
        ].join("\n");

        let modalBodyContent = [
            `<table class="table table-bordered">`,
            `<tr>`,
            `   <th style="width: 190px;" class="bg-primary text-white text-center align-middle">TASK</th>`,
            `   <td>`,
            `       <h4 class="text-primary">${task.task_name}</h4>`,
            `       <i>Deadline on: ${task.deadline}</i><br>`,
            `       <i>Assigned by: ${creator.first_name} ${creator.last_name} - ${creator.department.name}</i>`,
            `   </td>`,
            `</tr>`,    
            `</table>`,
            `<hr>`,
            table,
        ].join("\n");
        
        TaskHistoryModal.querySelector('.modal-body').innerHTML = modalBodyContent;
    } else {
        TaskHistoryModal.querySelector('.modal-body').innerHTML = [
            `<h3 class="text-center text-danger">${message}</h3>`,
        ].join("\n");
    }
}

async function startTask(task) {
    let result = await Swal.fire({
        title: 'Start working on this task?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!',
        cancelButtonText: 'No!'
    });

    if (result.isConfirmed) {
        const { value: remarks } = await Swal.fire({
            input: 'textarea',
            inputLabel: 'Remarks',
            inputPlaceholder: 'Type your remarks here...',
            inputAttributes: {
                'aria-label': 'Type your remarks here'
            },
            confirmButtonText: 'Finish',
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        let formData = new FormData();
            formData.append('id', task.id);
            formData.append('remarks', remarks);
            let department_id = (task.department_id) ? task.department_id : task.department_id_user;

            formData.append('department_id', department_id);

        let response = await fetch('start.php', {
            method: 'POST',
            body: formData
        });

        let { status, message } = await response.json();

        if(status == 'success') {
            await Swal.fire({ 
                icon: 'success', 
                title: message, 
                timer: 2000,
                showConfirmButton: false,
            });
            window.location.reload();
        } else {
            await Swal.fire({ icon: 'error', title: message });
        }
    }
}

async function reAssignTask(task) {
    let result = await Swal.fire({
        title: 'Re-assign this task?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!',
        cancelButtonText: 'No!'
    });

    if (result.isConfirmed) {
        const { value: remarks } = await Swal.fire({
            input: 'textarea',
            inputLabel: 'Remarks',
            inputPlaceholder: 'Type your remarks here...',
            inputAttributes: {
                'aria-label': 'Type your remarks here'
            },
            confirmButtonText: 'Finish',
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        let formData = new FormData();
            formData.append('id', task.id);
            formData.append('remarks', remarks);
            let department_id = (task.department_id) ? task.department_id : task.department_id_user;

            formData.append('department_id', department_id);

        let response = await fetch('re-assign.php', {
            method: 'POST',
            body: formData
        });

        let { status, message } = await response.json();

        if(status == 'success') {
            await Swal.fire({ 
                icon: 'success', 
                title: message, 
                timer: 2000,
                showConfirmButton: false,
            });
            window.location.reload();
        } else {
            await Swal.fire({ icon: 'error', title: message });
        }
    }
}

async function markTaskAsComplete(task) {
    let result = await Swal.fire({
        title: 'Mark task as complete?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!',
        cancelButtonText: 'No!'
    });

    if (result.isConfirmed) {
        const { value: remarks } = await Swal.fire({
            input: 'textarea',
            inputLabel: 'Remarks',
            inputPlaceholder: 'Type your remarks here...',
            inputAttributes: {
                'aria-label': 'Type your remarks here'
            },
            confirmButtonText: 'Finish',
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        let formData = new FormData();
            formData.append('id', task.id);
            formData.append('remarks', remarks);
            let department_id = (task.department_id) ? task.department_id : task.department_id_user;

            formData.append('department_id', department_id);

        let response = await fetch('complete.php', {
            method: 'POST',
            body: formData
        });

        let { status, message } = await response.json();

        if(status == 'success') {
            await Swal.fire({ 
                icon: 'success', 
                title: message, 
                timer: 2000,
                showConfirmButton: false,
            });
            window.location.reload();
        } else {
            await Swal.fire({ icon: 'error', title: message });
        }
    }
}


async function fetchTasks() {
    let response = await fetch('read.php');
    let { rows } = await response.json();

    return rows;
}

async function searchTasks(q) {
    let response = await fetch('search.php?q=' + q);
    let { rows } = await response.json();

    return rows;
}

function populateTasksTable(tasks) {
    let tbody = TasksTable.querySelector('tbody');
    let content = "";

    if (tasks.length) {
        tasks.forEach(task => {
            content += [
                `<tr>`,
                `<td style="width: 190px;" class="text-center">${task.datetime}</td>`,
                `<td style="width: 170px;" class="text-center">`,
                `   <span class="fs-6 p-2 d-block w-100 badge ${BACKGROUNDS[task.status]}">${task.status}</span>`,
                `</td>`,
                `<td>`,
                `   <h4>${task.name}</h4>`,
                (task.attachments.length) 
                    ? `<p>Attachments: ${task.attachments.map(file => `<a target="_blank" href="./files/${file.generated_name}" download="${file.filename}">${file.filename}</a>`).join(", ")}</p>` 
                    : "",
                `</td>`,
                `<td style="width: 160px;" class="text-center">`,
                `   <button data-bs-toggle="tooltip" data-bs-placement="top"`,
                `       data-bs-title="View"`,
                `       onclick='viewTaskHistory(${JSON.stringify(task)});' class="btn btn-outline-info">`,
                `       <i class="bi bi-eye-fill"></i>`,
                `   </button>`,
                `   <button ${(task.status == 'DONE') ? "" : 'style="display: none;'} data-bs-toggle="tooltip" data-bs-placement="top"`,
                `       data-bs-title="Re-Assign"`,
                `       onclick='reAssignTask(${JSON.stringify(task)});' class="btn btn-outline-danger">`,
                `       <i class="bi bi-box-arrow-in-right"></i>`,
                `   </button>`,
                `   <button ${(task.status == 'DONE') ? "" : 'style="display: none;'} data-bs-toggle="tooltip" data-bs-placement="top"`,
                `       data-bs-title="Mark as Completed"`,
                `       onclick='markTaskAsComplete(${JSON.stringify(task)});' class="btn btn-outline-danger">`,
                `       <i class="bi bi-check-square-fill"></i>`,
                `   </button>`,
                `</tr>`,
            ].join("\n");
        });
    } else {
        content += `
            <tr><td colspan="4" class="text-center">No data found</td></tr>
        `;
    }

    tbody.innerHTML = content;
    triggerTooltips();
}

const markAsDoneModal = new bootstrap.Modal('#MarkAsDoneModal', {
    keyboard: false
});

async function markAsDone(task) {
    let department_id = (task.department_id) ? task.department_id : task.department_id_user;

    MarkAsDoneTaskForm.querySelector('input[name="task_id"]').value = task.id;
    MarkAsDoneTaskForm.querySelector('input[name="department_id"]').value = department_id;
    markAsDoneModal.show();
}

MarkAsDoneTaskForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(MarkAsDoneTaskForm);

    let options = {
        container: ModalAlertContainer,
        message: [
            `<img class='me-2' style='height: 25px;' src='../assets/images/spinner.gif' />`,
            ` Forwarding document...`,
        ].join("\n"),
        type: "info"
    };
    let alertWrapper = appendAlert(options);

    try {
        let response = await fetch('done.php', {
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
            });
        }
    } catch (error) {
        alertWrapper.remove();
        Swal.fire({
            icon: "error",
            title: error.title,
            text: error.message,
        });
    }
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

if (document.getElementById('search')) {
    search.onkeyup = async (e) => {
        let q = e.target.value;

        tasks = await searchTasks(q);
        populateTasksTable(tasks);
    }
}

cancel.style.display = 'none';

$(function () {
    $("#tasker").selectize({
        placeholder: "Type the name of tasker."
    });
});

function triggerTooltips() { 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

var selectedRows = [];
var tasks = [];

(async () => {
    if (document.getElementById('TasksTable')) {
        tasks = await fetchTasks();
        populateTasksTable(tasks);
    }
})();

triggerTooltips();