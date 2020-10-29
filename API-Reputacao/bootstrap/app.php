<?php

session_start();

require __DIR__ .'/../vendor/autoload.php';

$configs = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$container = new \Slim\Container($configs);

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $c['response']->withStatus($statusCode)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(["message" => $exception->getMessage()], $statusCode);
    };
};
$app = new \Slim\App($container);

require __DIR__ .'/../app/routes.php';
//conexao
require_once __DIR__ .'/../src/conn/Conexao.php';
//interfaces

require_once __DIR__ .'/../src/v1_0/interfaces/I_dao_token.php';
require_once __DIR__ .'/../src/v1_0/interfaces/I_dao_usuario.php';

//classes
require_once __DIR__ .'/../src/v1_0/classes/Token.php';
require_once __DIR__ .'/../src/v1_0/classes/AplicacaoClass.php';
require_once __DIR__ .'/../src/v1_0/classes/ItemClass.php';
require_once __DIR__ .'/../src/v1_0/classes/AvaliacaoClass.php';


//dao
require_once __DIR__ .'/../src/v1_0/dao/DaoToken.php';
require_once __DIR__ .'/../src/v1_0/dao/DaoAplicacao.php';
require_once __DIR__ .'/../src/v1_0/dao/DaoUsuUsuario.php';
require_once __DIR__ .'/../src/v1_0/dao/DaoItem.php';
//controler
require_once __DIR__ .'/../src/v1_0/controller/Aplicacao.php';
require_once __DIR__ .'/../src/v1_0/controller/Usuario.php';
require_once __DIR__ .'/../src/v1_0/controller/Item.php';
//----------------------------------------------------

//----------------------------------------------------
