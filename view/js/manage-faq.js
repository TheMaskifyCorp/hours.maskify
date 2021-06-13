const unusedSearches = document.getElementById("unusedSearches");

function createSolutionSelector(){
    return axios.get(api+"faq", config)
        .then(data => data['data']['response'])
}
function getUnusedSearchterms(){
    return axios.get(api+"faq"+"?getsearchresults", config)
        .then(data => data['data']['response'])
}

function formatUsusedSearch(obj){
    let deletionLink = "<a href='#'><button class=\"btn btn-light py-0 \" onclick=\"deleteSearch(\'"+obj.SearchTerm+"\')\"><i class=\"bi bi-trash\" aria-hidden=\"true\"></i></button></a>"
    let content = "<div class='search-searchterm'>"+obj.SearchTerm+"</div>" +
        "<div class='search-selector'></div>" +
        "<div class='search-deletion'>"+deletionLink+"</div>"
    return content;
}

document.addEventListener('DOMContentLoaded', function() {
    getUnusedSearchterms()
        .then(response => {
                response.forEach(obj =>{
                    if (obj.SolutionID == null)
                    {
                        let div = document.createElement('div');
                        div.setAttribute('id', "st-" + obj.SearchTerm)
                        div.classList.add('search-unused-grid', 'alert-primary','mb-1')
                        div.innerHTML = formatUsusedSearch(obj)
                        unusedSearches.appendChild(div)
                    }
                })
            }
        )

    createSolutionSelector();


    },
    false)