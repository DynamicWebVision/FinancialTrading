/**
 * Created by boneill on 2/26/14.
 */

(function($) {

      $.fn.success_small = function(callback) {
          
         $(this).css("top","20px"); 
          
        var divEffected = this.selector;
        
        setTimeout(function(){
            
            $(divEffected).fadeIn(900);
            
        },700);
        
        
        setTimeout(function(){
            
            $(divEffected).animate({
            'top': "55px"
            }, {
                duration: 1300,
                easing: 'linear',
                queue: false
            });
            
            $(divEffected).fadeOut('2200',function(){
                callback();
            });
            
        },3000);
        
        

    };
    
    $.fn.successFade = function(messageText, callBack) {
        $("#fixedAjaxSuccess").show();
        $(".successHead").html(messageText);

        $(this).css("top","0px");
        var divEffected = this.selector;
        
        setTimeout(function(){
            
            $(divEffected).fadeIn(600);
            
        },200);
        
        setTimeout(function(){
            
            $(divEffected).animate({
            'top': "70px"
            }, {
                duration: 1000,
                easing: 'linear',
                queue: false
            });
            
            $(divEffected).fadeOut('2200',function(){
                $("#fixedAjaxSuccess").hide();
                if (typeof callBack != "undefined") {
                    callBack();
                }
            });
            
        },2000);
    };


}(jQuery));

