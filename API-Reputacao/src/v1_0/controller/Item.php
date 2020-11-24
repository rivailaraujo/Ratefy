<?php

namespace App\v1_0\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class Item
{

    public function CadastrarItem(Request $request, Response $response, $args)
    {
        $dao_item = new \DaoItem();
        $dao_aplicacao = new \DaoAplicacao();
        $item = new \ItemClass();
        $employe = $request->getParsedBody();
        //return $response->withJson("Tetando", 200)->withHeader('Content-type', 'application/json');
        if (empty(isset($employe['name'])) || empty(isset($employe['key'])) || empty(isset($employe['type']))) {
            $myArr = array("Erro" => "Item passado Inválido");
            var_dump($employe);
            $response->getBody()->write(json_encode($myArr));
            return $response;
        } else {


            $item->setTipo($employe['type']);
            $item->setNome_item($employe['name']);
            $item->setAplicacao($employe['key']);

            $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
            //var_dump($item);
            if ($item->getAplicacao() > 0) {
                if ($dao_item->InsertItem($item) == 'success') {
                    return $response->withJson("Item cadastrado com sucesso!", 200)->withHeader('Content-type', 'application/json');
                } else {
                    return $response->withJson("Erro", 422)->withHeader('Content-type', 'application/json');
                }
            } else {
                return $response->withJson($item->getAplicacao(), 422)->withHeader('Content-type', 'application/json');
            }
        }
    }
    public function GetStatus(Request $request, Response $response, $args)
    {

        $dao_item = new \DaoItem();
        $dao_aplicacao = new \DaoAplicacao();
        $employe = $request->getParsedBody();
        $item = new \ItemClass();
        $item->setAplicacao($args['YOUR_KEY']);
        $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
        //$dao_aplicacao = new \DaoAplicacao();
        if ($item->getAplicacao() > 0) {
            return $response->withJson($dao_item->GetStatus($item), 200)->withHeader('Content-type', 'application/json');
        } else {
            return $response->withJson("chave inválida", 403)->withHeader('Content-type', 'application/json');
        }
        //var_dump($item);
        
    }

    public function GetItem(Request $request, Response $response, $args)
    {

        $dao_item = new \DaoItem();
        $dao_aplicacao = new \DaoAplicacao();
        $employe = $request->getParsedBody();
        $item = new \ItemClass();
        $item->setAplicacao($args['YOUR_KEY']);
        $item->setId($args['ITEM_ID']);
        $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
        //$dao_aplicacao = new \DaoAplicacao();
        if ($item->getAplicacao() > 0) {
            return $response->withJson($dao_item->GetItem($item), 200)->withHeader('Content-type', 'application/json');
        } else {
            return $response->withJson("chave inválida", 403)->withHeader('Content-type', 'application/json');
        }
        //var_dump($item);
        
    }

    public function GetItemPorTipo(Request $request, Response $response, $args)
    {

        $dao_item = new \DaoItem();
        $dao_aplicacao = new \DaoAplicacao();
        $avaliacao = new \AvaliacaoClass();
        $item = new \ItemClass();
        $employe = $request->getParsedBody();
        $avaliacao->setTipo($args['TYPE']);
        $item->setAplicacao($args['YOUR_KEY']);
        $item->setId($args['ITEM_ID']);
        $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
        if($avaliacao->getTipo() > 0 && $avaliacao->getTipo() < 4 ){
            if ($item->getAplicacao() > 0) {
                $res = $dao_item->GetItemPorTipo($item, $avaliacao);
                if($res == "Id de item inválido ou tipo de avaliação não encontrado para o item"){
                    return $response->withJson($res, 400)->withHeader('Content-type', 'application/json');
                }else{
                    return $response->withJson($res, 200)->withHeader('Content-type', 'application/json');
                }
               
            } else {
                return $response->withJson("Chave inválida", 403)->withHeader('Content-type', 'application/json');
            }
        }else{
            return $response->withJson("Tipo Inválido", 400)->withHeader('Content-type', 'application/json');
        }
        //$dao_aplicacao = new \DaoAplicacao();
        
        //var_dump($item);
        
    }

