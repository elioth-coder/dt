const appendToast = ({container, message}) => {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="toast fade show">`,
        `    <div class="toast-header">`,
        `        <i class="bi-info-square-fill rounded me-2"></i>`,
        `        <strong class="me-auto">Tabulator</strong>`,
        `        <small>Just now</small>`,
        `        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>`,
        `    </div>`,
        `    <div class="toast-body">`,
        `        ${message}`,
        `    </div>`,
        `</div>`,
    ].join('')

    container.append(wrapper);

    return wrapper;
}