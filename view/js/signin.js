    const form = document.getElementById("signin");
    form.addEventListener('submit', (event) => {
        event.preventDefault()
        // get the form data
        const theData = new FormData(form)

        let formdata = {
            "username": theData.get('username'),
            "password": theData.get('password')
        }

        axios.post('/view/login/signin.php', formdata)
            // using the done promise callback
            .then( response => response['data'])
            .then (data => {
                // here we will handle errors and validation messages
                console.log(data)
                if ( ! data.success) {
                    if ( ! data.errors.credentials){
                        // handle errors for name ---------------
                        if (data.errors.username) {
                            //todo give an error
                            console.log('username must be here correct')
                        }
                        // handle errors for password ---------------
                        if (data.errors.password) {
                            console.log('password must be here correct')
                            //todo give an error
                        }
                    } else {
                        //todo give proper error
                        console.log('credentials do not match')
                    }
                } else {
                    // ALL GOOD! just show the success message!
                    window.location.replace('/view/employee/index.php')

                }
            });
    });
