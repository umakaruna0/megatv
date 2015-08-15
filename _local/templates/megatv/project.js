$(document).on('ready', function(){
    
    $('form#login-form').submit(function(){
        var $this = $(this);
        
        $this.find(".email-container").removeClass("has-error");
        $this.find(".email-container .form-control-message").remove();
        
        $.post($this.attr('action'), $this.serialize(), function(data){
            $('input', $this).removeAttr('disabled');
            if (data.type == 'error') 
            {
                $( '<span class="form-control-message">'+data.message+'</span>' ).insertAfter($this.find(".email-container").addClass("has-error").find("input"));
            } else {
                window.location.href = $this.data("redirect");
            }
        }, 'json');
        return false;
    });

    $('form#register-form').on('submit', function(){
        var $form = $(this);
        var form_backurl = $(this).find('input[name="backurl"]').val();

        $form.find("div").removeClass("has-error");
        $form.find(".form-control-message").remove();

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            error: function(request,error) {
                alert('Error! Please try again!');
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
                if(!data.status)
                {
                    errors = data.errors;
                    $.each(errors, function(i, val) {
                        if(i=="AGREE")
                        {
                            $form.find("input[name='"+i+"']").closest("div").addClass("has-error").find("input")
                        }else{
                            $( '<span class="form-control-message">'+val+'</span>' ).insertAfter($form.find("input[name='"+i+"']").closest("div").addClass("has-error").find("input"));
                        }
                    });
                }else{
                    //setTimeout(function(){alert("Boom!");}, 2000);
                    window.location.href = form_backurl;
                }
            }
        });

        return false;
    });
    
    $('form#recovery-form').submit(function(){
        var $this = $(this);
        
        $this.find(".email-container").removeClass("has-error");
        $this.find(".email-container .form-control-message").remove();
        
        $.post($this.attr('action'), $this.serialize(), function(data){
            $('input', $this).removeAttr('disabled');
            if (!data.status)
            {
                $( '<span class="form-control-message">'+data.message+'</span>' ).insertAfter($this.find(".email-container").addClass("has-error").find("input"));
            } else {
                $( '<div class="form-group recovery-success">'+data.message+'</div>' ).insertBefore($this.find(".email-container"));
            }
        }, 'json');
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
    
    $("#_id-city-select").change(function(){
        $("#city-select-value").val($(this).val());
        $("#city-select-form").submit();
    });
    
});