$(document).ready(function(){

    $("form").on("submit", function(event){
        event.preventDefault();
        $('input[type=submit]', this).attr('disabled', 'disabled').attr('value','Installing...');
        $('input[type=input]', this).attr('disabled', 'disabled');
        let formValues= $(this).serialize();
        $.post("install.php", formValues, function(data){
            console.log(data);
            //Parse it before displaying
            parseAlert(data);
        });
    });
});
function parseAlert(data) {
    let array = JSON.parse(data);
    let success = true;
    for (let key in array) {
        if (array.hasOwnProperty(key)) {
            let value = array[key];
            let message = "<div class='d-flex alert alert-" + value.toLowerCase() + " mb-3 text-break'><div class='d-flex flex-column justify-content-center align-items-left m-0 w-100'>" + key + "</div></div>"
            $("#callbackTarget").append(message);
            if (value.toLowerCase() != "success") {
                success = false;
                $(".alert").delay(2000).fadeOut(1000).removeClass("d-flex");
            }
        }
    }
    if (success == true) {
        $('form').trigger("reset");
        $('input[type=submit]', 'form').attr('value', 'Database installed').removeClass("btn-primary").addClass("btn-success");
    } else {
        $('input[type=submit]', 'form').attr('disabled', false).attr('value', 'Install database');
    }
}