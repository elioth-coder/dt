const appendAlert = ({container, message, type}) => {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="my-3 alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')

    container.append(wrapper);

    return wrapper;
}