<?php

/**
 * Description of DaoUsuUsuario
 *
 * @author marcio
 */
class DaoUsuUsuario implements I_dao_usuario
{
    private $INSERT_USUARIO = "INSERT INTO `usuario` (`nome`, `imagem`,`email`, `senha`, `sexo`, `telefone1`, `ativo`, `criado`) VALUES (:nome, 'assets/img/default_user.jpg' ,:email, :senha, :sexo, :telefone1, 1, NOW());";
    private $RETORNA_SENHA = "SELECT `id`, `nome`, `email`, `perfil`  FROM `usuario` WHERE `senha` = :senha AND `email` = :email  LIMIT 1;";
    private $RETORNA_PERFIL = "SELECT `id`, `nome`, `imagem`, `email`, `perfil` FROM `usuario` WHERE id = :id ;";
    private $RETORNA_PERFIL_COMPLETO = "SELECT `id`, `nome`, `imagem`, `email`, `senha`, `sexo`, `data_nascimento`, `rg`, `cpf`, `telefone1`, `telefone2`, `telefone3`, `ativo`, `endereco`, `numero`, `complemento`, `bairro`, `cep`, `cidade`, `estado`, `criado`, `atualizado`, `perfil` FROM `usuario` WHERE id = :id ;";
    private $RETORNA_EMAIL = "SELECT `id`, `nome` FROM `usuario` WHERE `email` = :email LIMIT 1;";
    private $VERIFICA_SENHA = "SELECT `id` FROM `usuario` WHERE `id` = :id AND `senha` = :senha;";
    private $TROCA_SENHA = "UPDATE `usu_usuario` SET `senha` = :senha WHERE `id` = :id AND `ativo` = 1 ;";
    // private $ALTERAR_PERFIL = "UPDATE `usuario` SET  `nome` = :nome, `sexo` = :sexo, `rg` = :rg, `cpf` = :cpf, `telefone1` = :telefone1, `telefone2` = :telefone2, `telefone3` = :telefone3, `endereco` = :endereco, `numero` = :numero, `complemento` = :complemento, `bairro` = :bairro, `cep` = :cep, `cidade` = :cidade, `atualizado` =  NOW() WHERE `id` = :id;";
    private $ALTERAR_PERFIL = "UPDATE `usuario` SET  `nome` = :nome, `sexo` = :sexo, `rg` = :rg, `cpf` = :cpf, `telefone1` = :telefone1, `telefone2` = :telefone2, `telefone3` = :telefone3, `endereco` = :endereco, `numero` = :numero, `complemento` = :complemento, `bairro` = :bairro, `cep` = :cep, `cidade` = :cidade, `estado` = :estado, `atualizado` =  NOW() WHERE `id` = :id;";
    private $REMOVER_CONTA = "UPDATE `usuario` SET `ativo` = 0 WHERE `id` = :id ;";
    private $DESATIVAR_TOKENS = "UPDATE `sessoes` SET `expirado` = 1 , `data_expiracao` = NOW() WHERE `usuario` = :id ;";
    private $ALTERAR_FOTO_PERFIL = "UPDATE `usuario` SET `imagem` = :arquivo WHERE `id` = :id ;";
    private $RETORNA_TIPO_PERFIL = "SELECT `perfil` FROM `usuario` WHERE id = :id";
    private $POST_PERFIL_ADMIN = "UPDATE `usuario` SET `perfil` = 'admin' WHERE `id` = :id;";
    private $POST_PERFIL_MASTER = "UPDATE `usuario` SET `perfil` = 'master' WHERE `id` = :id;";
   // private $EXCLUIR_CONTA = "UPDATE `usuario` SET `ativo` = 1 WHERE `id` = :id ;";




    
    public function PostUsuario(\UsuUsuario $u)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->INSERT_USUARIO);
       
        //$query->bindValue(":id", $u->getId_tipo_usuario());
        $query->bindValue(":nome", $u->getNome());
        $query->bindValue(":email", $u->getEmail());
        $query->bindValue(":senha", $u->getSenha());
        $query->bindValue(":sexo", $u->getSexo());
        //    $query->bindValue(":cpf", $u->getCpf());
        //    $query->bindValue(":rg", $u->getRg());
        //    $query->bindValue(":cep", $u->getCep());
        //    $query->bindValue(":bairro", $u->getBairro());
        $query->bindValue(":telefone1", $u->getTelefone1());
        //$query->bindValue(":estado_civil", $u->getEstado_civil());


        //$query->bindValue(":classificacao", $u->getClassificacao());
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            return "success ok";
        } else {
            return "Erro ao cadastrar o Usuario";
        }
    }

   public function getTipoPerfil(\UsuUsuario $u)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_TIPO_PERFIL);
        $query->bindValue(":id", $u->getId());
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados = $rs;
                return $dados;
            }
        }
    }
    public function PostPerfil(\UsuUsuario $u)
    {
        //var_dump($u->getPerfil()->perfil);
        $conn = new conexao();
        $conexao = $conn->getConexao();
        if ($u->getPerfil()->perfil == 'master'){
        $query = $conexao->prepare($this->POST_PERFIL_MASTER);
        }else if($u->getPerfil()->perfil == 'admin'){
        $query = $conexao->prepare($this->POST_PERFIL_ADMIN);
        }else if ($u->getPerfil()->perfil == 'cliente'){
        $query = $conexao->prepare($this->POST_PERFIL_ADMIN);
        }
        $query->bindValue(":id", $u->getId());
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            return 'success';
        }else{
            return 'erro';
        }
        
    }



    

    public function ValidaSenha(\UsuUsuario $u, $versaoSenha, \Token $t)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_SENHA);
        $query->bindValue(":senha", $u->getSenha());
        $query->bindValue(":email", $u->getEmail());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            $token = new \Token();
            $dao_token = new \DaoToken();
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $u->setId($rs->id);
                $u->setPerfil($rs->perfil);
                if ($versaoSenha == 1) {
                    $rs->trocarSenha = "Trocar Senha";
                    $rs->token = $token->ConstruirToken($u, $t);
                } else {
                    $rs->trocarSenha = "NULL";
                    $rs->token = $token->ConstruirToken($u, $t);
                }
                $token->setToken($rs->token);
                $token->setId_usu_usuario($rs->id);
                $token->setPlayer_id($t->getPlayer_id());
                if ($dao_token->ValidaInsertToken($token) == "success") {
                    //echo "nao faz nada , o token já existe";
                } else {
                    //echo "deve adicionar o token";
                    $dao_token->InsertToken($token);
                }
                unset($rs->id);
                return $dados[] = $rs;
            }
        }
    }

    public function RetornaPerfil(\UsuUsuario $u)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_PERFIL);
        $query->bindValue(":id", $u->getId());
        $query->execute();
        $conn->fecharConexao();
        
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                if ($rs->imagem == null || $rs->imagem == "") {
                    $rs->imagem;
                } else {
                    $url_img = new \links_externos();
                    $rs->imagem = $url_img::$CAMINHO_IMG . (string) $rs->imagem;
                }
                $rs->id_usuario = $rs->id;
                $dados[] = $rs;
            }
            if (!empty($dados)) {
                return $dados;
            }
        } else {
            $myArr = array("Erro" => "Perfil nao encontrado");
            return $myArr;
        }
    }

    public function RetornaPerfilCompleto(\UsuUsuario $u)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_PERFIL_COMPLETO);
        $query->bindValue(":id", $u->getId());
        $query->execute();
        $conn->fecharConexao();
        
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                if ($rs->imagem == null || $rs->imagem == "") {
                    $rs->imagem;
                } else {
                    $url_img = new \links_externos();
                    $rs->imagem = $url_img::$CAMINHO_IMG . (string) $rs->imagem;
                }
                $rs->id_usuario = $rs->id;
                $dados[] = $rs;
            }
            if (!empty($dados)) {
                return $dados;
            }
        } else {
            $myArr = array("Erro" => "Perfil nao encontrado daoUso");
            return $myArr;
        }
    }

    // public function DesativarConta(\UsuUsuario $us)
    // {
    //     $conn = new conexao();
    //     $conexao = $conn->getConexao();
    //     $query = $conexao->prepare($this->EXCLUIR_CONTA);
    //     $query->bindValue(":id", $us->getId());
    //     $query->execute();
    //     $conn->fecharConexao();
    // }

    public function DesativarConta(\UsuUsuario $meu_usuario)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->REMOVER_CONTA);
        $query->bindValue(":id", $meu_usuario->getId());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            $temp = $this->DesativarALLToken($meu_usuario);
            if ($temp['mensagem'] == "success") {
                $myArr[] = array("mensagem" => "success");
            } else {
                $myArr[] = array("mensagem" => "ops..., ocorreu um erro");
            }
            return $myArr;
        } else {
            $myArr[] = array("mensagem" => "erro, nao foi possivel desativar");
            return $myArr;
        }
    }

    public function DesativarALLToken(\UsuUsuario $meu_usuario)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->DESATIVAR_TOKENS);
        $query->bindValue(":id", $meu_usuario->getId());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            $myArr[] = array("mensagem" => "success");
            return $myArr;
        } else {
            $myArr[] = array("mensagem" => "success");
            return $myArr;
        }
    }
    

    public function VerificaEmail(\UsuUsuario $u)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_EMAIL);
        $query->bindValue(":email", $u->getEmail());
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs;
                return $dados;
            }
        }
    }

   

   

    public function RetornaIdToken(\Token $t)
    {
        $token = new Token();
        $usuario = (object) $token->RetornaToken($t);
        return $usuario->id;
    }

   

   

    public function RetornaEmail(\UsuUsuario $u)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_EMAIL_TEMP);
        $query->bindValue(":id", $u->getId());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs->email;
            }
            return $dados;
        }
    }

    //verificando - para resposta de solicitação de amizade retornar valores invertidos
    

    
    public function TesteSenha(\UsuUsuario $meu_usuario)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->VERIFICA_SENHA);
        $query->bindValue(":id", $meu_usuario->getId());
        $query->bindValue(":senha", $meu_usuario->getSenha());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            return "success";
        } else {
            return "erro";
        }
    }

    public function TrocaSenha(\UsuUsuario $meu_usuario)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->TROCA_SENHA);
        $query->bindValue(":id", $meu_usuario->getId());
        $query->bindValue(":senha", $meu_usuario->getSenha());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            return "success";
        } else {
            return "erro";
        }
    }


    public function AlterarFotoPerfil(\UsuUsuario $meu_usuario)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->ALTERAR_FOTO_PERFIL);
        $query->bindValue(":id", $meu_usuario->getId());
        $query->bindValue(":arquivo", $meu_usuario->getImagem());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            $myArry[] = array("mensagem" => "success");
            return $myArry;
        } else {
            $myArry[] = array("mensagem" => "erro");
            return $myArry;
        }
    }


    public function RetornaPlayerId(\UsuUsuario $usuario)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_LISTA_PLAYER_ID);
        $query->bindValue(":id", $usuario->getId());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs->player_id;
            }
            return $dados;
        }
    }

    public function AlterarPerfil(\UsuUsuario $u)
    {
        $trocar_senha = 0;
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->ALTERAR_PERFIL);
        $query->bindValue(":id", $u->getId());
        $query->bindValue(":nome", $u->getNome());
        //$query->bindValue(":email", $u->getEmail());
        //retornar email anterir para resgatar o token
