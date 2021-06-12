var myForm = document.getElementById('newEmployeeForm');  // Our HTML form's ID
var myFile = document.getElementById('newEmployeeFile');  // Our HTML files' ID
var statusP = document.getElementById('status');

function csvToArray(content){
    let nlSplit =  content.split(/\r?\n/)
    let body = {};
    let error = false
    nlSplit.forEach(item => {
        let attr = item.split(',');
        if(attr[1]) {
            let key = attr[0]
            body[key] = attr[1]
        } else{
            error = true;
        }
    })
    return (error)? {} : body;
}
try {
    myForm.onsubmit = function (event) {
        event.preventDefault();
        // Get the files from the form input
        var files = myFile.files;
        if (files.length == 0) {
            Toastify({
                text: "No file uploaded",
                duration: 3000,
                className: 'toast-bg toast-warning'
            }).showToast()
        } else {
            let file = files[0];
            if (!file.type.match('text/csv')) {
                Toastify({
                    text: "The file selected is not valid.",
                    duration: 3000,
                    className: 'toast-bg toast-warning'
                }).showToast()
                return;
            }
            Toastify({
                text: "Uploading your file.",
                duration: 3000,
                className: 'toast-bg toast-success'
            }).showToast()

            // Create a FormData object
            let formData = new FormData();
            // Add the file to the AJAX request
            formData.append('newEmp', file, file.name);
            headers = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            axios.post('/app/scripts/csvtojson.php', formData, headers)
                .then(data => (data['data']))
                .then(response => csvToArray(response))
                .then(body => {
                    if(Object.entries(body) == 0) {
                        Toastify({
                            text: "Could not resolve CSV. Delimiter should be ',' ",
                            duration: 3000,
                            className: 'toast-bg toast-danger'
                        }).showToast()
                        done;
                    }
                    axios.post(api + "employees", body, config)
                        .then(data => data['data'])
                        .then(response => {
                            if (response.status == 200) {
                                Toastify({
                                    text: response.response.message,
                                    duration: 3000,
                                    className: 'toast-bg toast-success'
                                }).showToast()
                            } else {
                                Toastify({
                                    text: response.response.error,
                                    duration: 3000,
                                    className: 'toast-bg toast-warning'
                                }).showToast()
                            }
                        })
                })
                .catch(() => {
                    Toastify({
                        text: "Sorry, something went wrong... \n Please try again!",
                        duration: 3000,
                        className: 'toast-bg toast-danger'
                    }).showToast()
                })
        }
    }
}catch(e){
    Toastify({
        text: "Sorry, something went wrong... \n Please try again!",
        duration: 3000,
        className: 'toast-bg toast-danger'
    }).showToast()
}