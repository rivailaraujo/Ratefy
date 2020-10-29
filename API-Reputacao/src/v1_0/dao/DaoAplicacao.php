<?php


class DaoAplicacao{

    private $INSERT_APLICACAO= "INSERT INTO `aplicacao` (`id`, `nome_aplicacao`, `token`) VALUES ('', :nome_aplicacao, :token);";
    private $BUSCA_TOKEN = "SELECT * FROM `projetos` WHERE `chave` = :chave LIMIT 1;";
    private $BUSCA_ID_TOKEN = "SELECT projeto_id FROM `projetos` WHERE `chave` = :chave LIMIT 1;";
    private $GET_APLICACOES= "SELECT * FROM `aplicacao`";

    public function InsertAplicacao(\AplicacaoClass $a){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->INSERT_APLICACAO);
        $query->bindValue(":nome_aplicacao", $a->getNomeAplicacao());
        $query->bindValue(":token", $a->getToken());
        //$query->bindValue(":dominio", $a->getDominio());
        $query->execute();
        $conn->fecharConexao();
        if($query){
            return "success";
        }else{
            return "Erro ao registar Aplicação";
        }
    }
    public function RetornaIdToken(\ItemClass $i)
    {
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->BUSCA_ID_TOKEN);
        $query->bindValue(":chave", $i->getAplicacao());
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados = $rs;              
            }
            if(isset($dados)){
                return $dados->projeto_id;
            }else{
                return "Erro, Chave inválida";
            }
        }
    }

    public function ValidaToken(\AplicacaoClass $a){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->BUSCA_TOKEN);
        $query->bindValue(":token", $a->getToken());
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs;
                return $dados;
            }
        }
    } 
    public function GetAplicacoes(){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_APLICACOES);
        $query->execute();
        $conn->fecharConexao();
        if ($query) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs;
                return $dados;
            }
        }
    }         
}

