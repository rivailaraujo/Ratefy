<?php

//confirmar email de usuario cadastrado
$app->group('/v1.0', function () use ($app) {
    $app->group('/aplicacao', function () use ($app) {
        $app->post('/aplicacao', 'App\v1_0\controller\Aplicacao:CadastrarAplicacao'); // web ok
        $app->get('/aplicacoes', 'App\v1_0\controller\Aplicacao:GetAplicacoes'); // web ok
    }); 

    $app->group('/project', function () use ($app) {
        $app->post('/item', 'App\v1_0\controller\Item:CadastrarItem'); // rota ok
        $app->get('/items/{YOUR_KEY}', 'App\v1_0\controller\Item:GetItems'); // rota ok
        $app->post('/avaliarItem', 'App\v1_0\controller\Item:AvaliarItem'); // rota ok -> falta ajustes por tipo de avaliacao e seguranca
        $app->get('/item/{id}', 'App\v1_0\controller\Item:GetItem');
        $app->delete('/item/{id}', 'App\v1_0\controller\Item:DeleteItem');
       // $app->get('/aplicacoes', 'App\v1_0\controller\Aplicacao:GetAplicacoes');
    }); //fim livre
});
