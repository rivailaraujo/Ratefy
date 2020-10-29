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
    public function GetItems(Request $request, Response $response, $args)
    {

        $dao_item = new \DaoItem();
        $dao_aplicacao = new \DaoAplicacao();
        $employe = $request->getParsedBody();
        $item = new \ItemClass();
        $item->setAplicacao($args['YOUR_KEY']);
        $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
        //$dao_aplicacao = new \DaoAplicacao();
        //var_dump($item);
        return $response->withJson($dao_item->GetItemsAplicacao($item), 200)->withHeader('Content-type', 'application/json');
    }

    public function GetItem(Request $request, Response $response, $args)
    {
        $dao_item = new \DaoItem();
        //$aplicacao = new \AplicacaoClass();
        $item = new \ItemClass();
        $item->setId($args['id']);
        //$dao_aplicacao = new \DaoAplicacao();
        return $response->withJson($dao_item->GetItem($item), 200)->withHeader('Content-type', 'application/json');
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
        if (empty(isset($employe['id_item'])) || empty(isset($employe['note'])) || empty(isset($employe['type'])) || empty(isset($employe['key']))) {
            $myArr = array("Erro" => "Entrada Inválida");
            var_dump($employe);
            $response->getBody()->write(json_encode($myArr));
            return $response;
        }
        $item->setAplicacao($employe['key']);
        $item->setAplicacao($dao_aplicacao->RetornaIdToken($item));
        if ($item->getAplicacao() > 0) {
            $avaliacao = new \AvaliacaoClass();
            $avaliacao->setTipo($employe['type']);
            $avaliacao->setId_item($employe['id_item']);
            $avaliacao->setNota($employe['note']);
            if ($dao_item->AvaliarItem($avaliacao) == 'success') {
                if ($dao_item->UpdateMedia($avaliacao)) {
                    return $response->withJson("Item avaliado!", 200)->withHeader('Content-type', 'application/json');
                }
            } else {
                return $response->withJson("Erro", 422)->withHeader('Content-type', 'application/json');
            }
        } else {
            return $response->withJson($item->getAplicacao(), 422)->withHeader('Content-type', 'application/json');
        }
    }
}
