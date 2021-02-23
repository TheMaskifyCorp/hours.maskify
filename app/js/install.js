/*$(document).ready(function(){
    $('input#EmployeeHoursQuantity').timepicker({
        minStep: 5,
        timeFormat:"%H:%i",
        scrollDefault:"00:00",
        selectSize: 5
    });
});*/

$(document).ready(function(){
    $("form").on("submit", function(event){
        event.preventDefault();
        $('input[type=submit]', this).attr('disabled', 'disabled').attr('value','Installing...');
        var formValues= $(this).serialize();
        $(this)[0].reset();
        $.post("install.php", formValues, function(data){
            // Display the returned data in browser
            parseAlert(data);
        });
    });
});
function parseAlert(data){
    let array = JSON.parse(data);
    for (let key in array) {
        if (array.hasOwnProperty(key)) {
            let value = array[key];
            let message = "<div class='d-flex alert alert-success mb-3'><div class='d-flex flex-column justify-content-center align-items-left m-0 w-100'>" + key + ":<br> " + value + "</div></div>"
            $("#callbackTarget").append(message);
        }
    }
}
