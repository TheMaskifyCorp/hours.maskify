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
config = { headers: {Authorization: `Bearer ${jwt}`} }

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
    let alertClass = "alert-secondary";
    if (obj.HoursAccorded === null){

        deletionLink = "<a href='#'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a>"
    }
    if (obj.HoursAccorded === "1"){
        let alertClass = "alert-succes";
    }
    if (obj.HoursAccorded === "0"){
        let alertClass = "alert-warning";
    }
    let content = "<div class='eh-hours "+alertClass+"'>" +
        "<div class='eh-hours1'>"+obj.DeclaratedDate+"</div>" +
        "<div class='eh-hours2'>"+obj.EmployeeHoursQuantityInMinutes+"</div>" +
        "<div class='eh-hours3'>"+deletionLink+"</div>" +
        "</div>";
    return content;
}


//functions to get data from api

function getSingleEmployee(employee){
    return axios.get("/api/v1/employees/"+employee, config)
        .then( (data) => data['data']['response'])
        .catch( (e) => console.log( e ))
}

function getSingleEmployeeHours(employee,startdate = null,enddate = null){
    let params = "";
    params += (startdate != null) ? "?StartDateRange="+startdate+"&endDateRange="+enddate : "";
    return axios.get("/api/v1/hours/"+employee+params, config)
        .then( (data) => data['data']['response'])
        .catch( (e) => console.log( e ))
}