//        $dao_usuario_temp = new \DaoUsuUsuario();
//        $temp_email = new \UsuUsuario();
//        //atravez do id retorna o email
//        $temp = $dao_usuario_temp->RetornaEmail($u);
//        $temp_email->setEmail($temp["0"]);
//        $temp_email->setId($u->getId());
//        if(($u->getEmail() != '' || $u->getEmail() != null) && $u->getEmail() != $temp_email->getEmail()){
//            //alterar o token e retornar o token;
//            $token = new \Token();
//            $final_token = new \Token();
//            //token antigo para retornar o id do token a ser editado
//            $token->setToken($token->ConstruirToken($temp_email));
//            $dao_token = new \DaoToken();
//            $id_token = $dao_token->RetornaIdToken($token);
//            //----constroi o novo token
//            $final_token->setToken($final_token->ConstruirToken($u));
//            //verificar se teve sucesso
//            $resultado = $dao_token->AtualizarToken($final_token, $id_token["0"]);
//            //guarda o novo token
//            if($resultado["mensagem"] == "success"){
//                $trocar_senha = 1;
//            }else{
//                $trocar_senha = 0;
//            }
//        }else{
//            $trocar_senha = 0;
//        }
        //$query->bindValue(":imagem", $u->getImagem());

        $query->bindValue(":sexo", $u->getSexo()); //valor do banco M ou F
        $query->bindValue(":rg", $u->getRg());
        $query->bindValue(":cpf", $u->getCpf());
        $query->bindValue(":telefone1", $u->getTelefone1());
        $query->bindValue(":telefone2", $u->getTelefone2());
        $query->bindValue(":telefone3", $u->getTelefone3());
        $query->bindValue(":endereco", $u->getEndereco());
        $query->bindValue(":numero", $u->getNumero());
        $query->bindValue(":complemento", $u->getComplemento());
        $query->bindValue(":bairro", $u->getBairro());
        $query->bindValue(":cep", $u->getCep());
        $query->bindValue(":cidade", $u->getCidade());
        $query->bindValue(":estado", $u->getEstado());
        $query->execute();
        $conn->fecharConexao();


        if ($query) {
            if ($trocar_senha == 1) {
//                $myArr[] = array("mensagem" => "success", "token" => $final_token->getToken());
//                return $myArr;
            } else {
                $myArr[] = array("mensagem" => "success", "token" => "null");
                return $myArr;
            }
        } else {
            //reverte o token no banco de dados
            if ($trocar_senha == 1) {
                //$dao_token->AtualizarToken($token, $id_token["0"]);
            }
            $myArr[] = array("mensagem" => "erro", "token" => "null");
            return $myArr;
        }
    }


    
    public function RetornaPromocoesScroll(\ControlePag $c)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        // var_dump($c->getNum_pag());
        // $test = 'SELECT * FROM `promocao`  ORDER BY promocao.id LIMIT 10 OFFSET ' . $c->getInicio() . ';';

        $test = 'SELECT promocao.id, titulo , ativo, anexos.url AS imagem, categoria_promocao.id AS id_categoria, categoria_promocao.nome AS nome_categoria,
                estabelecimento.nome_fantasia, estabelecimento.endereco FROM `promocao`, `estabelecimento`,`categoria_promocao`, `promocao_tem_categoria`,
                `anexos`, `evento` WHERE promocao.id = anexos.promocao AND categoria_promocao.id = promocao_tem_categoria.categoria 
                AND promocao_tem_categoria.promocao = promocao.id AND promocao.estabelecimento = estabelecimento.id GROUP BY anexos.promocao
                ORDER BY promocao.id DESC
                LIMIT 10 OFFSET ' . $c->getInicio() . ';';

        $query = $conexao->prepare($test);
        $query->execute();
        $conn->fecharConexao();
        $promocao = new \UsuPromocao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                if ($rs->imagem == null || $rs->imagem== "") {
                    $rs->imagem;
                } else {
                    $url_img = new \links_externos();
                    $rs->imagem = $url_img::$CAMINHO_IMG_PROMOCAO . (string) $rs->imagem;
                }
                $dados[] = $rs;
            }
            if (!empty($dados)) {
                return $dados;
            }
        } else {
            $myArr = [];
            // $myArr  = -1;
            return $myArr;
        }
    }
}
