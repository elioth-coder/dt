
async function searchMembers(departmentId, q="") {
    let response = await fetch(`search-members.php?department_id=${departmentId}&q=${q}`);
    let { rows } = await response.json();

    return rows;
}

async function fetchMembers(departmentId, selected=true) {

    let response = await fetch(`fetch-members.php?department_id=${departmentId}${(selected==false) ? "&not_selected" : ""}`);
    let { rows } = await response.json();

    return rows;
}


async function showMembersModal(departmentId) {
    SearchMembers.setAttribute('department_id', departmentId);
    departmentMembersModal.show();
    let selectedMembers  = await fetchMembers(departmentId);
    let availableMembers = await fetchMembers(departmentId, false);

    populateAvailableMembersTable(departmentId, availableMembers);
    populateSelectedMembersTable(departmentId, selectedMembers);
}

const departmentMembersModal = new bootstrap.Modal('#DepartmentMembersModal');

DepartmentMembersModal.addEventListener('hide.bs.modal', async event => {
    DepartmentMembersModalForm.querySelector('table tbody').innerHTML = "";
});

async function addDepartmentMember(button, departmentId, memberId) {
    let formData = new FormData();
    formData.append('department_id', departmentId);
    formData.append('user_id', memberId);

    let loader = button.previousElementSibling;
    let check  = button.nextElementSibling;
    loader.style.display = 'inline-block';
    button.style.display = 'none';

    let response = await fetch('add-member.php', {
        method: 'POST',
        body: formData,
    });

    let { status } = await response.json();

    if(status=='success') {
        loader.style.display = 'none';
        check.style.display = 'inline-block';
        let selectedMembers = await fetchMembers(departmentId);
        populateSelectedMembersTable(departmentId, selectedMembers);
        let tr = button.parentNode.parentNode;
        tr.remove();

        let departments = await fetchDepartments();
        populateDepartmentsTable(departments);
    }
}

async function removeDepartmentMember(button, departmentId, memberId) {
    let formData = new FormData();
    formData.append('department_id', departmentId);
    formData.append('user_id', memberId);

    let loader = button.previousElementSibling;
    let check  = button.nextElementSibling;
    loader.style.display = 'inline-block';
    button.style.display = 'none';

    let response = await fetch('remove-member.php', {
        method: 'POST',
        body: formData,
    });

    let { status } = await response.json();

    if(status=='success') {
        loader.style.display = 'none';
        check.style.display = 'inline-block';
        let members = await searchMembers(departmentId);
        populateAvailableMembersTable(departmentId, members);
        let tr = button.parentNode.parentNode;
        tr.remove();
        
        let departments = await fetchDepartments();
        populateDepartmentsTable(departments);
    }
}

function populateAvailableMembersTable(departmentId, members) {
    let content = "";

    members.forEach(member => {
        content += [
            `<tr>`,
            `   <td class="text-center">${member.id}</td>`,
            `   <td class="text-center">`,
            `       <img onclick="viewImage('../upload/${member.profile}');" class="rounded-circle" style="cursor: pointer; height: 50px; width: 50px;" src="../upload/${member.profile}" />`,
            `   </td>`,
            `   <td>${member.full_name}</td>`,
            `   <td class="text-center fs-4 text-${member.gender=='MALE' ? 'primary' : 'danger'}"`,
            `       style="width: 60px;"`,
            `       title="${member.gender}">`,
            `       <i class="bi bi-gender-${member.gender.toLowerCase()}"></i>`,
            `   </td>`,
            `   <td class="text-center">`,
            `       <button type="button" disabled style="display: none;" class="btn btn-outline-secondary">`,
            `           <img src="../assets/images/loader.gif" style="height: 16px; margin-top: -5px;" />`,
            `       </button>`,
            `       <button type="button" onclick="addDepartmentMember(this, ${departmentId},${member.id});" class="btn btn-outline-primary">`,
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

    if(!members.length) {
        content = [
            `<tr><td colspan="5" class="text-center">No data found.</td></tr>`,
        ].join("\n");
    }

    MembersTable.querySelector('tbody').innerHTML = content;
}

function populateSelectedMembersTable(departmentId, members) {
    let content = "";

    members.forEach(member => {
        content += [
            `<tr>`,
            `   <td class="text-center">${member.id}</td>`,
            `   <td class="text-center">`,
            `       <img onclick="viewImage('../upload/${member.profile}');" class="rounded-circle" style="cursor: pointer; height: 50px; width: 50px;" src="../upload/${member.profile}" />`,
            `   </td>`,
            `   <td>${member.full_name}</td>`,
            `   <td class="text-center fs-4 text-${member.gender=='MALE' ? 'primary' : 'danger'}"`,
            `       style="width: 60px;"`,
            `       title="${member.gender}">`,
            `       <i class="bi bi-gender-${member.gender.toLowerCase()}"></i>`,
            `   </td>`,
            `   <td class="text-center">`,
            `       <button type="button" disabled style="display: none;" class="btn btn-outline-secondary">`,
            `           <img src="../assets/images/loader.gif" style="height: 16px; margin-top: -5px;" />`,
            `       </button>`,
            `       <button type="button" onclick="removeDepartmentMember(this, ${departmentId},${member.id});" class="btn btn-outline-primary">`,
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

    if(!members.length) {
        content = [
            `<tr><td colspan="5" class="text-center">No data found.</td></tr>`,
        ].join("\n");
    }

    SelectedMembersTable.querySelector('tbody').innerHTML = content;
}

async function setMembers(departmentId) {
    departmentModal.hide();
    departmentMembersModal.show();
    SearchMembers.setAttribute('department_id', departmentId);
    let members = await searchMembers(departmentId);
    populateAvailableMembersTable(departmentId, members);
    let selectedMembers = await fetchMembers(departmentId);
    populateSelectedMembersTable(departmentId, selectedMembers);
}

SearchMembers.onkeyup = async (e) => {
    let q = e.target.value;
    let departmentId = e.target.getAttribute('department_id');
    let members = await searchMembers(departmentId, q);

    populateAvailableMembersTable(departmentId, members);
}