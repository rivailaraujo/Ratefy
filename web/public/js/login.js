$(function(){
    $("#login_form").submit(function(){
        $.ajax({
            type: "post",
            url: "http://localhost/web/restrict/ajax_login",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function(){
                clearErrors();
            $("#btn_login").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            success: function(json){
                if(json["status"] == 1){
                    clearErrors();
                    $("#btn_login").parent().siblings(".help-block").html(loadingImg("Logando..."))
                    window.location = "http://localhost/web/restrict";
                }else{
                    showErrors(json["error_list"]);
                }
            },
            error: function(response){
                console.log(response);
            }
        })
        return false;

    })
})