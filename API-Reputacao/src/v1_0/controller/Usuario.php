<?php

/**
 *   Para erros de dados
 * 400 para quando as informações solicitadas estiverem incompletas ou mal formadas.
 * Ex. throw new \Exception("Este campo é obrigatório", 400);
 *
 * 422 para quando as informações solicitadas estiverem corretas, mas inválidas.
 * Ex. throw new \Exception("Campo no formato inválido", 422);
 *
 * 404 para quando tudo está bem, mas o recurso não existe.
 * Ex. throw new \Exception("Esta página não existe", 404);
 *
 * 409 para quando existe um conflito de dados, mesmo com informações válidas.
 * Ex. throw new \Exception("Este dado já existe na base de dados", 409);
 *
 *   Para erros de autenticação
 * 401 para quando um token de acesso não é fornecido ou é inválido.
 * Ex. throw new \Exception("Usuário não permitido", 401);
 *
 * 403 para quando um token de acesso é válido, mas requer mais privilégios.
 * Ex. throw new \Exception("Erro de permissão para este usuário", 403);
 *
 *   Para status padrão
 * 200 para quando tudo estiver bem.
 * Ex. throw new \Exception("Sucesso!", 200);
 *
 * 204 para quando tudo estiver bem, mas não há conteúdo para retornar.
 * Ex. throw new \Exception("Não há conteúdo para exibir", 204);
 *
 * 500 para quando o servidor lança um erro, completamente inesperado.
 * Ex. throw new \Exception("Erro inesperado", 500);
 */

namespace App\v1_0\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Http\UploadedFile;

/**
 * Description of Usuario
 *
 */
class Usuario
{

    //ok - Faltando envio do email
    public function Cadastro(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        if (empty(isset($employe['nome'])) || empty(isset($employe['email'])) || empty(isset($employe['senha']))) {
            $myArr[] = array("Erro" => "Verifique se todos os dados foram preenchidos");
            return $response->withJson($myArr, 400)->withHeader('Content-type', 'application/json');
        }
        $usu_usuario = new \UsuUsuario();
        $dao_usu_usuario = new \DaoUsuUsuario();
        $usu_usuario->setEmail($employe['email']);
        if ($dao_usu_usuario->VerificaEmail($usu_usuario) != null) {
            return $response->withJson(["Erro" => "Este e-mail já possui cadastro na rede social!"], 400)->withHeader('Content-type', 'application/json');
        } else {
            //converter a senha p/ sha256
            $usu_usuario->setSenha($usu_usuario->ConverteSenha_256($employe['senha']));
            // $usu_usuario->setId_tipo_usuario(2);
            $usu_usuario->setNome($employe['nome']);
            // $usu_usuario->setCpf($employe['cpf']);
            // $usu_usuario->setRg($employe['rg']);
            //$usu_usuario->setEstado_civil($employe['estado_civil']);
            $usu_usuario->setSexo($employe['sexo']);
            $usu_usuario->setTelefone1($employe['telefone1']);
            // $usu_usuario->setCep($employe['cep']);
            // $usu_usuario->setBairro($employe['bairro']);
            //$usu_usuario->setClassificacao($employe['classificacao']);
            return $response->withJson([$dao_usu_usuario->PostUsuario($usu_usuario)], 200)->withHeader('Content-type', 'application/json');
        }
    }

