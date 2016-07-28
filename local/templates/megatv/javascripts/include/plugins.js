/* ***************************************
Plugins.js - Плагины подключаемые к сайту
*   Copyright (c) 2016 MegaTV;        
*   Developer: gERYH (Eugeniy Genov) 
*************************************** */


 
/* --- ModalHeader.js ------------
  Модальное окно инициализация,      
  которого происходит под хедером. 
  - Глобальный плагин;
  - Поддержка Ajax;
**********************************
Copyright (c) 2016 by gERYH 
(FrontEnd Dev. for webapp MegaTV); 
********************************** */
+function ($) {
  'use strict';

  var Modal = function (options) {
    var self = this;
    $.each(options, function(key, value){
      self[key] = value;
    });
  }

  Modal.prototype.init = function(){
    var $this = this.el,
        type = this.type,
        $modalOverlay = $(document.createElement('div')).addClass('ModalWindow__overlay js-ModalOverlay '),
        $modalContent = $(document.createElement('div')).addClass('ModalWindow__content js-ModalContent'),
        $modalLoader = $(document.createElement('div')).addClass('ModalWindow__loader'),
        $modalContentCurrent = $this.find('.js-ModalContent'),
        modalContentHTML = "",
        height = $modalContentCurrent.height(),
        content = this.content,
        delay = this.delay;
  
    $modalContent.html($modalLoader);
    $modalOverlay.html($modalContent);
    if(height > 1) $modalContent.height(height);
    $this.html($modalOverlay);
    
    if(!$this.hasClass('js-ModalWindow--show')){
      $this.fadeIn(delay);
      $modalOverlay[type](delay);
      $this.addClass('js-ModalWindow--show');
    }else $modalOverlay.show();

    if(content !== "" && content !== "undefined") this.show();

  };

  Modal.prototype.show = function(){
    var $this = this.el,
        $modalOverlay = $this.find('.js-ModalOverlay'),
        $modalContent = $this.find('.js-ModalContent'),
        content = this.content;

    if(content == "") {
      console.error("Exception: Content empty!");
      return;
    }
    var height = this.temporaryBlockHeight(content);
    $modalContent.animate({ 
      opacity: 0,
      height: height
    },500,function(){
      var self = $(this);
      self.animate({opacity:1},200).html(content);
      setTimeout(function(){
        $modalContent.height("auto");
      },1000);
    });
  };

  Modal.prototype.temporaryBlockHeight = function(content){
    content = content.replace(/[\<]script[\>](.*\s*)*[\<\/]script[\>]/,"");
    var div = $('<div/>').html(content);
    div.addClass('hiddenBlock').css({
      position: "absolute",
      opacity: 0
    });
    $("body").prepend(div);
    var height = div[0].offsetHeight;
    $('.hiddenBlock').remove();
    return height;
  };

  Modal.prototype.hide = function(){
    var $this = this.el,
        $modalOverlay = $this.find('.js-ModalOverlay'),
        $modalContent = $this.find('.js-ModalContent'),
        content = this.content,
        type = this.type,
        delay = this.delay;

    $modalOverlay[type](delay);
    $this.removeClass('js-ModalWindow--show').fadeOut(delay);
  };

  $.fn.modalHeader = function(method, options) {

      var options = $.extend({
          type: "slideFadeToggle",
          delay: "slow",
          content: ""
      }, options);

      return this.each(function() {
          var $this = $(this),
              modalInit = new Modal($.extend({
                el: $this
              },options));
          if(method === undefined || method == "" || !method) modalInit.init();
          else if(method in modalInit) modalInit[method]();
          else {
            console.error("Exception: Undefined method!");
            return;
          }
      });

  };

}(jQuery);


/* --- slideFadeToggle.js ------
  Двойной эффект 
  (Fade and Slide одновременно)
********************************
  Copyright (c) by jQuery
******************************** */
+function($) {

  $.fn.slideFadeToggle = function(speed, easing, callback){
    return this.animate({opacity: 'toggle', height: 'toggle'}, speed, easing, callback);
  };

}(jQuery);



