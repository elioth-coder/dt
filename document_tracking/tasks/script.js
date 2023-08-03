var formElements = [
    tasker,
    task_name,
    remarks ,
];

function disableForm() {
    formElements.forEach(element => element.setAttribute('disabled', true));
}

function enableForm() {
    formElements.forEach(element => element.removeAttribute('disabled'));
}

NewTaskForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(NewTaskForm);

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

const markAsDoneModal = new bootstrap.Modal('#MarkAsDoneModal', {
    keyboard: false
});

async function markAsDone(task) {
    let department_id = (task.department_id) ? task.department_id : task.department_id_user;

    MarkAsDoneForm.querySelector('input[name="task_id"]').value = task.id;
    MarkAsDoneForm.querySelector('input[name="department_id"]').value = department_id;
    markAsDoneModal.show();
}

MarkAsDoneForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(MarkAsDoneForm);

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

function triggerPopover() {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
}

function triggerTooltips() { 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

$(document).ready(function () {
    $('#DataTableTask').DataTable({
        paging: false,
        scrollCollapse: true,
        scrollY: '50vh',
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ],
        language: {
            emptyTable: "No results found."
        }
    });
});

$(function () {
    $("#tasker").selectize({
        placeholder: "Type the name of tasker."
    });
});

triggerTooltips();
triggerPopover();