<?php

//confirmar email de usuario cadastrado
$app->group('/v1', function () use ($app) {

    $app->group('/aplicacao', function () use ($app) {
        $app->post('/aplicacao', 'App\v1_0\controller\Aplicacao:CadastrarAplicacao'); // web ok
        $app->get('/aplicacoes', 'App\v1_0\controller\Aplicacao:GetAplicacoes'); // web ok
    }); 

    $app->post('/ratings', 'App\v1_0\controller\Item:AvaliarItem'); //rota ok apikey
    $app->get('/ratings', 'App\v1_0\controller\Item:GetStatus'); //rota ok apikey
    $app->get('/ratings/{TYPE_RATE}', 'App\v1_0\controller\Item:GetStatusTipo'); //rota ok apikey


    $app->get('/items/{ITEM_ID}', 'App\v1_0\controller\Item:GetItem'); //rota ok apikey
    $app->get('/items/{ITEM_ID}/{TYPE}', 'App\v1_0\controller\Item:GetItemPorTipo'); //rota ok apikey

    $app->group('/project', function () use ($app) {
        //$app->post('/item', 'App\v1_0\controller\Item:CadastrarItem'); // rota ok
        
         // rota ok -> falta ajustes por tipo de avaliacao e seguranca
        
        $app->delete('/items/{id}', 'App\v1_0\controller\Item:DeleteItem');
       // $app->get('/aplicacoes', 'App\v1_0\controller\Aplicacao:GetAplicacoes');
    }); //fim livre
});
