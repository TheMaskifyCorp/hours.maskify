const form = document.getElementById("contactform")

form.addEventListener('submit', (event) => {
  event.preventDefault();
    const theData = new FormData(form)

    let formdata = {
        "name": theData.get('name'),
        "email": theData.get('email'),
        "content": theData.get('content'),
        "subject": "Uw contactformulier is ontvangen"
    }
    axios.post('/app/scripts/mailer.php', formdata)
        .then(data => data['data'])
        .then((data) => {
            console.log(data)
            if (!data.success) {
                Toastify({
                    text: data.message,
                    duration: 3000,
                    className: 'toast-bg toast-warning'
                }).showToast();
            } else {
                Toastify({
                    text: data.message,
                    duration: 3000,
                    className: 'toast-bg toast-success'
                }).showToast();
            }
        })
})