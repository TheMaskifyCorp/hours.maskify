var myForm = document.getElementById('new404ImageForm');  // Our HTML form's ID
var myFile = document.getElementById('new404ImageFile');  // Our HTML files' ID

try {
    myForm.onsubmit = function (event) {
        event.preventDefault();
        console.log('test')
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
            if (!file.type.match('image/png')) {
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
                    'Content-Type': 'image/png'
                }
            }
            axios.post('/app/scripts/upload404.php', formData, headers)
                .then(data => console.log(data) )
        }
    }
}catch(e){
    Toastify({
        text: "Sorry, something went wrong... \n Please try again!",
        duration: 3000,
        className: 'toast-bg toast-danger'
    }).showToast()
}