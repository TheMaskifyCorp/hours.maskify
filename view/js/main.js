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
    HELPERS
 */

function reloadPage(){
    window.location.replace(window.location.href)
}
/*
    VALIDATION FUNCTIONS
 */


/*
   GET FUNCTIONS
 */
/*
    GET Employees
 */
function getSingleEmployee(employee){
    return axios.get("/api/v1/employees/"+employee, config)
        .then( (data) => {
            return data['data']['response']
        })
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
    if (!id.isArea) id = Array.from(id)
    let content = "";
    axios.get('/api/v1/faq/')
        .then(data => {
            let response;
            if (!id.length) {
                response = data['data']['response']
            } else {
                response = data['data']['response'].filter(response => !(id.indexOf(Number(response.SolutionID)) < 0))
            }
            return response
        })
        .then(response => {
            let elements = document.getElementsByClassName("faq-solutions")
            elements = Array.from(elements)
            elements.map (elem => elem.remove() )
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
                div.classList.add("faq-solutions");
                div.innerHTML = solution;
                let existingDiv = document.getElementById('faq-div')
                existingDiv.insertBefore(div, existingDiv.lastChild);
            }
        })
}
function UseSearchTerm(term)
{
    if (!term.length){
        getSolutions();
    } else {
        let searchterm = term.replace(" ", "%20");
        searchterm = searchterm.toLowerCase()
        axios.get('/api/v1/faq/' + searchterm)
            .then(data => data['data']['response'])
            .then(data => {
                if (data.SolutionID) {
                    getSolutions(Number(data.SolutionID))
                    Toastify({
                        text: "Succes for term <strong>" + term + "</strong><br/> This term has been searched " + data.SearchTermCounter + " times!",
                        duration: 3000,
                        escapeMarkup: false,
                        className: 'toast-bg toast-success'
                    }).showToast()
                } else {
                    getSolutions();
                    Toastify({
                        text: "No result for <strong>" + term + "</strong><br/> This term has been searched " + data.SearchTermCounter + " times!",
                        duration: 3000,
                        escapeMarkup: false,
                        className: 'toast-bg toast-warning'
                    }).showToast()
                }
            })
    }
}


/*
    POST FUNCTIONS
 */

function postHours(date,time){
    let body = {
        "EmployeeID": Number(emp),
        "DeclaratedDate": date,
        "EmployeeHoursQuantityInMinutes": Number(time)
    }

    return axios.post('/api/v1/hours', body, config)
        .then(data => data['data'])
}

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
