
//set employeedata
const empDiv = document.getElementById('employeedata');
const hourDiv = document.getElementById('employeehours');
getSingleEmployee(emp).then( response => {
        empDiv.innerHTML = objectToTable(response)
    })
//init with all the data
getSingleEmployeeHours(emp)
    .then((data) => {
        let content = "";
        data.map((obj => {
            content += formatEmployeeHours(obj)
        }))
        hourDiv.innerHTML = content
    })

//init the datepicker for hours
dateRangeSelector = document.getElementById('dateRangeHours');
const datePickerForHours = new Litepicker({
    element: dateRangeSelector,
    singleMode: false,
    allowRepick:true,
    autoApply:false,
    startDate: new Date(2019,0,1),
    endDate: Date.now(),

    setup: (picker) => {
        picker.on('button:apply', (date1, date2) => {
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