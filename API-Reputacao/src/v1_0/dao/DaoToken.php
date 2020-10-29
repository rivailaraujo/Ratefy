<?php

/**
 * Description of DaoToken
 *
 * @author marcio
 */
class DaoToken implements I_dao_token{

    private $RETORNA_TOKEN = "SELECT * FROM `sessoes` WHERE `token` = :token AND `expirado` = 0 LIMIT 1;";
    private $INSERT_TOKEN = "INSERT INTO `sessoes` (`token`, `usuario`, `player_id`, `criado`) VALUES (:token, :id, :player_id, NOW());";
    private $BUSCA_TOKEN = "SELECT * FROM `sessoes` WHERE `token` = :token AND `expirado` = 0 LIMIT 1;";
    private $REVOGA_TOKEN = "UPDATE `sessoes` SET `data_expiracao`= :data,`expirado`= 1 WHERE `token` = :token AND `expirado` = 0;";
    private $UPADATE_TOKEN = "UPDATE `sessoes` SET `token`= :token WHERE `id` = :posicao ;";
    //retorna a linha id do token a ser editado
    //private $POS_TOKEN = "SELECT * FROM `sessoes` WHERE `token` = :token AND `expirado` = 0 LIMIT 1;";
    
    public function ValidaUsuarioToken(\Token $t) {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->RETORNA_TOKEN);
        $query->bindValue(":token", $t->getToken());
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs;
            }
            return $dados;
        }
    }
    
    public function InsertToken(\Token $t){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->INSERT_TOKEN);
        $query->bindValue(":token", $t->getToken());
        $query->bindValue(":id", $t->getId_usu_usuario());
        $query->bindValue(":player_id", $t->getPlayer_id());
        $query->execute();
        $conn->fecharConexao();
        if($query){
            return "success";
        }else{
            return "Erro ao registar o Token";
        }
    }
    
    public function ValidaInsertToken(\Token $t) {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->BUSCA_TOKEN);
        $query->bindValue(":token", $t->getToken());
        $query->execute();
        $conn->fecharConexao();
        if($query->rowcount() > 0){
            return "success";
        }else{
            return "Pode registar";
        }
    }
    
    public function RetornaIdToken(\Token $t) {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->BUSCA_TOKEN);
        $query->bindValue(":token", (string)$t->getToken());
        $query->execute();
        $conn->fecharConexao();
        if($query->rowcount() > 0){
            while($rs = $query->fetch(PDO::FETCH_OBJ)){
                $dados[] = $rs->id;
            }
            return $dados;
        }
    }
    
    public function RevogarToken(\Token $t){
        //date_default_timezone_set('America/Belem');
        date_default_timezone_set('America/Santarem');
        $data = date('Y-m-d H:i:s');
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->REVOGA_TOKEN);
        $query->bindValue(":token", (string)$t->getToken());
        $query->bindValue(":data", $data);
        $query->execute();
        $conn->fecharConexao();
        if($query->rowcount() > 0){
            return "success";
        }else{
            return "Erro ao Sair";
        }
    }

    public function AtualizarToken(\Token $t, $pos){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->UPADATE_TOKEN);
        $query->bindValue(":token", $t->getToken());
        $query->bindValue(":posicao", $pos);
        $query->execute();
        $conn->fecharConexao();
        if($query){
            $rs = array("mensagem" => "success");
            return $rs;
        }else{
            $rs = array("mensagem" => "erro");
            return $rs;
        }
    }
}
