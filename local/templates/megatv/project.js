    function icon(name, options) {
    	var optionsz = options || {};
    	var size	= optionsz.size ? 'is-' + optionsz.size : '';
    	var klass	= 'icon ' + name + ' ' + size + '' + (optionsz.class || '');
    
    	var iconz	=	'<svg class="icon__cnt">' +
    					'<use xlink:href="#' + name + '" />' +
    					'</svg>';
    
    	var html =  '<div class="' + klass + '">' +
    					wrapSpinner(iconz, klass) +
    				'</div>';
    
    	return html;
    }
    function wrapSpinner(html, klass) 
    {
    	if (klass.indexOf('spinner') > -1) {
    		return '<div class="icon__spinner">' + html + '</div>';
    	} else {
    		return html;
    	}
    }
    function renderIcons() {
    	var icons = document.querySelectorAll('[data-icon]');
    
    	for (var i = 0; i < icons.length; i++) {
    		var currentIcon = icons[i];
    		var name        = currentIcon.getAttribute('data-icon');
    		var options = {
    			class:  currentIcon.className,
    			size:   currentIcon.getAttribute('data-size')
    		};
    
    		currentIcon.insertAdjacentHTML('beforebegin', icon(name, options));
    		currentIcon.parentNode.removeChild(currentIcon);
    	}
    }

(function($) 
{    
    $.fn.scrollPagination = function(options) {
		
		var settings = { 
			nop     : 10, // Количество запрашиваемых из БД записей
			offset  : 0, // Начальное смещение в количестве запрашиваемых данных
			delay   : 500, // Задержка перед загрузкой данных
			scroll  : true // Если true то записи будут подгружаться при прокрутке странице, иначе только при нажатии на кнопку 
		}
		
		// Включение опции для плагина
		if(options) {
			$.extend(settings, options);
		}
		
		return this.each(function() {		
			
			$this = $(this);
            $settings = settings;
			var offset = $settings.offset;
			var busy = false; // переменная для обозначения происходящего процесса
            var finished = false;
            
            if($this.data("activate")===undefined || finished)
                return false;
            
            if($this.data("nop")!==undefined)
            {
                $settings.nop = offset = $this.data("nop");
            }
            
			// Функция AJAX запроса
			function getData() {
				
				// Формируется POST запрос к ajax.php
				$.post($this.data("url"), {	
				    number        : $settings.nop,
				    offset        : offset,
                    AJAX          : "Y"
					    
				}, function(data) {
					
                    var $response = $(data);
                    //console.log($response.find('.broadcasts-list [data-type="broadcast"]').length);
                    
					if($response.find('.broadcasts-list [data-type="broadcast"]').length!=0) 
                    { 
                        $this.append($response.find(".broadcasts-list").html());
                        $('[data-type="broadcast"]').data('status-flag', false).data('play-flag', false);
                        
                        renderIcons();

						// Смещение увеличивается
					    offset = offset + $settings.nop; 
						    
						// Добавление полученных данных в DIV content
					   	$this.find('.content').append(data);
						
						// Процесс завершен	
						busy = false;
					}else{
                        
                        //console.log("finished");
                        finished = true;
					}		
				});	
			}	
			
			//getData(); // Запуск функции загрузки данных в первый раз
			
			// Если прокрутка включена
			if($settings.scroll == true) 
            {
				// .. и пользователь прокручивает страницу
				$(window).scroll(function() {
					
					// Проверяем пользователя, находится ли он в нижней части страницы
					if($(window).scrollTop() + $(window).height() > $this.height() && !busy) {
						
						// Идет процесс
						busy = true;
						
						// Запустить функцию для выборки данных с установленной задержкой
						// Это полезно, если у вас есть контент в футере
						setTimeout(function() {
							
							getData();
							
						}, $settings.delay);
							
					}	
				});
			}
						
		});
	}
})(jQuery);


