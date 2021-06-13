function logout(){
    axios.get("/app/scripts/signout.php");
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
const config = {};
config.headers = {Authorization: `Bearer ${jwt}`}
const api = '/api/v1/'

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
        deletionLink = "<button class=\"btn btn-light py-0 \" onclick=\"deleteHour(\'"+obj.EmployeeHoursID+"\')\"><i class=\"bi bi-trash\" aria-hidden=\"true\"></i></button>"
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

function formatAccordableHoursManager(obj){
    let accordationlink = "<a href='#'><button class=\"btn btn-light py-0 \" onclick=\"updateHour(\'"+obj.EmployeeHoursID+"\',1)\"><i class=\"bi bi-check2-square\" aria-hidden=\"true\"></i></button></a>";
    let declinelink = "<a href='#'><button class=\"btn btn-light py-0 \" onclick=\"updateHour(\'"+obj.EmployeeHoursID+"\',0)\"><i class=\"bi bi-x-square\" aria-hidden=\"true\"></i></button></a>";
    let alertClass = "alert-primary";
    return getEmployeesForDepartment(department)
        .then(emps => {
            let employees = []
            emps.forEach( e => {
                employees[e.EmployeeID] = e.FirstName+" "+e.LastName
            })
            return employees
        })
        .then(employees => {
            let content = "<div class='eh-hours " + alertClass + "'>" +
                "<div class='eh-hours1'> " + employees[obj.EmployeeID] + "</br>" + obj.DeclaratedDate + "</div>" +
                "<div class='eh-hours2'>" + obj.EmployeeHoursQuantityInMinutes + "</div>" +
                "<div class='eh-hours3'>" + accordationlink + declinelink +"</div>" +
                "</div>";
            return content
        })
}

/*
    HELPERS
 */


/*
    VALIDATION FUNCTIONS
 */
document.addEventListener('DOMContentLoaded', function() {
        loadTranslation('nl');
    },
    false
);