    public function GetStatusTipo(Request $request, Response $response, $args)
    {
        $dao_item = new \DaoItem();
        $dao_aplicacao = new \DaoAplicacao();
        $employe = $request->getParsedBody();
        $item = new \ItemClass();
        $avaliacao = new \AvaliacaoClass();
        $avaliacao->setTipo($args['TYPE_RATE']);
        $item->setAplicacao($args['YOUR_KEY']);
        $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
        //$dao_aplicacao = new \DaoAplicacao();
        if ($item->getAplicacao() > 0) {
            if($avaliacao->getTipo() > 0 && $avaliacao->getTipo() < 4 ){
                return $response->withJson($dao_item->GetStatusTipo($item, $avaliacao), 200)->withHeader('Content-type', 'application/json');
            }else{
                return $response->withJson("Tipo Inválido", 400)->withHeader('Content-type', 'application/json');
            }
            
        } else {
            return $response->withJson("Chave inválida", 403)->withHeader('Content-type', 'application/json');
        }
        
    }
    public function DeleteItem(Request $request, Response $response, $args)
    {
        $dao_item = new \DaoItem();
        $item = new \ItemClass();
        $item->setId($args['id']);
        //var_dump($item);
        return $response->withJson($dao_item->DeleteItem($item), 200)->withHeader('Content-type', 'application/json');
    }


    public function AvaliarItem(Request $request, Response $response, $args)
    {
        $dao_item = new \DaoItem();
        $item = new \ItemClass();
        $dao_aplicacao = new \DaoAplicacao();
        $employe = $request->getParsedBody();
        if (empty(isset($employe['id_item'])) || empty(isset($employe['note'])) || empty(isset($employe['type'])) || empty(isset($employe['key'])) || empty(isset($employe['tipo_item']))) {
            //$myArr = array("Erro" => "Entrada Inválida");
           // var_dump($employe);
            //$response->getBody()->write(json_encode($myArr));
            return $response->withJson("Entrada inválida", 400)->withHeader('Content-type', 'application/json');
        }
        $item->setAplicacao($employe['key']);
        $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
        $item->setTipo($employe['tipo_item']);
        if($item->getAplicacao() == 'Erro, Chave inválida'){
            return $response->withJson("Erro, Chave inválida", 403)->withHeader('Content-type', 'application/json');
        }
        if($employe['type'] == 1){
            if ($employe['note'] > 5 || $employe['note'] < 1){
                return $response->withJson("Nota inválida para tipo de avaliação", 400)->withHeader('Content-type', 'application/json');
                }
        }else if ($employe['type'] == 2){
            if ($employe['note'] > 1 || $employe['note'] < 0){
            return $response->withJson("Nota inválida para tipo de avaliação", 400)->withHeader('Content-type', 'application/json');
            }
        }else if($employe['type'] == 3){
            if ($employe['note'] > 100 || $employe['note'] < 1){
                return $response->withJson("Nota inválida para tipo de avaliação", 400)->withHeader('Content-type', 'application/json');
                }
        }
        if ($item->getAplicacao() > 0) {
                $avaliacao = new \AvaliacaoClass();
                $avaliacao->setTipo($employe['type']);
                $avaliacao->setId_item($employe['id_item']);
                $avaliacao->setNota($employe['note']);
                $avaliacao->setId_projeto($item->getAplicacao());
                if ($dao_item->AvaliarItem($avaliacao, $employe['tipo_item']) == 'success') {
                    return $response->withJson($dao_item->GetAvaliacaoStatus($avaliacao), 200)->withHeader('Content-type', 'application/json');
                   // if ($dao_item->UpdateMedia($avaliacao)) {
                     //   return $response->withJson("Item avaliado!", 200)->withHeader('Content-type', 'application/json');
                   // }
                } else {
                    return $response->withJson("Erro em parâmetro passado", 422)->withHeader('Content-type', 'application/json');
                }
            } else {
                return $response->withJson($item->getAplicacao(), 422)->withHeader('Content-type', 'application/json');
            }
        
        
    }
}
