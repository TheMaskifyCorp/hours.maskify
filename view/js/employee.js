
//set employeedata
const empDiv = document.getElementById('employeedata');
const hourDiv = document.getElementById('employeehours');



//init the datepicker for hours
dateRangeSelector = document.getElementById('dateRangeHours');
const datePickerForRange = new Litepicker({
    element: dateRangeSelector,
    singleMode: false,
    allowRepick:true,
    startDate: new Date(2019,0,1),
    endDate: Date.now(),

    setup: (picker) => {
        picker.on('selected', (date1, date2) => {
            // some action
           let startdate = date1.format('YYYY-MM-DD');
           let enddate = date2.format('YYYY-MM-DD');

           getSingleEmployeeHours(emp,startdate,enddate)
               .then((data) => {
                   hourDiv.innerHTML = "";
                   if (!data.length) {
                       Toastify({
                           text: "No results in specified period",
                           duration: 3000,
                           className: 'toast-bg toast-warning'
                       }).showToast();
                   } else {
                   let content = "";
                   data.map((obj => {
                       content += formatEmployeeHours(obj)
                   }))
                   hourDiv.innerHTML = content
                   }
               })
        });
    }
});

dateHoursSelector = document.getElementById('dateHoursSelector');
const datePickerForHours = new Litepicker({
    element: dateHoursSelector,
    startDate: Date.now()
})
function postHourForm() {
    const addhours = document.getElementById('addHours');
    addhours.addEventListener('submit', (event) => {
        event.preventDefault();
        const hourData = new FormData(addhours);
        postHours(hourData.get('date'), hourData.get('time') )
            .then(response =>{
                if (response.success){
                    Toastify({
                        text: response.response,
                        duration: 3000,
                        className: 'toast-bg toast-success'
                    }).showToast()
                } else {
                    Toastify({
                        text: response.response,
                        duration: 3000,
                        className: 'toast-bg toast-warning'
                    }).showToast()
                }
            })
            .then(() => {
                let date = new Date (hourData.get('date') );
                let year = date.getFullYear();
                let month = date.getMonth();
                var firstDay = new Date(year, month, 1);
                var lastDay = new Date(year, month + 1, 0);

                datePickerForRange.setDateRange(firstDay,lastDay);
            })
    })
}
document.addEventListener('DOMContentLoaded', function() {
        //init with all the data
        getSingleEmployee(emp).then( response => {
            empDiv.innerHTML = objectToTable(response)
        })

        getSingleEmployeeHours(emp)
            .then((data) => {
                let content = "";
                data.map((obj => {
                    content += formatEmployeeHours(obj)
                }))
                hourDiv.innerHTML = content
            })
        postHourForm()
    },
    false
    );