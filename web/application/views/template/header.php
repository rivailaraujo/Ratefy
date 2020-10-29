<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="favicon.ico">
		<title>Lattes - Onepage Multipurpose Bootstrap HTML</title>
		<!-- Bootstrap core CSS -->
		<link href="<?php echo base_url(); ?>public/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<!-- Custom styles for this template -->
		<link href="<?php echo base_url(); ?>public/css/owl.carousel.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>public/css/owl.theme.default.min.css"  rel="stylesheet">
		<link href="<?php echo base_url(); ?>public/css/style.css" rel="stylesheet">
		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<script src="<?php echo base_url(); ?>public/js/ie-emulation-modes-warning.js"></script>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<?php 
		class header extends CI_Controller{
			function __construct()
			 {
				 parent::__construct();
				 $this->load->library("session");
			 }
			 function index()
			 {
				 if ($this->session->userdata("id_usuario")) {
					 $data = array(
						 "scripts" => array(
							 "util.js",
							 "restrict.js",
						 )
					 );
				 } else {
					 $data = array(
						 "scripts" => array(
							 "util.js",
							 "login.js",
							 "cadastro.js"
						 )
					 );
					 //echo password_hash("admin", PASSWORD_DEFAULT);
					 //$this->load->model("users_model");
					 //print_r($this->users_model->get_user_data("admin"));
				 }
			 }
			 public function ajax_save_user() {

				if (!$this->input->is_ajax_request()) {
					exit("Nenhum acesso de script direto permitido!");
				}
		
				$json = array();
				$json["status"] = 1;
				$json["error_list"] = array();
		
				$this->load->model("users_model");
		
				$data = $this->input->post();
		
				if (empty($data["login_usuario"])) {
					$json["error_list"]["#login_usuario"] = "Login é obrigatório!";
				} else {
					if ($this->users_model->is_duplicated("login_usuario", $data["login_usuario"], $data["id_usuario"])) {
						$json["error_list"]["#login_usuario"] = "Login já existente!";
					}
				}
		
				if (empty($data["nome_completo"])) {
					$json["error_list"]["#nome_completo"] = "Nome Completo é obrigatório!";
				} 
		
				if (empty($data["email"])) {
					$json["error_list"]["#email"] = "E-mail é obrigatório!";
				} else {
					if ($this->users_model->is_duplicated("email", $data["email"], $data["id_usuario"])) {
						$json["error_list"]["#email"] = "E-mail já existente!";
					} else {
						if ($data["email"] != $data["email_confirm"]) {
							$json["error_list"]["#email"] = "";
							$json["error_list"]["#email_confirm"] = "E-mails não conferem!";
						}
					}
				}
		
				if (empty($data["senha"])) {
					$json["error_list"]["#senha"] = "Senha é obrigatório!";
				} else {
					if ($data["senha"] != $data["senha_confirm"]) {
						$json["error_list"]["#senha"] = "";
						$json["error_list"]["#senha_confirm"] = "Senha não conferem!";
					}
				}
		
				if (!empty($json["error_list"])) {
					$json["status"] = 0;
				} else {
		
					$data["password_hash"] = password_hash($data["senha"], PASSWORD_DEFAULT);
		
					unset($data["senha"]);
					unset($data["senha_confirm"]);
					unset($data["email_confirm"]);
		
					if (empty($data["id_usuario"])) {
						$this->users_model->insert($data);
					} else {
						$id_usuario = $data["id_usuario"];
						unset($data["id_usuario"]);
						$this->users_model->update($id_usuario, $data);
					}
				}
		
				echo json_encode($json);
			}
		}
			 
		if (isset($styles)){
			
			foreach ($styles as $style_name){
				$href = base_url() . "public/css/".	$style_name;?>
				<link href="<?=$href?>" rel="stylesheet">
			<?php }
		}?>

	</head>
	<body id="page-top">
		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-shrink navbar-fixed-top">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header page-scroll">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand page-scroll" href="#page-top"><img src="<?php echo base_url(); ?>public/images/logo.png" alt="Lattes theme logo"></a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li class="hidden">
							<a href="#page-top"></a>
						</li>
						<li>
							<a class="page-scroll" href="<?php echo base_url(); ?>#about">Sobre</a>
						</li>
						<!-- <li>
							<a class="page-scroll" href="#services">Services</a>
						</li> -->
						<li>
							<a class="page-scroll" href="<?php echo base_url(); ?>#portfolio">Portfolio</a>
						</li>
						<li>
							<a class="page-scroll" href="<?php echo base_url(); ?>#team">Time</a>
						</li>
						<li>
							<a class="page-scroll" href="<?php echo base_url(); ?>#contact">Contato</a>
						</li>
						<li id="from_7">
							<a class="page-scroll" href="<?php echo base_url(); ?>restrict" onclick="cadastro(); return false;">Cadastre-se</a>
						</li>
						<li>
							<a class="page-scroll" href="<?php echo base_url(); ?>restrict" >Login</a>
						</li>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
		<!-- Header -->
		<div id="modal_user" class="modal fade">

    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">x</button>
          <h4 class="modal-title">Cadastro de usuário</h4>
        </div>

        <div class="modal-body">
          <form id="form_user">

            <input id="id_usuario" name="id_usuario" hidden>

            <div class="form-group">
              <label class="col-lg-2 control-label">Login</label>
              <div class="col-lg-10">
                <input id="login_usuario" name="login_usuario" class="form-control" maxlength="30">
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">Nome Completo</label>
              <div class="col-lg-10">
                <input id="nome_completo" name="nome_completo" class="form-control" maxlength="100">
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">E-mail</label>
              <div class="col-lg-10">
                <input id="email" name="email" class="form-control" maxlength="100">
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">Confirmar E-mail</label>
              <div class="col-lg-10">
                <input id="email_confirm" name="email_confirm" class="form-control" maxlength="100">
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">Senha</label>
              <div class="col-lg-10">
                <input type="password" id="senha" name="senha" class="form-control">
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">Confirmar Senha</label>
              <div class="col-lg-10">
                <input type="password" id="senha_confirm" name="senha_confirm" class="form-control">
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group text-center">
              <button type="submit" id="btn_save_user" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar
              </button>
              <span class="help-block"></span>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>