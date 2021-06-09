$(document).ready(function(){

        $("form").on("submit", function(event){
        event.preventDefault();
        $('input[type=submit]', this).attr('disabled', 'disabled').attr('value','Installing...');
        $('input[type=input]', this).attr('disabled', 'disabled');
        let formValues= $(this).serialize();
        axios.post('install.php',formValues,config)
            .then((data) => data['data'] )
            .then(response => {
                parseAlert(response)
            })
            .catch(e => {
                console.log(e);

                Toastify({
                    text: "Something went wrong, sorry",
                    duration: 3000,
                    className: 'toast-bg toast-danger',
                }).showToast();
            })
    });
});
function parseAlert(data) {
    let success = true;
    for (let key in data) {
        if (data.hasOwnProperty(key)) {
            let value = data[key];
            let typeOfToast = (value.toLowerCase() == "success") ? "success" : "warning" ;

            Toastify({
                text: key,
                duration: 3000,
                className: 'toast-bg toast-'+typeOfToast,

            }).showToast();
        }
    }
    if (success == true) {
        $('form').trigger("reset");
        $('input[type=submit]', 'form').attr('value', 'Database installed').removeClass("btn-primary").addClass("btn-success");
    } else {
        $('input[type=submit]', 'form').attr('disabled', false).attr('value', 'Install database');
    }
}

