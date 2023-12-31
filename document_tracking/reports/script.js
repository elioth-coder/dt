
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

var GENERATED_DATA = [];

function populateReportsTable(rows) {
    let tbody = ReportsTable.querySelector('tbody');
    let content = "";

    if (rows.length) {
        rows.forEach(row => {
            let department = (row.receiver_type=='DEPARTMENT') 
                    ?   row.department 
                    :   row.user_department;

            content += [
                `<tr>`,
                `<td>${row.datetime}</td>`,
                `<td>${row.status}</td>`,
                `<td>${department}</td>`,
                `<td>${row.document_type}</td>`,
                `<td>${row.document_name}</td>`,
                `<td>${row.remarks}</td>`,
                `<td>${row.actor_department}<br> - ${row.actor}</td>`,
                `</tr>`,
            ].join("\n");
        });
    } else {
        content += `
            <tr><td colspan="8" class="text-center">No data found.</td></tr>
        `;
    }

    tbody.innerHTML = content;
}

function generateReport(rows, user) {
    GENERATED_DATA  = [];

    if (rows.length) {
        rows.forEach(row => {
            let department = (row.receiver_type=='DEPARTMENT') 
                    ?   row.department 
                    :   row.user_department;
                    
            GENERATED_DATA.push({
                datetime      : row.datetime,
                status        : row.status,
                department    : department,
                document_type : row.document_type,
                document_name : row.document_name,
                remarks       : row.remarks,
                by            : `${row.actor_department} - ${row.actor}`
            });
        });
    }

    let csv  = Papa.unparse(GENERATED_DATA);
    let currentdate = new Date(); 
    let datetime = 
          `${currentdate.getDate()}`.padStart(2, '0') + "/"
        + `${(currentdate.getMonth()+1)}`.padStart(2, '0')  + "/" 
        + `${currentdate.getFullYear()}`.padStart(2, '0') + " "  
        + `${currentdate.getHours()}`.padStart(2, '0') + ":"  
        + `${currentdate.getMinutes()}`.padStart(2, '0') + ":" 
        + `${currentdate.getSeconds()}`.padStart(2, '0');
        csv += `\n\nGenerated by: ${user.first_name} ${user.last_name} ${datetime}`;

    let data = new Blob([csv], {type: 'text/csv'});
    let url  = window.URL.createObjectURL(data);
    document.getElementById('download').href = url;
    document.getElementById('download').download = "report-" + (new Date()).getTime() + ".csv";
    document.getElementById('download').style.display = 'inline-block';
}

function triggerTooltips() { 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

generate.onclick = async () => {
    let formData = new FormData();
        formData.append('doctype', doctype.value);
        formData.append('department_id', department_id.value);
        formData.append('from', from.value);
        formData.append('to', to.value);

    let response = await fetch('fetch-reports.php', {
        method: 'POST',
        body: formData
    });
    let { rows, status, message, user } = await response.json();

    if(status=="success") {
        populateReportsTable(rows);
        generateReport(rows, user);
    } else {
        download.style.display = 'none';
        Swal.fire({
            icon: 'error',
            title: message
        });
    }

}

triggerTooltips();