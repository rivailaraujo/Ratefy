<?php

/**
 * Description of Token
 *
 * @author marcio
 */
use Firebase\JWT\JWT;

interface I_Token {

    public function ConstruirToken(\UsuUsuario $u, \Token $t);

    public function RetornaToken(\Token $t);
}

class Token implements I_Token {

    private $key = "@DESENVOLVIMENTO_@CLOUDIDEV_#2019#";
    private $token;
    private $data_expiracao;
    private $expirado;
    private $id_usu_usuario;
    private $player_id;

    function getPlayer_id() {
        return $this->player_id;
    }

    function setPlayer_id($player_id) {
        $this->player_id = $player_id;
    }

    function getToken() {
        return $this->token;
    }

    function getData_expiracao() {
        return $this->data_expiracao;
    }

    function getExpirado() {
        return $this->expirado;
    }

    function getId_usu_usuario() {
        return $this->id_usu_usuario;
    }

    function setToken($token) {
        $this->token = $token;
    }

    function setData_expiracao($data_expiracao) {
        $this->data_expiracao = $data_expiracao;
    }

    function setExpirado($expirado) {
        $this->expirado = $expirado;
    }

    function setId_usu_usuario($id_usu_usuario) {
        $this->id_usu_usuario = $id_usu_usuario;
    }

    public function ConstruirToken(\UsuUsuario $u, \Token $t) {
        $token = array(
            "email" => $u->getEmail(),
            "id" => $u->getId(),
            "player_id" => $t->getPlayer_id(),
            "perfil" => $u->getPerfil()
        );
        $jwt = JWT::encode($token, $this->key);
        return $jwt;
    }

    public function RetornaToken(\Token $t) {
        $decoded = JWT::decode($t->getToken(), $this->key, array('HS256'));
        $dados = (array) $decoded;
        return $dados;
    }

}
