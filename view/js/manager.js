const empDiv = document.getElementById('employeedata');
const hourDiv = document.getElementById('employeehours');
const specificEmpDiv = document.getElementById("specificEmployee");
const notAccorded = document.getElementById('notAccorded');


function setManagerPage(id = 0) {
    if(id != 0) {
        getSingleEmployee(id).then(response => {
            specificEmpDiv.innerHTML = objectToTable(response)
        })
    }
    getAllNonAccordedHours(department, id)
        .then((data) => {
            let existingDiv = document.getElementById('notAccorded');
            existingDiv.innerHTML = "";
            data.map((obj => {
                formatAccordableHoursManager(obj)
                    .then(data => {
                        let div = document.createElement('div');
                        div.setAttribute('id',obj.EmployeeHoursID)
                        div.classList.add('eh-hours')
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
            select += "  <option value='0' selected>Department "+department+"</option>\n";
            data.forEach(e =>{
                select += "  <option value='"+e.EmployeeID+"'>"+e.FirstName+" "+e.LastName+"</option>\n";
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
    setManagerPage(0);


        //load unaccorded hours for department


    },
    false)