/* --- JsonDropDown.js --------------------
  Выпадающий список инициализация,
  которого проиходит после передачи
  JSON параметров.
  ! Обязательно !
  Массив должен быть такого вида:
  { 
    "array": [ 
      { 
        "text"  : "Text1",   // Обязат.
        "id"    : "1",       // Не обязат.
        "<key>" : "<value>", // Не обязат.
        "<key>" : "<value>", // Не обязат.
        ...                  // Не обязат.
      } 
    ] 
  }
  Не обязательные атрибуты будут вписаны
  в data атрибуты.
*******************************************
  Copyright (c) 2016 by gERYH 
  (FrontEnd Dev. for webapp MegaTV); 
******************************************* */
+function($) {

  'use strict';

    var JsonDropDown = function(params){
        this.self = params.self;
        this.container = params.container;
        this.settings = params.settings;
        this.active = false;

        var pars = this.settingsCheck();
        if(typeof pars !== "object") return;
        else this.init();
    };

    JsonDropDown.prototype.createMainContainer = function() {
        var self = this;
        var classContainer = this.self.data("class");
        if (classContainer !== undefined) this.container.addClass(classContainer);
        this.self.addClass('DropDownJS-init').append(self.container);
    };

    JsonDropDown.prototype.show = function(content) {
        if(this.active) return;
        this.container.append(content);
        this.self.addClass("DropDownJS-init--open");
        this.active = true; 
    };

    JsonDropDown.prototype.hide = function() {
      var self = this;
        if(!this.active) return;
        this.self.removeClass("DropDownJS-init--open");
        
        setTimeout(function(){
          self.container.html('');
        }, 500);
        this.active = false;
    };

    JsonDropDown.prototype.renderUl = function(ul, search) {
      var array = this.params.array;
      if (search == undefined) search = "";
      ul.html('');
      for (var i = 0; i < array.length; i++) {
          var key = i,
              val = array[i];
          if (val.text.indexOf(search) + 1 || search == "") {
              var li = $(document.createElement('li'))
                  .addClass("DropDownJS-options__option DropDownJS-option")
                  .html("<a class='DropDownJS-option__a' href='#'>" + val.text + "</a>");
              $.each(val, function(k, v) {
                  if (k !== "text") li.children().attr("data-" + k, v);
              });
              ul.append(li);
          }
      };
    };


    JsonDropDown.prototype.renderElements = function(){
        var self = this;
        var dropdown = $(document.createElement('div'))
            .addClass("DropDownJS-dropdown"),
            ulResults = $(document.createElement('ul'))
            .addClass("DropDownJS-options");

        this.renderUl(ulResults);

        if (this.settings.search) {
            var searchContainer = $(document.createElement('div'))
                .addClass("DropDownJS-search");
            var searchInput = $(document.createElement("input"))
                .addClass("DropDownJS-search__input js-field-sdd")
                .attr({
                    "type": "search",
                    "tabindex": "0",
                    "autocomplete": "off",
                    "autocorrect": "off",
                    "autocapitalize": "off",
                    "spellcheck": "false"
                });
            searchContainer.append(searchInput);
            dropdown.append(searchContainer);
            searchInput.on("keyup keypress keydown change", function() {
                var $search = $(this);
                self.renderUl(ulResults, $search.val());
            });
        }
        var containerResults = $(document.createElement('div'))
            .addClass("DropDownJS-results");
        containerResults.append(ulResults);
        dropdown.append(containerResults);

        return dropdown;
    };

    JsonDropDown.prototype.init = function() {
      var self = this;
      self.createMainContainer();
      $(document).mouseup(function(e) {
          if(self.active)
              if (self.container.has(e.target).length === 0) {
                  self.hide();
              }
      });
      var timeout;
      this.container.mouseleave(function(e) {
          timeout = setTimeout(function(){
              self.hide();
          },2000);
      });
      this.container.mouseenter(function(e) {
          clearTimeout(timeout);
      });
      this.self.find('.js-jdd-open').on("click", function(e) {
          var check = $.trim($(this).parent().find('.js-DropDownJS-container').text());
          if(check == ""){
              var dropdown = self.renderElements();
              self.show(dropdown);
          }else{
              self.hide();
          }
      });
    };

    JsonDropDown.prototype.settingsCheck = function() {
      var self = this;
      var $jsonBox = (!this.settings.json) ?
          this.self.siblings('script[type="text/x-config"]') :
          (
              (typeof this.settings.json === "string") ?
              $(self.settings.json) : self.settings.json
          );
      if ($jsonBox.length <= 0) {
          console.error("Exception JsonDropDown: Please, enter JSON params!");
          return;
      }
      try {
          if(typeof $jsonBox === "object") 
              if("array" in $jsonBox)
                  this.params = $jsonBox;
          else this.params = JSON.parse($jsonBox.html());
      } catch (e) {
          console.error("Exception JsonDropDown: Invalid JSON params!");
          return;
      }
      return this.params;
    };

    $.fn.JsonDropDown = function(options) {

        var settings = $.extend({
            'json': false,
            'search': false
        }, options);

        return this.each(function() {
            var $this = $(this);
            var JDD = new JsonDropDown({
              container: $(document.createElement('div')).addClass("js-DropDownJS-container"),
              self: $this,
              settings: settings
            });
        });

    };

}(jQuery);



