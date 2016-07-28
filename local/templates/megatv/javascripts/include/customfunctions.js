/* *********************************************
	CustomFunctions.js - Кастомные функции ядра
*   Copyright (c) 2016 MegaTV;        
*   Developer: gERYH (Eugeniy Genov) 
************************************************ */

var CustomFunctions = function(){
	var Functions = {
		method: function(){
			alert('StartApp!');
		}
	}
	return {
		init: function(){
			$.cf = Functions;
		}
	}
}();


