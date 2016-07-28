 /* ***************************************
    Core.js - JavaScript-ядро сайта
*   Copyright (c) 2016 MegaTV;        
*   Developer: gERYH (Eugeniy Genov) 
*************************************** */
'use strict';

var Core = function () {

    var runMainScript = function(){
        // CustomFunctions.js - Кастомные функции ядра
        // Вызов $.cf.<function>
        CustomFunctions.init();
    };
    
    // Delayed Animations
    var runAnimations = function () {

      $('[data-animate]').each(function () {
          var This = $(this)
          var delayTime = $(this).data('animate')[0];
          var delayAnimation = $(this).data('animate')[1];
          
          var delayAnimate = setTimeout(function () {
              $(This).removeClass('animated-delay').addClass('animated ' + delayAnimation);
          }, delayTime); 
          
      });
    };
    
    // Header Functions
    var runHeader = function () {
        $('.js-langdd-init').JsonDropDown();
        $('.js-citydd-init').JsonDropDown({
            search : true
        });

        var Events = function(){
            $('body').on("click", ".js-open-search-form", function(){
                var form = $(this).parents('.form-search');
                form.toggleClass('form-search--show');
                form.toggleClass('form-search--hide');
            });

        }
        
        // Инициализация событий
        Events();
    };
    
    // Header Functions
    var runModals = function () {


        var Events = function(){
            // $('body').on('click','.js-password-show-toggle',function(){
            //     var passInput = $(this).siblings('.js-password-field');
            //     var $this = $(this);
            //     if(passInput.attr('type') == "password") {
            //         passInput.attr('type', 'text');
            //         $this.addClass('password-show-toggle--show');
            //     }else{
            //         passInput.attr('type', 'password');
            //         $this.removeClass('password-show-toggle--show');
            //     }
            //     return false;
            // });

            // $(document).mouseup(function(e) {
            //   if ($('.ModalWindow__overlay').has(e.target).length === 0 && $('.header').has(e.target).length === 0) {
            //     $('.js-ModalWindow').modalHeader('hide');
            //   }
            // });
        }

        // Инициализация событий
        Events();
    };
    
    // SideMenu Functions
    var runSideMenu = function () {
        
    };
    
    // Form related Functions
    var runFormElements = function () {

        
    };
    
    return {
        init: function () {
            runMainScript();
            runAnimations();
            runSideMenu();
            runFormElements();
            runHeader();
            runModals();
        }
    } 
}();

jQuery(document).ready(function($){
    Core.init();
});