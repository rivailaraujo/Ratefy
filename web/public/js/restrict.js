$(document).ready(function(){
    $('#from_7').remove();
	$("#login-menu")[0].innerHTML = 'Gerenciador';
});

$(function(){
    $("#btn_add_projeto").click(function(){
		clearErrors();
		$("#form_projeto")[0].reset();
		$("#projeto_img_path").attr("src", "");
		$("#modal_projeto").modal();
	});


    $("#btn_upload_projeto_img").change(function() {
		uploadImg($(this), $("#projeto_img_path"), $("#projeto_img"));
	});
	$(document).ready(function(){
    $('#from_7').remove();
});
	
    
    $("#form_projeto").submit(function() {

		$.ajax({
			type: "POST",
			url: BASE_URL + "restrict/ajax_save_projeto",
			dataType: "json",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_projeto").siblings(".help-block").html(loadingImg("Verificando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					console.log("entrou 1")
					$("#modal_projeto").modal("hide");
					 swal("Sucesso!","Projeto salvo com sucesso!", "success");
					 dt_projeto.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
					console.log("entrou 2")
				}
			}
		})

		return false;
	});

	$("#btn_your_user").click(function() {

		$.ajax({
			type: "POST",
			url: BASE_URL + "restrict/ajax_get_user_data",
			dataType: "json",
			data: {"id_usuario": $(this).attr("id_usuario")},
			success: function(response) {
				clearErrors();
				$("#form_user")[0].reset();
				$.each(response["input"], function(id, value) {
					$("#"+id).val(value);
				});
				$("#modal_user").modal();
			}
		})

		return false;
	});

	function active_btn_projeto() {
		
		$(".btn-edit-projeto").click(function(){
			$.ajax({
				type: "POST",
				url: BASE_URL + "restrict/ajax_get_projeto_data",
				dataType: "json",
				data: {"projeto_id": $(this).attr("projeto_id")},
				success: function(response) {
					clearErrors();
					$("#form_projeto")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$file = response["img"]["projeto_img_path"];
					if(response["img"]["projeto_img_path"] != BASE_URL){
						//console.log("entrou 1");
						$("#projeto_img_path").attr("src", response["img"]["projeto_img_path"]);
						$("#modal_projeto").modal();

					}else{
						//console.log("entrou 2");
						$("#projeto_img_path").attr("src", "");
						$("#modal_projeto").modal();
					}
					
					
				}
			})
		});


		$(".btn-del-projeto").click(function(){
			
			projeto_id = $(this);
			swal({
				title: "Atenção!",
				text: "Deseja deletar esse projeto?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d9534f",
				confirmButtonText: "Sim",
				cancelButtontext: "Não",
				closeOnConfirm: true,
				closeOnCancel: true,
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type: "POST",
						url: BASE_URL + "restrict/ajax_delete_projeto_data",
						dataType: "json",
						data: {"projeto_id": projeto_id.attr("projeto_id")},
						success: function(response) {
							swal("Sucesso!", "Projeto deletado com sucesso", "success");
							dt_projeto.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_projeto = $("#dt_projetos").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": BASE_URL + "restrict/ajax_list_projeto",
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		 "drawCallback": function() {
		 	active_btn_projeto();
		}
	});


})
