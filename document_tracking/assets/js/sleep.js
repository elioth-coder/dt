function sleep(s) {
    return new Promise((resolve, reject) => {
        setTimeout(() => resolve(true), s * 1000);
    })
}