    public function EditarPerfil(Request $request, Response $response, $args)
    {
        $employe = (object) $request->getParsedBody();
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $token->setToken($args['id']);
        $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
        $usu_usuario->setNome($employe->nome);
        $usu_usuario->setSexo($employe->sexo);
        $usu_usuario->setRg($employe->rg);
        $usu_usuario->setCpf($employe->cpf);
        $usu_usuario->setTelefone1($employe->telefone1);
        $usu_usuario->setTelefone2($employe->telefone2);
        $usu_usuario->setTelefone3($employe->telefone3);
        $usu_usuario->setEndereco($employe->endereco);
        $usu_usuario->setNumero($employe->numero);
        $usu_usuario->setComplemento($employe->complemento);
        $usu_usuario->setBairro($employe->bairro);
        $usu_usuario->setCep($employe->cep);
        $usu_usuario->setCidade($employe->cidade);
        $usu_usuario->setEstado($employe->estado);
        $temp = $dao_usuario->AlterarPerfil($usu_usuario);
        if ($temp['0']['mensagem'] == "success") {
            return $response->withJson($dao_usuario->AlterarPerfil($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            return $response->withJson($dao_usuario->AlterarPerfil($usu_usuario), 422)->withHeader('Content-type', 'application/json');
        }
    }

    public function ExcluirConta(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $dao_token = new \DaoToken();
        $token = new \Token();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->DesativarConta($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function DeixardeParticipar(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $token->setToken($args['id']);
        $dao_token = new \DaoToken();
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->DesativarConta($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //OK - retorna os dados do usuario. =>passagem de parametro para troca do usuario
    public function GetUsuario(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaPerfil($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Perfil nao encontrado");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function GetUsuarioCompleto(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaPerfilCompleto($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Perfil nao encontrado");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //OK
    public function GetPublicacoes(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $controlepag = new \ControlePag();
        if (!isset($employe['pagina'])) {
            $controlepag->setInicio(0);
        } else {
            $controlepag->setNum_pag($employe["pagina"]);
            $inicio = (int) ((int) $controlepag->getNum_pag() * 10);
            $controlepag->setInicio($inicio);
        }
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $token->setToken($args['id']);
        $dao_token = new \DaoToken();
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaPostUsuario($usu_usuario, $controlepag), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //OK
    public function GetComunidades(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $token->setToken($args['id']);
        $dao_token = new \DaoToken();
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaComunidadesUsuario($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

   

    //ok
    public function GetAmigos(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $controlepag = new \ControlePag();
        if (!isset($employe['pagina'])) {
            $controlepag->setInicio(0);
        } else {
            $controlepag->setNum_pag($employe["pagina"]);
            $inicio = (int) ((int) $controlepag->getNum_pag() * 10);
            $controlepag->setInicio($inicio);
        }
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaAmigos($usu_usuario, $controlepag), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //ok - com carregamento de pag
    public function GetFotos(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $controlepag = new \ControlePag();
        if (!isset($employe['pagina'])) {
            $controlepag->setInicio(0);
        } else {
            $controlepag->setNum_pag($employe["pagina"]);
            $inicio = (int) ((int) $controlepag->getNum_pag() * 10);
            $controlepag->setInicio($inicio);
        }
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $dao_fotos = new \DaoFotos();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_fotos->RetornaFotosUsuario($usu_usuario, $controlepag), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //ok
    public function GetPerfil(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaPerfilCompleto($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

   

    public function Qtd_amigos(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->QtdAmigos($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function Qtd_comunidades(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->QtdComunidades($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function QtdPostNLidos(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->QtdPostNaoLidos($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //---------------------------perfis publicos-------------------
    public function Qtd_amigos_public(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($employe['id_amigo']);
            return $response->withJson($dao_usuario->QtdAmigos($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function Qtd_comunidades_public(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($employe['id_amigo']);
            return $response->withJson($dao_usuario->QtdComunidades($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function GetPublicacoes_public(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $controlepag = new \ControlePag();
        if (!isset($employe['pagina'])) {
            $controlepag->setInicio(0);
        } else {
            $controlepag->setNum_pag($employe["pagina"]);
            $inicio = (int) ((int) $controlepag->getNum_pag() * 10);
            $controlepag->setInicio($inicio);
        }
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($employe['id_amigo']);
            return $response->withJson($dao_usuario->RetornaPostUsuario($usu_usuario, $controlepag), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //ok - com carregamento de pag
    public function GetFotos_public(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $controlepag = new \ControlePag();
        if (!isset($employe['pagina'])) {
            $controlepag->setInicio(0);
        } else {
            $controlepag->setNum_pag($employe["pagina"]);
            $inicio = (int) ((int) $controlepag->getNum_pag() * 10);
            $controlepag->setInicio($inicio);
        }
        $usu_usuario = new \UsuUsuario();
        $dao_fotos = new \DaoFotos();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($employe['id_amigo']);
            return $response->withJson($dao_fotos->RetornaFotosUsuario($usu_usuario, $controlepag), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //OK - retorna os dados do usuario. =>passagem de parametro para troca do usuario
    public function GetUsuario_public(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_amigo = new \UsuUsuario();
        $meu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $meu_usuario->setId($dao_usuario->RetornaIdToken($token));
            $usu_amigo->setId($employe['id_amigo']);
            return $response->withJson($dao_usuario->RetornaPerfilCompletoConsulta($usu_amigo, $meu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function Notificacoes(Request $request, Response $response, $args)
    {
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->Notificacoes($usu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function PedidoAmizade(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_relacionamento = new \UsuRelacionamento();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_relacionamento->setId_usuario_solicitante($dao_usuario->RetornaIdToken($token));
            $usu_relacionamento->setId_usuario_solicitado($employe['id_amigo']);
            //verificar se ja possui um pedido em aberto para depois prosseguir
            $temp = $dao_usuario->VerificaSolicitacaoAmizade($usu_relacionamento);
            if ($temp['0']['mensagem'] == "success") {
                // ja possui solicitação, nao faz nada
                $myArr[] = array("Erro" => "Uma solicitação ja foi enviada, agarde seu amigo responder.");
                return $response->withJson($myArr, 409)->withHeader('Content-type', 'application/json');
            } else {
                return $response->withJson($dao_usuario->SolicitarAmizade($usu_relacionamento), 200)->withHeader('Content-type', 'application/json');
            }
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca.");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //adicionar aqui uma verificação para saber se o usuario tem solicitaçoes pendes de algum lado
    //remover solicitaçoes, no caso de aceite e remover tambem as notificção
    public function RespostaAmizade(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_relacionamento = new \UsuRelacionamento();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_relacionamento->setId($employe['id_solicitacao']);
            $usu_relacionamento->setId_usuario_solicitante($dao_usuario->RetornaIdToken($token));
            $usu_relacionamento->setId_usuario_solicitado($dao_usuario->RetornaIdUsuarioSolicitado($usu_relacionamento));
            $usu_relacionamento->setStatus($employe['status']);
            return $response->withJson($dao_usuario->RespostaAmizade($usu_relacionamento), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //ok
    public function ListaAmigos(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $controlepag = new \ControlePag();
        if (!isset($employe['pagina'])) {
            $controlepag->setInicio(0);
        } else {
            $controlepag->setNum_pag($employe["pagina"]);
            $inicio = (int) ((int) $controlepag->getNum_pag() * 10);
            $controlepag->setInicio($inicio);
        }
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($employe['id_amigo']);
            return $response->withJson($dao_usuario->RetornaAmigos($usu_usuario, $controlepag), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    //OK
    public function GetComunidades_public(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_usuario = new \UsuUsuario();
        $meu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $token->setToken($args['id']);
        $dao_token = new \DaoToken();
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($employe['id_amigo']);
            $meu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaComunidadesUsuarioPublico($usu_usuario, $meu_usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function ListaAmizade(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $controlepag = new \ControlePag();
        if (!isset($employe['pagina'])) {
            $controlepag->setInicio(0);
        } else {
            $controlepag->setNum_pag($employe["pagina"]);
            $inicio = (int) ((int) $controlepag->getNum_pag() * 10);
            $controlepag->setInicio($inicio);
        }
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $token->setToken($args['id']);
        $dao_token = new \DaoToken();
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            return $response->withJson($dao_usuario->RetornaListaAmizade($usu_usuario, $controlepag), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    // public function DeixardeParticipar(Request $request, Response $response, $args) {
    //     $usu_usuario = new \UsuUsuario();
    //     $dao_usuario = new \DaoUsuUsuario();
    //     $token = new \Token();
    //     $token->setToken($args['id']);
    //     $dao_token = new \DaoToken();
    //     if ($dao_token->ValidaInsertToken($token) == "success") {
    //         $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
    //         return $response->withJson($dao_usuario->DesativarConta($usu_usuario), 200)->withHeader('Content-type', 'application/json');
    //     } else {
    //         $myArr[] = array("Erro" => "Não e possivel realizar a busca");
    //         return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
    //     }
    // }


    

    public function TrocarSenha(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_usuario = new \UsuUsuario();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $token->setToken($args['id']);
        $dao_token = new \DaoToken();
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_usuario->setId($dao_usuario->RetornaIdToken($token));
            //teste senha antiga antes de prosseguir a troca (teste sha_256)
            $usu_usuario->setSenha($usu_usuario->ConverteSenha_256($employe['senha_antiga']));
            if ($dao_usuario->TesteSenha($usu_usuario) == "success") {
                //verificação teve sucesso, troca a senha
                $usu_usuario->setSenha($usu_usuario->ConverteSenha_256($employe['nova_senha']));
                return $response->withJson($dao_usuario->TrocaSenha($usu_usuario), 200)->withHeader('Content-type', 'application/json');
            } else {
                //teste senha antiga antes de prosseguir a troca (teste sha_1)
                $usu_usuario->setSenha($usu_usuario->ConverteSenha_1($employe['senha_antiga']));
                if ($dao_usuario->TesteSenha($usu_usuario) == "success") {
                    $usu_usuario->setSenha($usu_usuario->ConverteSenha_256($employe['nova_senha']));
                    return $response->withJson($dao_usuario->TrocaSenha($usu_usuario), 200)->withHeader('Content-type', 'application/json');
                } else {
                    $myArr[] = array("Erro" => "Senha não confere, não foi possivel realizar a trocar");
                    return $response->withJson($myArr, 422)->withHeader('Content-type', 'application/json');
                }
            }
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function CancelarPedido(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_relacionamento = new \UsuRelacionamento();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_relacionamento->setId_usuario_solicitante($dao_usuario->RetornaIdToken($token));
            $usu_relacionamento->setId_usuario_solicitado($employe['id_amigo']);
            return $response->withJson($dao_usuario->CancelarSolicitacaoAmizade($usu_relacionamento), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function VerificarSolicitacaoAmizade(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_relacionamento = new \UsuRelacionamento();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_relacionamento->setId_usuario_solicitante($dao_usuario->RetornaIdToken($token));
            $usu_relacionamento->setId_usuario_solicitado($employe['id_amigo']);
            return $response->withJson($dao_usuario->VerificaSolicitacaoAmizade($usu_relacionamento), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function DesfazerAmizade(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $usu_relacionamento = new \UsuRelacionamento();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usu_relacionamento->setId_usuario_solicitante($dao_usuario->RetornaIdToken($token));
            $usu_relacionamento->setId_usuario_solicitado($employe['id_amigo']);
            return $response->withJson($dao_usuario->DesfazerSolicitacaoAmizade($usu_relacionamento), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function LerNotificacao(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $notificacao = new \GerNotificacao();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $notificacao->setId($employe['id']);
            return $response->withJson($dao_usuario->LerNotificacao($notificacao), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function PostDesejado(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $post = new \PosPost();
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $post->setId($employe['post']);
            return $response->withJson($dao_usuario->RetornaUmPost($post), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function EditarFotoPerfil(Request $request, Response $response, $args)
    {
        $directory = __DIR__ . '/../../../uploads/perfil';
        $uploadedFiles = $request->getUploadedFiles();
        @$uploadedFile = $uploadedFiles['arquivo'];
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            if ($uploadedFile == null) {
                $myArr[] = array("Erro" => "Arquivo não pode ser vazio!");
                return $response->withJson($myArr, 400)->withHeader('Content-type', 'application/json');
            }
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $arquivo_foto = new Publicacoes();
                $filename = $arquivo_foto->moveUploadedFile($directory, $uploadedFile);
                if ($filename != "" || $filename != null) {
                    //adicionar no banco passando id e a imagem
                    $usuario = new \UsuUsuario();
                    $usuario->setId($dao_usuario->RetornaIdToken($token));
                    $link_img = new \links_externos();
                    $caminho_img = $link_img::$CAMINHO_RELATIVO_PASTA_PERFIL_IMG . $filename;
                    $usuario->setImagem($caminho_img);
                    return $response->withJson($dao_usuario->AlterarFotoPerfil($usuario), 200)->withHeader('Content-type', 'application/json');
                }
            }
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }

    public function EditarFotoCapa(Request $request, Response $response, $args)
    {
        $directory = __DIR__ . '/../../../uploads/capa';
        $uploadedFiles = $request->getUploadedFiles();
        @$uploadedFile = $uploadedFiles['arquivo'];
        $dao_usuario = new \DaoUsuUsuario();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            if ($uploadedFile == null) {
                $myArr[] = array("Erro" => "Arquivo não pode ser vazio!");
                return $response->withJson($myArr, 400)->withHeader('Content-type', 'application/json');
            }
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $arquivo_foto = new Publicacoes();
                $filename = $arquivo_foto->moveUploadedFile($directory, $uploadedFile);
                if ($filename != "" || $filename != null) {
                    //adicionar no banco passando id e a imagem
                    $usuario = new \UsuUsuario();
                    $usuario->setId($dao_usuario->RetornaIdToken($token));
                    $link_img = new \links_externos();
                    $caminho_img = $link_img::$CAMINHO_RELATIVO_PASTA_FOTO_CAPA . $filename;
                    $usuario->setFoto_capa($caminho_img);
                    return $response->withJson($dao_usuario->AlterarFotoCapa($usuario), 200)->withHeader('Content-type', 'application/json');
                }
            }
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }
    
    //altera a foto de capa
    public function AlteraFotoCapa(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        $token = new \Token();
        $dao_token = new \DaoToken();
        $dao_usuario = new \DaoUsuUsuario();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $usuario = new \UsuUsuario();
            $usuario->setId($dao_usuario->RetornaIdToken($token));
            $usuario->setFoto_capa($employe["foto"]);
            return $response->withJson($dao_usuario->AlterarFotoCapa($usuario), 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }
    
    //retorna a lista de fotos de capa a url e o valor para salvar no banco
    public function FotosCapa(Request $request, Response $response, $args)
    {
        $token = new \Token();
        $dao_token = new \DaoToken();
        $token->setToken($args['id']);
        if ($dao_token->ValidaInsertToken($token) == "success") {
            $link_externo = new \links_externos();
            //carrega as imagens
            foreach ($link_externo::$FOTOS_CAPA as $fotos) {
                $temp = array("arquivo" => $link_externo::$CAMINHO_IMG_CAPA . $fotos, "referencia" => $fotos);
                $dados[] = $temp;
            }
            return $response->withJson($dados, 200)->withHeader('Content-type', 'application/json');
        } else {
            $myArr[] = array("Erro" => "Não e possivel realizar a busca");
            return $response->withJson($myArr, 401)->withHeader('Content-type', 'application/json');
        }
    }
}
