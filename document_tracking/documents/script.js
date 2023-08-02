var formElements = [
    document_name,
    document_type,
    remarks,
];

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

NewDocumentForm.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(NewDocumentForm);

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

const forwardDocumentModal = new bootstrap.Modal('#ForwardDocumentModal', {
    keyboard: false
});

async function forwardDocument(doc) {
    let department_id = (doc.department_id) ? doc.department_id : doc.department_id_user;

    ForwardDocumentForm.querySelector('input[name="document_id"]').value = doc.id;
    ForwardDocumentForm.querySelector('input[name="department_id"]').value = department_id;
    forwardDocumentModal.show();
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

            let from = row.actor_department;
            let to   = (['SENT','FORWARDED'].includes(row.status)) ? department : "";
            
            tbodyContent += [
                `<tr>`,
                `<td class="position-relative" style="width: 50px; border-right: 2px solid #0D6EFD">`,
                `   <div class="${(row.status=='RECEIVED') ? 'bg-primary' : 'bg-secondary-subtle'} position-absolute end-0 rounded-circle" `,
                `       style="border: 2px solid #0D6EFD; margin-right: -16px; width: 30px; height: 30px;"></div>`,
                `</td>`,
                `<td style="width: 50px;"></td>`,
                `<td class="text-end" style="width: 175px;">${row.datetime}</td>`,
                `<td class="">`,
                `   <span class="badge text-bg-${STATUS_COLOR[row.status]}">${row.status}</span>`,
                `</td>`,
                `<td class="">${from}</td>`,
                `<td class="">${to}</td>`,
                `<td class="">[${row.actor}]: <pre>${row.remarks}</pre></td>`,
                `</tr>`,
            ].join("\n");
        });

        let table = [
            `<div class="overflow-y-scroll" style="max-height: 50vh;">`,
            `<table class="position-relative table table-striped table-bordered">`,
            `   <thead>`,
            `   <tr>`,
            `   <td class="bg-white" style="position: sticky; top: 0; width: 50px; border-right: 2px solid #0D6EFD"></td><td></td>`,
            `   <th style="position: sticky; top: 0;" class="bg-white text-center text-primary">DATETIME</th>`,
            `   <th style="position: sticky; top: 0;" class="bg-white text-center text-primary">STATUS</th>`,
            `   <th style="position: sticky; top: 0;" class="bg-white text-center text-primary">FROM</th>`,
            `   <th style="position: sticky; top: 0;" class="bg-white text-primary">TO</th>`,
            `   <th style="position: sticky; top: 0;" class="bg-white text-primary">REMARKS</th>`,
            `   </tr>`,
            `   </thead>`,
            `   <tbody>`,
                    tbodyContent,
            `   <tbody>`,
            `</table>`,
            `</div>`,
        ].join("\n");

        let modalBodyContent = [
            `<table class="table table-bordered mb-0">`,
            `<tr>`,
            `   <th style="width: 100px;" class="fs-1 bg-primary text-white text-center align-middle">`,
            `       <i class="bi bi-file-earmark-richtext"></i>`,
            `   </th>`,
            `   <td>`,
            `       <h5 class="">${doc.document_name}</h5>`,
            `       <span class="badge text-bg-${DOCTYPE_COLOR[doc.document_type]} p-2">${doc.document_type}</span><br>`,
            `       <i>Created by: ${creator.first_name} ${creator.last_name} - ${creator.department.name}</i>`,
            `       <img class="position-absolute m-4 top-0 end-0"`,
            `           style="height: 80px; opacity: 0.50;"`,
            `           src="../assets/favicon/android-chrome-192x192.png"`,
            `       />`,
            `   </td>`,
            `</tr>`,    
            `</table>`,
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

function triggerTooltips() { 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

$(document).ready( function () {
    $('#DataTableDocument').DataTable({
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
} );

$(function () {
    $("#receiver").selectize({
        placeholder: "Type the name of receiver."
    });
    $("#forward-receiver").selectize({
        placeholder: "Type the name of receiver."
    });
});

triggerTooltips();