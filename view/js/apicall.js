/*
   GET FUNCTIONS
 */
/*
    GET Employees
 */
function getSingleEmployee(employee){
    return axios.get(api+"employees/"+employee, config)
        .then( (data) => data['data']['response'])
        .catch( (e) => console.log( e ))
}
function getEmployeesForDepartment(dep){
    return axios.get(api+'employees?departmentid='+dep, config)
        .then(data => data['data']['response'])
        .catch( (e) => console.log( e ))
}
/*
    GET Hours
 */

function getSingleEmployeeHours(employee,startdate = null,enddate = null){
    let params = "";
    params += (startdate != null) ? "?StartDateRange="+startdate+"&endDateRange="+enddate : "";
    return axios.get(api+"hours/"+employee+params, config)
        .then( (data) => data['data']['response'])
        .catch( (e) => console.log( e ))
}
function getAllNonAccordedHours(dep,id= 0){
    return axios.get(api+"hours?departmentid="+dep+"&status=null", config)
        .then(data => (id != 0) ? data['data']['response'].filter(e => e.EmployeeID == id) : data['data']['response'])
        .catch(error => console.log(error))
}

/*
    GET Solutions
 */
function getSolutions(...id){
    if (!id.isArea) id = Array.from(id)
    let content = "";
    axios.get(api+'faq/')
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
                let solution = "  <button class=\"btn btn-light border border-dark w-100\" type=\"button\" data-toggle=\"collapse\" data-target=\"#solution"+response[key]['SolutionID']+"\" aria-expanded=\"false\" aria-controls=\"collapseExample\">\n" +
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
        axios.get(api+'faq/' + searchterm)
            .then(data => data['data']['response'])
            .then(data => {
                if (data.SolutionID) {
                    getSolutions(Number(data.SolutionID))
                    Toastify({
                        text: "Succes for term <strong>" + term + "</strong><br/> This term has been searched " + data.SearchTermCounter + " times!",
                        duration: 2000,
                        escapeMarkup: false,
                        className: 'toast-bg toast-success'
                    }).showToast()
                } else {
                    getSolutions();
                    Toastify({
                        text: "No result for <strong>" + term + "</strong><br/> This term has been searched " + data.SearchTermCounter + " times!",
                        duration: 2000,
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

    return axios.post(api+'hours', body, config)
        .then(data => data['data'])
}

/*
    PUT FUNCTIONS
 */
function updateHour(id, status) {
    let body = {
        EmployeeHoursID: id,
        AccordedByManager: emp,
        HoursAccorded:status
    }
    axios.put(api+"hours/" + id, body, config)
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
function updateSearchTerm(searchterm){
    axios.put(api+"faq/"+searchterm)
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

/*
    DELETE FUNCTIONS
 */

function deleteHour(id) {
    axios.delete(api+"hours/" + id, config)
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
function deleteSearch(st) {
    axios.delete(api+"faq/" + st, config)
        .then((data) => data['data'])
        .then((data) => {
            if (!data.success) {
                Toastify({
                    text: data.response,
                    duration: 3000,
                    className: 'toast-bg toast-warning'
                }).showToast();
            } else {
                document.getElementById("st-"+st).remove();
                Toastify({
                    text: data.response,
                    duration: 3000,
                    className: 'toast-bg toast-success'
                }).showToast();
            }
        })
}