/* --- getDataFromLink.js --------
  Плагин определяет каким методом 
  получить содержимое файла по
  полученным параметрам.
**********************************
  Copyright (c) by gERYH
********************************** */

+function($) {
  'use strict';

    $.getDataFromLink = function(options) {
        if(options === undefined) options = {};
        options = $.extend({
            el: false,
            link : "/",
            params: false,
            callback : false
        },options);

        var params = (options.params) ? options.params : false,
            callback = (options.callback) ? options.callback : false;

        if(options.el) {
          options.el.load(options.link,params,callback);
        }else{
          $.ajax({
              url: options.link,
              dataType: "html",
              data: params,
              success: callback
          });
        }
        
    };

}(jQuery);

/* --- getEventsList.js --------------
  Плагин позволяет получить массив
  событий прикреплённых к вызванному
  элементу.
**************************************
  Copyright (c) undefined
************************************** */

+function($) {
  $.fn.getEventsList = function() {
    var $this = $(this);
    // В разных версиях jQuery список событий получается по-разному
    var events = $this.data('events');
    if (events !== undefined) return events;

    events = $.data($this, 'events');
    if (events !== undefined) return events;

    events = $._data($this, 'events');
    if (events !== undefined) return events;

    events = $._data($this[0], 'events');
    if (events !== undefined) return events;

    return false;
  }
}(jQuery);



/* --- checkEvent.js -----------------
  Плагин позволяет проверить элемент
  на наявность прикреплённого к нему
  события.
**************************************
  Copyright (c) undefined
************************************** */

+function($) {
  $.fn.checkEvent = function(checkEvent) {
    var $this = $(this),
        events;

    events = $this.getEventsList();
    if (events) {
        $.each(events, function(evName, e) {
            if (evName == checkEvent) {
                return true;
            }
        });
    }
    return false;
  }
}(jQuery);

/* --- FormValid.js ------------------
  Плагин для валидации форм.
**************************************
  Copyright (c) by gERYH
************************************** */

