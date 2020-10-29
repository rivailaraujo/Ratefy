<?php

use Firebase\JWT\JWT;
class AplicacaoClass
{
    private $key = "@DESENVOLVIMENTO_@CLOUDIDEV_#2019#";
    private $nomeAplicacao;
    private $dataInicio;
    private $token;
    private $dominio;


    /**
     * Get the value of nomeAplicacao
     */ 
    public function getNomeAplicacao()
    {
        return $this->nomeAplicacao;
    }

    /**
     * Set the value of nomeAplicacao
     *
     * @return  self
     */ 
    public function setNomeAplicacao($nomeAplicacao)
    {
        $this->nomeAplicacao = $nomeAplicacao;

        return $this;
    }

    /**
     * Get the value of dataInicio
     */ 
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * Set the value of dataInicio
     *
     * @return  self
     */ 
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;

        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
    public function ConstruirToken(\AplicacaoClass $a) {
        $token = array(
            "nome_aplicacao" => $a->getNomeAplicacao(),
            "valor" => "12345678",
        );
        $jwt = JWT::encode($token, $this->key);
        $this->setToken($jwt);
        return $jwt;
    }

    public function RetornaToken(\ItemClass $t) {
        $decoded = JWT::decode($t->getAplicacao(), $this->key, array('HS256'));
        $dados = (array) $decoded;
        return $dados;
    }

    /**
     * Get the value of dominio
     */ 
    public function getDominio()
    {
        return $this->dominio;
    }

    /**
     * Set the value of dominio
     *
     * @return  self
     */ 
    public function setDominio($dominio)
    {
        $this->dominio = $dominio;

        return $this;
    }
}
