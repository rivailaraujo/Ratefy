function cadastro() {
    clearErrors();
    $("#form_user")[0].reset();
    $("#modal_user").modal();
} 

$(function(){
    $("#form_user").submit(function() {
        $.ajax({
            type: "POST",
            url: BASE_URL + "restrict/ajax_save_user",
            //url: BASE_URL + "application/views/template/header/ajax_save_user",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function() {
                clearErrors();
                $("#btn_save_user").siblings(".help-block").html(loadingImg("Verificando..."));
            },
            success: function(response) {
                clearErrors();
                if (response["status"]) {
                    $("#modal_user").modal("hide");
                    swal("Sucesso!","Usuário salvo com sucesso!", "success");
                    //dt_user.ajax.reload();
                } else {
                    showErrorsModal(response["error_list"])
                }
            }
        })
    
        return false;
    });
})