+function($) {
  'use strict';

  var FormValid = function(options) {
    var self = this;
    $.each(options, function(key, val){
      if(key == "validationObj") {
        if(val !== false) 
          self[key] = $.extend(val, self.objValidationFields);
      }
      else if(key == "msgBlock"){
        if(val === false && self.el.find('.js-msg-block')[0]) 
          self[key] = self.el.find('.js-msg-block');
      }
      else self[key] = val;
    });
  };

  FormValid.prototype.validationObj = {
    phone_and_email: function($this){
      var errors = {
        "Неправильный формат телефона / e-mail!" : /(.+@.+\..+)|(\+?[0-9\-\ \(\)]+)/
      },
      value = $this.val();
      return this.renderErrors(errors, value);
    },
    auth_login: function($this){
      var errors = {
        "Вы не ввели логин!" : /.{3,}/
      },
      value = $this.val();
      return this.renderErrors(errors, value);
    },
    auth_pass: function($this){
      var errors = {
        "Вы не ввели пароль!" : /.{3,}/
      },
      value = $this.val();
      return this.renderErrors(errors, value);
    },
    agree: function($this){
      var errors = {
        "Примите условия договора оферты!" : /^on$/
      },
      value = $this.val();
      return this.renderErrors(errors, value);
    },
    checkword: function($this){
      var errors = {
        "Некорректно введён код активации!" : /^[0-9a-zA-Z]{3,20}$/
      },
      value = $this.val();
      return this.renderErrors(errors, value);
    },
    code_change_pass: function($this){
      var errors = {
        "Вы не ввели код!" : /[0-9a-zA-Z]{4,10}/
      },
      value = $this.val();
      return this.renderErrors(errors, value);
    },
    new_pass: function($this){
      var errors = {
        "Пароль должен быть от 5 до 20 символов!" : /[0-9a-zA-Z]{5,20}/
      },
      value = $this.val();
      return this.renderErrors(errors, value);
    },
    renderErrors: function(errors, value){
      var returnArray = [],
          i = 0;
      $.each(errors, function(key, val){
        if(!value.match(new RegExp(val))) {
          var p = $(document.createElement('p')).addClass('msg-block__p');
          returnArray[i] = p.text(key);
        }
      i++;});
      return returnArray || false;
    }
  };

  FormValid.prototype.ajax = function(){
    var url = this.el.attr('action'),
        ajax = this.ajax;
    if(url != "" && url != "#") ajax.url = url;
    $.ajax(ajax);
  };

  FormValid.prototype.validation = function(){
    var form = this.el,
        self = this,
        errors = [];
    form.find('[data-validation]').each(function(index){
      var errorsReturn = false,
          $this = $(this),
          type = $this.data('validation');
      $this.removeClass('form__form-control--error');
      if(errorsReturn = self.validationObj[type]($this)){
        if(errorsReturn.length > 0){
            $this.addClass('form__form-control--error');
          $.each(errorsReturn, function(key, error){
            errors.push(error);
          });
        }
      }
        
    });

    if(errors.length > 0){
      if(this.msgBlock !== false) {
        this.msgBlock.html('');
      } else if(this.msgBlock === false) {
        this.msgBlock = $(document.createElement("div")).addClass("js-msg-block form__msg-block msg-block");
        form.prepend(self.msgBlock);
      }
      this.msgBlock.slideUp(function(){
        var This = $(this);
        This.addClass('msg-block--error');
        $.each(errors, function(key, val){
          This.append(val);
        });
        This.slideDown();
      }); 
      return false;
    } else {
      this.msgBlock.slideUp(function(){
        $(this).html('');
      });
      return true;
    }
  };

  FormValid.prototype.start = function(){
    var form = this.el,
        self = this,
        returnVal = true;
    if(!self.validation()) returnVal = false;
    return returnVal;
  };

  $.fn.formValid = function(options){
      var options = $.extend({
          ajax: {
            url: false,
            data: false,
            dataType: "html",
            success: function(){}
          },
          validationObj: false,
          msgBlock: false
      }, options);

      var $this = $(this),
          validation = new FormValid($.extend({
            el: $this
          },options));
      var returnVal = validation.start();
      return returnVal;

  };

}(jQuery);