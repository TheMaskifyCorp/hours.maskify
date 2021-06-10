function logout(){
    axios.get("/view/login/signout.php");
    window.location.replace('/view/index.php')
}
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

//setting axios token header
let jwt = getCookie("jwt");
let config = {};
config.headers = {Authorization: `Bearer ${jwt}`}

// functions to handle data
function objectToTable(employee) {
    let content = "<table>";
    Object.keys(employee).forEach((key) =>{
            if (key !== "DocumentNumberID") {
                content += "<tr><td>"+ key + ":</td><td>"+employee[key]+"</td></lr>"
            }
        }
    )
    content += "</table>";
    return content;
}

function formatEmployeeHours(obj){
    let deletionLink = "";
    let alertClass = "alert-primary";
    if (obj.HoursAccorded === null){

        deletionLink = "<a href='#'><button class=\"btn btn-light py-0 \" onclick=\"deleteHour(\'"+obj.EmployeeHoursID+"\')\"><i class=\"bi bi-trash\" aria-hidden=\"true\"></i></button></a>"
    }
    if (obj.HoursAccorded === "1"){
        alertClass = "alert-success";
    }
    if (obj.HoursAccorded === "0"){
        alertClass = "alert-danger";
    }
    let content = "<div id='"+obj.EmployeeHoursID+"' class='eh-hours "+alertClass+"'>" +
        "<div class='eh-hours1'>"+obj.DeclaratedDate+"</div>" +
        "<div class='eh-hours2'>"+obj.EmployeeHoursQuantityInMinutes+"</div>" +
        "<div class='eh-hours3'>"+deletionLink+"</div>" +
        "</div>";
    return content;
}


/*
   GET FUNCTIONS
 */
/*
    GET Employees
 */
function getSingleEmployee(employee){
    return axios.get("/api/v1/employees/"+employee, config)
        .then( (data) => data['data']['response'])
        .catch( (e) => console.log( e ))
}
/*
    GET Hours
 */

function getSingleEmployeeHours(employee,startdate = null,enddate = null){
    let params = "";
    params += (startdate != null) ? "?StartDateRange="+startdate+"&endDateRange="+enddate : "";
    return axios.get("/api/v1/hours/"+employee+params, config)
        .then( (data) => data['data']['response'])
        .catch( (e) => console.log( e ))
}
/*
    GET Solutions
 */
function getSolutions(...id){
    id = Array.from(id)
    console.log(id)
    let content = "";
    axios.get('/api/v1/faq/')
        .then(data => {
            console.log(data['data']['response'])

            return data['data']['response']
        })
        .then(response => {
            for (let key in response) {
                let div = document.createElement('div');
                let solution = "  <button class=\"btn btn-primary\" type=\"button\" data-toggle=\"collapse\" data-target=\"#solution"+response[key]['SolutionID']+"\" aria-expanded=\"false\" aria-controls=\"collapseExample\">\n" +
                    "    "+response[key]['FAQTitle']+" " +
                    "  </button>\n" +
                    "</p>\n" +
                    "<div class=\"collapse\" id=\"solution"+response[key]['SolutionID']+"\">\n" +
                    "  <div class=\"card card-body\">\n" +
                    "    "+response[key]['FAQContent']+"" +
                    "  </div>\n" +
                    "</div>";
                div.innerHTML = solution;
                let existingDiv = document.getElementById('faq-div')
                existingDiv.insertBefore(div, existingDiv.lastChild);
            }
        })
}


/*
    POST FUNCTIONS
 */

/*
    PUT FUNCTIONS
 */

/*
    DELETE FUNCTIONS
 */

function deleteHour(id) {
    axios.delete("/api/v1/hours/" + id, config)
        .then((data) => data['data'])
        .then((data) => {
            if (!data.success) {
                Toastify({
                    text: data.response,
                    duration: 3000,
                    className: 'toast-bg toast-warning'
                }).showToast();
            } else {
                document.getElementById(id).remove();
                Toastify({
                    text: data.response,
                    duration: 3000,
                    className: 'toast-bg toast-success'
                }).showToast();

            }
        })
}
