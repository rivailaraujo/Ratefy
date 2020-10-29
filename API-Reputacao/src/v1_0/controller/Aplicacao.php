<?php

namespace App\v1_0\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class Aplicacao
{


    public function CadastrarAplicacao(Request $request, Response $response, $args)
    {
        $employe = $request->getParsedBody();
        if (empty(isset($employe['nome']))) {
            $myArr = array("Erro" => "Objeto de Configuração Vazia");
            $response->getBody()->write(json_encode($myArr));
            return $response;
        }
        $aplicacao = new \AplicacaoClass();
        $aplicacao->setNomeAplicacao($employe['nome']);
        $dao_aplicacao = new \DaoAplicacao();
        $aplicacao->ConstruirToken($aplicacao);
        if($dao_aplicacao->ValidaToken($aplicacao) != null){
            return $response->withJson($aplicacao->getToken(), 200)->withHeader('Content-type', 'application/json');
            
        }else {
            if ($dao_aplicacao->InsertAplicacao($aplicacao) == 'success') {
                return $response->withJson($aplicacao->getToken(), 200)->withHeader('Content-type', 'application/json');
            } else {
                return $response->withJson("Erro", 422)->withHeader('Content-type', 'application/json');
            }
        }  
    }
    public function GetAplicacoes(Request $request, Response $response, $args)
    {
        $aplicacao = new \AplicacaoClass();
        $token = new \AplicacaoClass();
        // $token->setPlayer_id($employe['player_id']);
        $dao_aplicacao = new \DaoAplicacao();
        $aplicacao->ConstruirToken($aplicacao);
        return $response->withJson($dao_aplicacao->GetAplicacoes(), 200)->withHeader('Content-type', 'application/json');
    }
}
