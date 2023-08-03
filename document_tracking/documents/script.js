var formElements = [
    document_name,
    document_type,
    remarks,
    submit,
    reset
];

var BACKGROUNDS = {
    'RECEIVED'  : "bg-success",
    'SENT'      : "bg-info",
    'FORWARDED' : "bg-warning",
};

function disableForm() {
    formElements.forEach(element => element.setAttribute('disabled', true));
}

function enableForm() {
    formElements.forEach(element => element.removeAttribute('disabled'));
}

ForwardDocumentForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(ForwardDocumentForm);

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
        let response = await fetch('forward.php', {
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

documentForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(documentForm);

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

function deleteDocument(id) {
    Swal.fire({
        icon: 'warning',
        title: 'Do you want to delete this document?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
    }).then(async (result) => {
        if (result.isConfirmed) {
            let options = {
                container: ToastContainer,
                message: [
                    `<img class='me-2' style='height: 20px;' src='../assets/images/spinner.gif' />`,
                    ` Deleting document...`,
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
                documents = await fetchDocuments();
                populateDocumentsTable(documents);
            }
        }
    })
}

const forwardModal = new bootstrap.Modal('#ForwardModal', {
    keyboard: false
});

async function forwardDocument(doc) {
    let department_id = (doc.department_id) ? doc.department_id : doc.department_id_user;

    ForwardDocumentForm.querySelector('input[name="document_id"]').value = doc.id;
    ForwardDocumentForm.querySelector('input[name="department_id"]').value = department_id;
    forwardModal.show();
}

const documentHistoryModal = new bootstrap.Modal('#DocumentHistoryModal', {
    keyboard: false
});

async function viewDocumentHistory(doc) {
    documentHistoryModal.show();

    let response = await fetch('fetch-history.php?document_id=' + doc.id);
    let { status, message, rows, creator } = await response.json();
    
    if(status == 'success') {
        let tbodyContent = "";
        rows.forEach(row => {
            let department = (row.receiver_type=='DEPARTMENT') 
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
                `<td>${row.remarks}</td>`,
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
            `   <th style="width: 190px;" class="bg-primary text-white text-center align-middle">DOCUMENT</th>`,
            `   <td>`,
            `       <h4 class="text-primary">${doc.document_name}</h4>`,
            `       <span class="mt-2 badge bg-primary p-2">${doc.document_type}</span><br>`,
            `       <i>Created by: ${creator.first_name} ${creator.last_name} - ${creator.department.name}</i>`,
            `   </td>`,
            `</tr>`,    
            `</table>`,
            `<hr>`,
            table,
        ].join("\n");
        
        DocumentHistoryModal.querySelector('.modal-body').innerHTML = modalBodyContent;
    } else {
        DocumentHistoryModal.querySelector('.modal-body').innerHTML = [
            `<h3 class="text-center text-danger">${message}</h3>`,
        ].join("\n");
    }
}

async function receiveDocument(doc) {
    let result = await Swal.fire({
        title: 'Receive document?',
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
            formData.append('id', doc.id);
            formData.append('remarks', remarks);
            let department_id = (doc.department_id) ? doc.department_id : doc.department_id_user;

            formData.append('department_id', department_id);

        let response = await fetch('receive.php', {
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


async function fetchDocuments() {
    let response = await fetch('read.php');
    let { rows } = await response.json();

    return rows;
}

async function searchDocuments(q) {
    let response = await fetch('search.php?q=' + q);
    let { rows } = await response.json();

    return rows;
}

function populateDocumentsTable(documents) {
    let tbody = DocumentsTable.querySelector('tbody');
    let content = "";

    if (documents.length) {
        documents.forEach(document => {
            content += [
                `<tr>`,
                `<td style="width: 190px;" class="text-center">${document.datetime}</td>`,
                `<td style="width: 170px;" class="text-center">`,
                `   <span class="fs-6 p-2 d-block w-100 badge ${BACKGROUNDS[document.status]}">${document.status}</span>`,
                `</td>`,
                `<td>`,
                `   <h4>${document.name}</h4>`,
                `   <span class="mt-2 badge bg-primary p-2">${document.document_type}</span><br>`,
                `</td>`,
                `<td style="width: 140px;" class="text-center">`,
                `   <button data-bs-toggle="tooltip" data-bs-placement="top"`,
                `       data-bs-title="View"`,
                `       onclick='viewDocumentHistory(${JSON.stringify(document)});' class="btn btn-outline-info">`,
                `       <i class="bi bi-eye-fill"></i>`,
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

        documents = await searchDocuments(q);
        populateDocumentsTable(documents);
    }
}

$(function () {
    $("#receiver").selectize({
        placeholder: "Type the name of receiver."
    });
    $("#forward-receiver").selectize({
        placeholder: "Type the name of receiver."
    });
});

function triggerTooltips() { 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

var selectedRows = [];
var documents = [];

(async () => {
    if (document.getElementById('DocumentsTable')) {
        documents = await fetchDocuments();
        populateDocumentsTable(documents);
    }
})();

triggerTooltips();