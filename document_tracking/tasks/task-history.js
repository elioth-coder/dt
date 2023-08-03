
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

            let from = row.actor_department;
            let to   = (['ASSIGNED','RE-ASSIGNED','DONE'].includes(row.status)) ? department : "";

            tbodyContent += [
                `<tr>`,
                `<td class="position-relative" style="width: 50px; border-right: 2px solid #0D6EFD">`,
                `   <div class="bg-secondary-subtle position-absolute end-0 rounded-circle" `,
                `       style="border: 2px solid #0D6EFD; margin-right: -16px; width: 30px; height: 30px;"></div>`,
                `</td>`,
                `<td style="width: 50px;"></td>`,
                `<td class="text-end" style="width: 175px;">${row.datetime}</td>`,
                `<td class="">`,
                `   <span class="badge text-bg-${STATUS_COLOR[row.status]}">${row.status}</span>`,
                `</td>`,
                `<td class="">${from}</td>`,
                `<td class="">${to}</td>`,
                `<td>`,
                `   [${row.actor}]: <pre>${row.remarks}</pre>`,
                (row.attachments.length) 
                    ? `<span>[Attachments]:</span> <ul>${row.attachments.map(file => `<li><a target="_blank" href="./files/${file.generated_name}" download="${file.filename}">${file.filename}</a></li>`).join("")}</ul>` 
                    : "",
                `</td>`,
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
            `   <th style="width: 100px;"`,
            `       class="fs-1 bg-primary text-white text-center align-middle">`,
            `       <i class="bi bi-bar-chart-steps"></i>`,
            `   </td>`,
            `   <td>`,
            `       <h5>${task.task_name}</h5>`,
            `       <span class="badge text-bg-danger">DEADLINE: ${task.deadline}</span><br>`,
            `       <i>Assigned by: ${creator.first_name} ${creator.last_name} - ${creator.department.name}</i>`,
            `       <img class="position-absolute m-4 top-0 end-0"`,
            `           style="height: 80px; opacity: 0.50;"`,
            `           src="../assets/favicon/android-chrome-192x192.png"`,
            `       />`,
            `   </td>`,
            `</tr>`,    
            `</table>`,
            table,
        ].join("\n");
        
        TaskHistoryModal.querySelector('.modal-body').innerHTML = modalBodyContent;
    } else {
        TaskHistoryModal.querySelector('.modal-body').innerHTML = [
            `<h3 class="text-center text-danger">${message}</h3>`,
        ].join("\n");
    }
}
