/*
Передается:
- макс длина
- id textarea
- id счетчика
*/
function length_check(len_max, field_id, counter_id) { 	
	var current_length = document.getElementById(field_id).value.length; // текущая длина поля ввода
	var rest = len_max - current_length; // кол-во оставшихся символов
	if (current_length> len_max ) {	
		document.getElementById(field_id).value = document.getElementById(field_id).value.substr (0, len_max); // укорачивает строку
		if (rest < 0) { 
			rest = 0;	// в осташиеся символы записывает 0
		} 
		alert('Максимальная длина содержимого поля: ' + len_max + ' символа(-ов)');	
		} else {	
		document.getElementById(counter_id).firstChild.data = rest + ' символов '; // вывод на страницу	
	} 
}


