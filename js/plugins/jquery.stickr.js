$.stickr = function(o) {
	var o = $.extend({   // настройки по умолчанию
		time:5000, // количество мс, которое отображается сообщение
		speed:'slow', // скорость исчезания
		note:null, // текст сообщения
		className:null, // класс, добавляемый к сообщению
		sticked:false, // не выводить кнопку закрытия сообщения
		position:{top:0,right:0}, // позиция по умолчанию - справа сверху
		removePrevious: false
	}, o);
	var stickers = $('#jquery-stickers'); // начинаем работу с главным элементом
	if (!stickers.length) { // если его ещё не существует
		$('body').prepend('<div id="jquery-stickers"></div>'); // добавляем его
		var stickers = $('#jquery-stickers');
		stickers.css('position','fixed').css(o.position).css('z-index','10000'); // позиционируем
	}
	var stick = $('<div class="stick" style="display: none"></div>'); // создаём стикер
	
	if(o.removePrevious)
		stickers.empty();
	
	stickers.append(stick); // добавляем его к родительскому элементу
	
	if (o.className) stick.addClass(o.className); // если необходимо, добавляем класс
    stick.html(o.note); // вставляем сообщение
        
       
    stick.slideDown(o.speed)
	if (o.sticked) { // если сообщение закреплено
		var exit = $('<div class="exit"></div>');  // создаём кнопку выхода
		stick.prepend(exit); // вставляем её перед сообщением
		exit.click(function(){  // при клике
			stick.fadeOut(o.speed,function(){ // скрываем стикер
				$(this).remove(); // по окончании анимации удаляем его
			})
		});
	} else { // если же нет
		setTimeout(function(){ // устанавливаем таймер на необходимое время
			stick.slideUp(o.speed,function(){ // затем скрываем стикер
				$(this).remove(); // по окончании анимации удаляем его
			});
		}, o.time);
	}
	
};