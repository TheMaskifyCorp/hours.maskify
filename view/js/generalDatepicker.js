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