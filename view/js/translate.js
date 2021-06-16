 function Translate() {
	//initialization
	this.init =  function(attribute, lng){
		this.attribute = attribute;
		this.lng = lng;
	}
	//translate
	this.process = function(){
		const _self = this;
		axios.get('/view/includes/translations/'+this.lng+'.json')
			.then(data => data.data)
			.then(LngObject => {
				let allDom = document.getElementsByTagName("*");
				let l = allDom.length
				for(let i = 0; i < l-1; i++){
					let elem = allDom[i];
					let key = elem.getAttribute(_self.attribute);
					if(key != null) {
						elem.innerHTML = LngObject[key]  ;
					}
				}})
    }
}
function loadTranslation(lang) {
	var translate = new Translate();
	translate.init("data-lang", lang);
	translate.process();
}