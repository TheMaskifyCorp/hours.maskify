const empDiv = document.getElementById('employeedata');
const hourDiv = document.getElementById('employeehours');
const notAccorded = document.getElementById('notAccorded');


function setManagerPage(id = null) {
    getAllNonAccordedHours(department, id)
        .then((data) => {
            let existingDiv = document.getElementById('notAccorded');
            existingDiv.innerHTML = "";
            data.map((obj => {
                formatAccordableHoursManager(obj)
                    .then(data => {
                        let div = document.createElement('div');
                        div.setAttribute('id',obj.EmployeeHoursID)
                        //div.classList.add("faq-solutions");
                        div.innerHTML = data;

                        existingDiv.insertBefore(div, existingDiv.lastChild);
                    })
            }))
        })

}

//init functions
document.addEventListener('DOMContentLoaded', function() {

    //create selector for employeedata
    getEmployeesForDepartment(department)
        .then(data => {
            let select = "<select id='employeeSelector' class=\"form-select\" aria-label=\"Select Employee\" '>\n"
            data.forEach(e =>{
                let selected = (e.EmployeeID == emp) ? "selected" : "";
                let value = (e.EmployeeID == emp) ? 0 : e.EmployeeID;
                select += "  <option value='"+value+"'"+selected+">"+e.FirstName+" "+e.LastName+"</option>\n";
            })
            select += "</select>";
            return select;
        })
        .then(select => {
            empDiv.innerHTML = select;
            let sel = document.getElementById('employeeSelector')
            sel.addEventListener("change", function(){
                setManagerPage( this.value );
            })
        } )
    setManagerPage();


        //load unaccorded hours for department


    },
    false)