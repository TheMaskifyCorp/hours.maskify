getSolutions();
const faqForm = document.getElementById("faqForm")

faqForm.addEventListener('submit', (event) => {
    event.preventDefault()
    // get the form data
    const theData = new FormData(faqForm)
    UseSearchTerm( theData.get('faqSearch') );
    faqForm.reset()
})