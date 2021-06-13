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
    let nieuwBericht = {
        "name": "Website Formulier",
        "email": "info@maskify.nl",
        "content": 'een nieuw bericht is verstuurd via de website:\n'+
            'Naam: '+ theData.get('name') +'\n'+
            'Emailadres: '+ theData.get('email') +'\n'+
            'Bericht: \n'+ theData.get('content'),
        "subject": "Nieuw contactformulier"
    }

    axios.post('/app/scripts/mailer.php', nieuwBericht)
        .then(
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
                )
        .catch(error => {
            Toastify({
                text: "Whoops, something went wrong",
                duration: 3000,
                className: 'toast-bg toast-danger'
            }).showToast();
        })
})