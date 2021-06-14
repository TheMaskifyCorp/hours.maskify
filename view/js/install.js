const form = document.getElementById('theForm');  // Our HTML files' ID
const submit = document.getElementById("submitinstall")

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
        form.reset()
        submit.setAttribute('value', 'Database installed')
        submit.classList.remove("btn-primary");
        submit.classList.add("btn-success");
    } else {
        submit.setAttribute('disabled', 'false');
    }
}

function unInstall(){
    axios.get('/app/scripts/uninstall.php')
        .then(()=> window.location.replace('/'))
        .catch(()=>{
            Toastify({
                text: "Something went wrong, sorry",
                duration: 3000,
                className: 'toast-bg toast-danger',
            }).showToast();
        })
}

document.addEventListener('DOMContentLoaded',function(){
    //oud
/*    $("form").on("submit", function(event) {
        event.preventDefault();
        $('input[type=submit]', this).attr('disabled', 'disabled').attr('value', 'Installing...');
        $('input[type=input]', this).attr('disabled', 'disabled');
        let formValues = $(this).serialize();
        console.log(formValues)
    })*/
    //nieuw


        form.addEventListener('submit',(event) =>{
            event.preventDefault();
            submit.setAttribute('disabled', 'disabled');
            submit.setAttribute('value','Installing...');
            let formdata = new FormData(form)
            let body = {
                "hostname":formdata.get('hostname'),
                "database":formdata.get('database'),
                "username":formdata.get('username'),
                "password":formdata.get('password')
            }
            if (formdata.get('dummydata')){
                body.dummydata = "on";
            }
            axios.post('/app/scripts/install.php',formdata,config)
                .then((data) => data['data'] )
                .then(response => {
                    console.log(response)
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
        })
    }, false
)





//OLD VERSION
/*

$(document).ready(function(){
        $("form").on("submit", function(event){
        event.preventDefault();
        $('input[type=submit]', this).attr('disabled', 'disabled').attr('value','Installing...');
        $('input[type=input]', this).attr('disabled', 'disabled');
        let formValues= $(this).serialize();
        console.log(formValues)

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

*/
