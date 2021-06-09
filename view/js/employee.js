let jwt = getCookie("jwt");
const empDiv = document.getElementById('employeedata')
axios.get("/api/v1/employees/"+emp)
    .then( (data) => data['data']['response'])
    .then( response => {
        empDiv.innerHTML = printEmployee(response)
    })
    .catch( (e) =>
{
    console.log(e)
})

function printEmployee(employee) {
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