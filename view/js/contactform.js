const form = document.getElementById("contactform")

form.addEventListener('submit', (event) => {
  event.preventDefault();
    const formEmail = new FormData(form)
    formEmail.append("subject","Uw contactformulier")

    axios.post('/app/scripts/mailer.php', formEmail)
        .then(data => data['data'])
        .then((data) => {
            console.log(JSON.stringify(data))
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
        .catch(error => {
            Toastify({
                text: "Whoops, something went wrong",
                duration: 3000,
                className: 'toast-bg toast-danger'
            }).showToast();
        })
})