$(document).on('ready', function(){
        
    $('.recommended-broadcasts .broadcasts-list').scrollPagination();
    
    function errorsView(form){
        var errors = false;
        var amount = form.find("#bx-asd-amount");
        var methodPay = form.find("[name=pay_system]:checked");
        var methodsPay = form.find("[name=pay_system]");

        if(!(amount.val()).match(/^[0-9]{1,10}$/)){
            errors = true;
            amount.parents(".form-group").addClass("has-error");
            setTimeout(function(){
                amount.parents(".form-group").removeClass("has-error");
            },2000);
        }
        if(!methodPay[0]){
            errors = true;
            methodsPay.parents(".radio-group").addClass("incorrect");
            setTimeout(function(){
                methodsPay.parents(".radio-group").removeClass("incorrect");
            },2000);
        }
        return errors;
    }
    var sendForm = true;
    $('form.asd-prepaid-form').on('submit', function(){
        var $form = $(this);
        var errors = errorsView($form);

        if(!errors && sendForm) {
            sendForm = false;
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                error: function(request,error) {
                    alert('Error! Please try again!');
                },
                success: function(data) 
                {
                    $("#form-pay-request").html(data);
                    $("#form-pay-request form").submit();
                }
            });
        }
        
        if(!sendForm){
            $form.find('[data-type="paymethod-submit"]').attr('disabled', true);
        }

        return false;
    });
    
    
    $(".modal-nav li a").click(function(e){
        e.preventDefault();
        $(".modal-nav li").removeClass("active");
        $("#auth-screens .tab-content .tab-pane").removeClass("in active");
        str = $(this).attr("href");
        $(this).closest("li").addClass("active");
        $(str).addClass("in active");
    });
    
    /*$('#comment-form .submit-btn').on('click', function(e){
        e.preventDefault();
        
        var $form = $('#comment-form');
        
        $.ajax({
            type: "POST",
            url: $form.attr('action'),
            data: $form.serialize(),
            error: function(request,error) {
                alert('Error! Please try again!');
            },
            dataType: "json",
            success: function(data) {
                
                if(!data.status)
                {
                    errors = data.errors;
                    console.log(errors);
                }else{
                    
                }
            }
        });

        return false;
    });*/
    
    /*$('a#channels-show-ajax-link').on('click', function (e) {
		e.preventDefault();
        var _this = this;
        
        var 
            furl = $(_this).data('load'),
            //feed = $(_this).data('feed'),
            page = $(_this).data('page');
            type = $(_this).data('ajax-type');
        
        if(!$(_this).hasClass("loading"))
        {
            //show loading
            $(_this).hide().addClass("loading");
            $(_this).next('div').css({display: 'inline-block'});
            
            if(furl.indexOf("?")>-1)
            {
                furl=$(_this).data('load') + "&PAGEN_2="+page + "&PAGEN_1="+page;
            }else{
                furl=$(_this).data('load') + "?PAGEN_2="+page + "&PAGEN_1="+page;
            }
            
            //console.log(furl);
            //console.log(type);
            
            $.ajax({
                url: furl,
                type: "POST",
                data: {
                    AJAX : 'Y',
                    AJAX_TYPE : type
                },
                success: function(response) {
                    var $response = $(response);

                    $(".categories-logos").append($response.find(".categories-logos").html());
                    $(".categories-items .row-wrap").append($response.find(".categories-items .row-wrap").html());

                    $('.categories-items [data-type="broadcast"]').data('status-flag', false).data('play-flag', false);
                    
                    renderIcons();
                    
                    $(_this).show().removeClass("loading");
                    $(_this).next('div').hide();
                    $(_this).data('page', ++page);
                },
                error: function() {
                    alert('Error load materials');
                }
            });
        }

        return false;
    });*/
        
    $(".badge, .channel-online").on('click', function(e){
        e.preventDefault();
        channel_id = $(this).data("channel-id");
        pageModule = $('[data-module="page"]').get(0);
        authentication = Box.Application.getModuleConfig(pageModule, 'authentication');
        
        if (authentication === true) 
        {
            if(channel_id!==undefined)
            {
                var modalHTML = $('<div class="modal player-modal fade" id="player-modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>');
        		if ($('#player-modal').length === 0) {
        			$('body').prepend(modalHTML);
        		}
    
        		$.ajax({
        			type: 'GET',
        			dataType: 'html',
        			url: "/local/templates/megatv/ajax/modals/player.php",
        			data: {
        				broadcastID: 0,
        				record: false,
                        channel_id: channel_id
        			},
        			success: function (data) {
        				playerModalContainer = $('.player-modal');
        				playerModalContainer.find('.modal-content').html(data);
        				renderIcons();
        				Box.Application.startAll(playerModalContainer.get(0));
        				modal = Box.Application.getService('modal').create(playerModalContainer, {
        					backdropClass: 'modal-backdrop player-backdrop'
        				});
        				modal.show();
        			}
        		});
            }
        }else{
            $("#mod-signin-overlay-1").addClass("is-visible");
            return;
        }
    });
    
});