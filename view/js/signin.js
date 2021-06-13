const form = document.getElementById("signin")

form.addEventListener('submit', (event) => {
    event.preventDefault()
    // get the form data
    const theData = new FormData(form)

    let formdata = {
        "username": theData.get('username'),
        "password": theData.get('password')
    }
    axios.post('/app/scripts/signin.php', formdata)
        // using the done promise callback
        .then( response => response['data'])
        .then (data => {
            console.log(data)
            // here we will handle errors and validation messages
            if ( ! data.success) {
                if ( ! data.errors.credentials){
                    // handle errors for name ---------------
                    if (data.errors.username) {
                        Toastify({
                            text: "Username is required",
                            duration: 3000,
                            className: 'toast-bg toast-warning'
                        }).showToast();
                    }
                    // handle errors for password ---------------
                    if (data.errors.password) {
                        Toastify({
                            text: "Password is required",
                            duration: 3000,
                            className: 'toast-bg toast-warning'
                        }).showToast();                        }
                } else {
                    Toastify({
                        text: "Credentials do not match",
                        duration: 3000,
                        className: 'toast-bg toast-danger'
                    }).showToast();
                }
            } else {
                // ALL GOOD! just show the success message!
                window.location.replace('/view/employee/index.php')
            }
        });
});
