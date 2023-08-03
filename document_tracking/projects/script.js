var formElements = [
    department_id,
    project_name,
    date_started,
    deadline,
    submit,
    reset
];

var BACKGROUNDS = {
    'CREATED'     : "bg-info",
    'COMPLETED'   : "bg-success",
    'IN-PROGRESS' : "bg-warning",
}

function disableForm() {
    formElements.forEach(element => element.setAttribute('disabled', true));
}

function enableForm() {
    formElements.forEach(element => element.removeAttribute('disabled'));
}

projectForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(projectForm);

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

function deleteProject(id) {
    Swal.fire({
        icon: 'warning',
        title: 'Do you want to delete this project?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
    }).then(async (result) => {
        if (result.isConfirmed) {
            let options = {
                container: ToastContainer,
                message: [
                    `<img class='me-2' style='height: 20px;' src='../assets/images/spinner.gif' />`,
                    ` Deleting project...`,
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
                projects = await fetchProjects();
                populateProjectsTable(projects);
            }
        }
    })
}

async function fetchProjects() {
    let response = await fetch('read.php');
    let { rows } = await response.json();

    return rows;
}

async function searchProjects(q) {
    let response = await fetch('search.php?q=' + q);
    let { rows } = await response.json();

    return rows;
}

function populateProjectsTable(projects) {
    let tbody = ProjectsTable.querySelector('tbody');
    let content = "";

    if (projects.length) {
        projects.forEach(project => {
            content += [
                `<tr>`,
                `<td class="text-center">`,
                `   <button onclick="showTasksModal(${project.id});" class="btn btn-success"`,
                `       data-bs-title="Add tasks" data-bs-toggle="tooltip" data-bs-placement="top">`,
                `       <i class="bi bi-plus-square"></i>`,
                `   </button>`,
                `   <a class="btn btn-info" href="./view.php?project_id=${project.id}"`,
                `       data-bs-title="View project" data-bs-toggle="tooltip" data-bs-placement="top">`,
                `       <i class="bi bi-eye-fill"></i>`,
                `   </a>`,
                `</td>`,
                `<td style="width: 190px;" class="text-center">${project.date_started}</td>`,
                `<td style="width: 170px;" class="text-center">`,
                `   <span class="fs-6 p-2 d-block w-100 badge ${BACKGROUNDS[project.status]}">${project.status}</span>`,
                `</td>`,
                `<td>${project.name}</td>`,
                `<td class="text-center">${project.deadline}</td>`,
                `<td class="text-center">${project.tasks}</td>`,
                `</tr>`,
            ].join("\n");
        });
    } else {
        content += `
            <tr><td colspan="6" class="text-center">No data found</td></tr>
        `;
    }

    tbody.innerHTML = content;
    triggerTooltips();
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

        projects = await searchProjects(q);
        populateProjectsTable(projects);
    }
}


function triggerTooltips() { 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

var selectedRows = [];
var projects = [];

(async () => {
    if (document.getElementById('ProjectsTable')) {
        projects = await fetchProjects();
        populateProjectsTable(projects);
    }
})();

triggerTooltips();