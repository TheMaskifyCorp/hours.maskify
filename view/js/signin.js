$(document).ready(function() {

    // process the form
    $('#signin').submit(function(event) {

        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
            'username'              : $('input[name=username]').val(),
            'password'             : $('input[name=password]').val()
        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '/login/signin.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
                        encode          : true
        })
            // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                console.log(data);

                // here we will handle errors and validation messages
                if ( ! data.success) {
                    if ( ! data.errors.credentials){
                        // handle errors for name ---------------
                        if (data.errors.username) {
                            $ng = $('#name-group');
                            $ng.addClass('has-error'); // add the error class to show red input
                            $ng.append('<div class="help-block">' + data.errors.username + '</div>'); // add the actual error message under our input
                            setTimeout(function(){$(".help-block").fadeOut(1000)}, 2000);

                        }

                        // handle errors for password ---------------
                        if (data.errors.password) {
                            $pw = $('#password-group');
                            $pw.addClass('has-error'); // add the error class to show red input
                            $pw.append('<div class="help-block">' + data.errors.password + '</div>'); // add the actual error message under our input}
                            setTimeout(function(){$(".help-block").fadeOut(1000)}, 2000);

                        }
                    } else {
                        $pw = $('#password-group');
                        $pw.addClass('has-error'); // add the error class to show red input
                        $pw.append('<div class="help-block">' + data.errors.credentials + '</div>'); // add the actual error message under our input
                        setTimeout(function(){$(".help-block").fadeOut(1000)}, 2000);
                    }
                } else {
                    // ALL GOOD! just show the success message!
                    $('#leftColumn').html('<div class="alert alert-success">' + data.message + '</div>');
                    $('#signin').modal('hide');
                }
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

});
