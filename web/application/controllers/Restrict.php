<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Restrict extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
    }
    public function index()
    {
        if ($this->session->userdata("id_usuario")) {
            $data = array(
                "styles" => array(
                    "dataTables.bootstrap.min.css",
                    "datatables.min.css"
				),
                "scripts" => array(
                    "sweetalert2.all.min.js",
                    "datatables.min.js",
					"dataTables.bootstrap.min.js",
                    "util.js",
                    "cadastro.js",
                    "restrict.js"
                ),
                "id_usuario" => $this->session->userdata("id_usuario")
            );
            $this->template->show("restrict.php", $data);
        } else {
            $data = array(
                "scripts" => array(
                    "util.js",
                    "login.js",
                    "cadastro.js",
                    "sweetalert2.all.min.js",
                )
            );
            //echo password_hash("admin", PASSWORD_DEFAULT);
            $this->template->show("login.php", $data);
            //$this->load->model("users_model");
            //print_r($this->users_model->get_user_data("admin"));
        }
    }

    public function logoff()
    {
        $this->session->sess_destroy();
        header("Location: " . base_url() . "restrict");
    }


    public function ajax_login()
    {
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        if (empty($username)) {
            $json["status"] = 0;
            $json["error_list"]["#username"] = "Usuário não pode ser vazio!";
        } else {
            $this->load->model("users_model");
            $result = $this->users_model->get_user_data($username);
            if ($result) {
                $id_usuario = $result->id_usuario;
                $password_hash = $result->senha_hash;
                if (password_verify($password, $password_hash)) {
                    $this->session->set_userdata("id_usuario", $id_usuario);
                } else {
                    $json["status"] = 0;
                }
            } else {
                $json["status"] = 0;
            }
            if ($json["status"] == 0) {
                $json["error_list"]["#btn_login"] = "Usuário e/ou senha incorretos!";
            }
        }
        echo json_encode($json);
    }

    public function ajax_import_image()
    {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }

        $config["upload_path"] = "./tmp/";
        $config["allowed_types"] = "gif|png|jpg|jpeg";
        $config["overwrite"] = TRUE;

        $this->load->library("upload", $config);

        $json = array();
        $json["status"] = 1;

        if (!$this->upload->do_upload("image_file")) {
            $json["status"] = 0;
            $json["error"] = $this->upload->display_errors("", "");
        } else {
            if ($this->upload->data()["file_size"] <= 1024) {
                $file_name = $this->upload->data()["file_name"];
                $json["img_path"] = base_url() . "tmp/" . $file_name;
            } else {
                $json["status"] = 0;
                $json["error"] = "Arquivo não deve ser maior que 1 MB!";
            }
        }

        echo json_encode($json);
    }

    public function ajax_save_projeto()
    {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }

        $json = array();
        $json["status"] = 1;
        $json["error_list"] = array();

        $this->load->model("projetos_model");

        $data = $this->input->post();

        if (empty($data["projeto_nome"])) {
            $json["error_list"]["#projeto_nome"] = "Nome do projeto é obrigatório!";
        } else {
            if ($this->projetos_model->is_duplicated("projeto_nome", $data["projeto_nome"], $data["projeto_id"])) {
                $json["error_list"]["#projeto_nome"] = "Nome de projeto já existente!";
            }
        }

        if (!empty($json["error_list"])) {
            $json["status"] = 0;
        } else {

            if (!empty($data["projeto_img"])) {

                $file_name = basename($data["projeto_img"]);
                $old_path = getcwd() . "/tmp/" . $file_name;
                $new_path = getcwd() . "/public/images/projetos/" . $file_name;
                rename($old_path, $new_path);

                $data["projeto_img"] = "/public/images/projetos/" . $file_name;
                //
            } else {
                unset($data["projeto_img"]);
            }

            if (empty($data["projeto_id"])) {
                $data["id_usuario"] = $this->session->userdata("id_usuario");
                $data["chave"] = md5(uniqid($this->session->userdata("login_usuario"), true));
                //$data["chave"] = password_hash(($this->session->userdata("nome_completo") + $data["projeto_nome"]), PASSWORD_DEFAULT);
                $this->projetos_model->insert($data);
            } else {
                $projeto_id = $data["projeto_id"];
                unset($data["projeto_id"]);
                $this->projetos_model->update($projeto_id, $data);
            }
        }

        echo json_encode($json);
    }
    public function ajax_save_user()
    {
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

            $data["senha_hash"] = password_hash($data["senha"], PASSWORD_DEFAULT);

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

    public function ajax_get_user_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$json = array();
		$json["status"] = 1;
		$json["input"] = array();

		$this->load->model("users_model");

		$id_usuario = $this->input->post("id_usuario");
		$data = $this->users_model->get_data($id_usuario)->result_array()[0];
		$json["input"]["id_usuario"] = $data["id_usuario"];
		$json["input"]["login_usuario"] = $data["login_usuario"];
		$json["input"]["nome_completo"] = $data["nome_completo"];
		$json["input"]["email"] = $data["email"];
		$json["input"]["email_confirm"] = $data["email"];
		$json["input"]["senha"] = "";
		$json["input"]["senha_confirm"] = "";
		echo json_encode($json);
    }

    public function ajax_get_projeto_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$json = array();
		$json["status"] = 1;
		$json["input"] = array();

		$this->load->model("projetos_model");

		$projeto_id = $this->input->post("projeto_id");
		$data = $this->projetos_model->get_data($projeto_id)->result_array()[0];
		$json["input"]["projeto_id"] = $data["projeto_id"];
		$json["input"]["projeto_nome"] = $data["projeto_nome"];
		$json["input"]["projeto_descricao"] = $data["projeto_descricao"];

		$json["img"]["projeto_img_path"] = base_url() . $data["projeto_img"];

		echo json_encode($json);
    }
    
    public function ajax_delete_projeto_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$json = array();
		$json["status"] = 1;

		$this->load->model("projetos_model");
		$projeto_id = $this->input->post("projeto_id");
		$this->projetos_model->delete($projeto_id);

		echo json_encode($json);
	}
    
    public function ajax_list_projeto() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$this->load->model("projetos_model");
		$projetos = $this->projetos_model->get_datatable();

		$data = array();
		foreach ($projetos as $projeto) {

			$row = array();
			$row[] = $projeto->projeto_nome;

			if ($projeto->projeto_img) {
				$row[] = '<img src="'.base_url().$projeto->projeto_img.'" style="max-height: 100px; max-width: 100px;">';
			} else {
				$row[] = "";
			}

			$row[] = '<div class="description">'.$projeto->projeto_descricao.'</div>';

            $row[] = $projeto->chave;

			$row[] = '<div style="display: inline-block;">
						<button class="btn btn-primary btn-edit-projeto" 
							projeto_id="'.$projeto->projeto_id.'">
							<i class="fa fa-edit"></i>
						</button>
						<button class="btn btn-danger btn-del-projeto" 
							projeto_id="'.$projeto->projeto_id.'">
							<i class="fa fa-times"></i>
						</button>
					</div>';

			$data[] = $row;

		}

		$json = array(
			"draw" => $this->input->post("draw"),
			"recordsTotal" => $this->projetos_model->records_total(),
			"recordsFiltered" => $this->projetos_model->records_filtered(),
			"data" => $data,
		);

		echo json_encode($json);
	}


}
