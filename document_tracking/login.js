var formElements = [
    username,
    password,
    submit,
];

function disableForm() {
    formElements.forEach(element => element.setAttribute('disabled', true));
}

function enableForm() {
    formElements.forEach(element => element.removeAttribute('disabled'));
}

function countdown(seconds) {
    remaining = seconds;
    let reset = () => {
        Timer.innerHTML = "";
        enableForm();
    }
    let tick = () => {
        Timer.innerHTML = "Try again after " + remaining + " seconds";

        setTimeout(() => {
            remaining--;

            if (remaining > 0) {
                tick();
            } else {
                reset();
            }
        }, 1000);
    }

    tick();
}

login.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(login);

    let options = {
        container: AlertContainer,
        message: [
            `<img class='me-2' style='height: 25px;' src='./assets/images/spinner.gif' />`,
            ` Logging in...`,
        ].join("\n"),
        type: "info"
    };
    let alertWrapper = appendAlert(options);
    disableForm();

    let response = await fetch('./process/login.php', {
        method: 'POST',
        body: formData,
    });
    let { status, message, limit_reached, time_set } = await response.json();

    alertWrapper.remove();
    enableForm();
    if (status == 'success') {
        window.location.href = './';
    } else {
        if (limit_reached) {
            disableForm();
            countdown(time_set);
        }

        let options = {
            container: AlertContainer,
            message: [
                `${message}`,
            ].join("\n"),
            type: "danger"
        };
        let alertWrapper = appendAlert(options);
        await sleep(3); alertWrapper.remove();
    }
}