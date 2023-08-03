
async function searchTasks(projectId, q="") {
    let response = await fetch(`search-tasks.php?project_id=${projectId}&q=${q}`);
    let { rows } = await response.json();

    return rows;
}

async function fetchTasks(projectId, selected=true) {

    let response = await fetch(`fetch-tasks.php?project_id=${projectId}${(selected==false) ? "&not_selected" : ""}`);
    let { rows } = await response.json();

    return rows;
}

async function showTasksModal(projectId) {
    projectTasksModal.show();
    let selectedTasks  = await fetchTasks(projectId);
    let availableTasks = await fetchTasks(projectId, false);

    populateAvailableTasksTable(projectId, availableTasks);
    populateSelectedTasksTable(projectId, selectedTasks);
}

const projectTasksModal = new bootstrap.Modal('#ProjectTasksModal');

ProjectTasksModal.addEventListener('hide.bs.modal', async event => {
    ProjectTasksModalForm.querySelector('table tbody').innerHTML = "";
});

async function addProjectTask(button, projectId, taskId) {
    let formData = new FormData();
    formData.append('project_id', projectId);
    formData.append('task_id', taskId);

    let loader = button.previousElementSibling;
    let check  = button.nextElementSibling;
    loader.style.display = 'inline-block';
    button.style.display = 'none';

    let response = await fetch('add-task.php', {
        method: 'POST',
        body: formData,
    });

    let { status } = await response.json();

    if(status=='success') {
        loader.style.display = 'none';
        check.style.display = 'inline-block';
        let selectedTasks = await fetchTasks(projectId);
        populateSelectedTasksTable(projectId, selectedTasks);
        let tr = button.parentNode.parentNode;
        tr.remove();

        let projects = await fetchProjects();
        populateProjectsTable(projects);
    }
}

async function removeProjectTask(button, projectId, taskId) {
    let formData = new FormData();
    formData.append('project_id', projectId);
    formData.append('task_id', taskId);

    let loader = button.previousElementSibling;
    let check  = button.nextElementSibling;
    loader.style.display = 'inline-block';
    button.style.display = 'none';

    let response = await fetch('remove-task.php', {
        method: 'POST',
        body: formData,
    });

    let { status } = await response.json();

    if(status=='success') {
        loader.style.display = 'none';
        check.style.display = 'inline-block';
        let tasks = await searchTasks(projectId);
        populateAvailableTasksTable(projectId, tasks);
        let tr = button.parentNode.parentNode;
        tr.remove();
        
        let projects = await fetchProjects();
        populateProjectsTable(projects);
    }
}

function populateAvailableTasksTable(projectId, tasks) {
    let content = "";

    tasks.forEach(task => {
        content += [
            `<tr>`,
            `   <td class="text-center">${task.id}</td>`,
            `   <td>${task.name}</td>`,
            `<td class="">`,
            `   <span class="badge text-bg-${STATUS_COLOR2[task.status]}">${task.status}</span>`,
            `</td>`,
            `   <td class="text-center">`,
            `       <button type="button" disabled style="display: none;" class="btn btn-outline-secondary">`,
            `           <img src="../assets/images/loader.gif" style="height: 16px; margin-top: -5px;" />`,
            `       </button>`,
            `       <button type="button" onclick="addProjectTask(this, ${projectId},${task.id});" class="btn btn-outline-primary">`,
            `           <i class="bi bi-plus-lg"></i>`,
            `       </button>`,
            `       <button type="button" disabled style="display: none;" class="btn btn-outline-success">`,
            `           <i class="bi bi-check-lg"></i>`,
            `       </button>`,
            `   </td>`,            
            `</tr>`,
            ``,
        ].join("\n");
    });

    if(!tasks.length) {
        content = [
            `<tr><td colspan="5" class="text-center">No data found.</td></tr>`,
        ].join("\n");
    }

    TasksTable.querySelector('tbody').innerHTML = content;
}

function populateSelectedTasksTable(projectId, tasks) {
    let content = "";

    tasks.forEach(task => {
        content += [
            `<tr>`,
            `   <td class="text-center">${task.id}</td>`,
            `   <td>${task.name}</td>`,
            `   <td class="">`,
            `       <span class="badge text-bg-${STATUS_COLOR2[task.status]}">${task.status}</span>`,
            `   </td>`,
            `   <td class="text-center">`,
            `       <button type="button" disabled style="display: none;" class="btn btn-outline-secondary">`,
            `           <img src="../assets/images/loader.gif" style="height: 16px; margin-top: -5px;" />`,
            `       </button>`,
            `       <button type="button" onclick="removeProjectTask(this, ${projectId},${task.id});" class="btn btn-outline-primary">`,
            `           <i class="bi bi-trash-fill"></i>`,
            `       </button>`,
            `       <button type="button" disabled style="display: none;" class="btn btn-outline-success">`,
            `           <i class="bi bi-check-lg"></i>`,
            `       </button>`,
            `   </td>`,            
            `</tr>`,
            ``,
        ].join("\n");
    });

    if(!tasks.length) {
        content = [
            `<tr><td colspan="5" class="text-center">No data found.</td></tr>`,
        ].join("\n");
    }

    SelectedTasksTable.querySelector('tbody').innerHTML = content;
}

async function setTasks(projectId) {
    projectModal.hide();
    projectTasksModal.show();
    SearchTasks.setAttribute('project_id', projectId);
    let tasks = await searchTasks(projectId);
    populateAvailableTasksTable(projectId, tasks);
    let selectedTasks = await fetchTasks(projectId);
    populateSelectedTasksTable(projectId, selectedTasks);
}

SearchTasks.onkeyup = async (e) => {
    let q = e.target.value;
    let projectId = e.target.getAttribute('project_id');
    let tasks = await searchTasks(projectId, q);

    populateAvailableTasksTable(projectId, tasks);
}