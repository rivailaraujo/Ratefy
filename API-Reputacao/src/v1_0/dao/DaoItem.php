<?php


class DaoItem{

    private $INSERT_ITEM= "INSERT INTO `item` (`id`, `data_insercao`, `nome_item`, `nota_media`, `aplicacao`, `tipo`) VALUES (NULL, NOW(), :nome_item, 0, :aplicacao, :tipo);";
    private $GET_ITEMS_APLICACAO = "SELECT * FROM `item` WHERE `aplicacao` = :aplicacao;";
    private $GET_ITEM = "SELECT * FROM `item` WHERE `id` = :id;";
    private $INSERT_AVALIACAO = "INSERT INTO `avaliacao` (`id`, `nota`, `item`, `tipo`, `projeto_id`) VALUES (NULL, :nota, :item, :tipo, :projeto_id);";
    private $UPDATE_MEDIA = "UPDATE `item` SET `nota_media` = (SELECT avg(nota) FROM avaliacao WHERE item = :item AND tipo = :tipo) WHERE `item`.`id` = :item";
    private $DELETE_ITEM = "UPDATE `item` SET `excluido` = '1' WHERE `item`.`id` = :iditem";
    private $GET_AVALIACAO_STATUS = "SELECT avg(nota) AS media , count(avaliacao.tipo) AS quantidade, tipo_avaliacao.descricao AS tipo_avaliacao FROM avaliacao, tipo_avaliacao WHERE item = :item AND tipo_avaliacao.id = :tipo AND avaliacao.tipo = :tipo AND projeto_id = :projeto_id";
    //private $BUSCA_TOKEN = "SELECT * FROM `aplicacao` WHERE `token` = :token LIMIT 1;";
   // private $GET_APLICACOES= "SELECT * FROM `aplicacao`";

    public function InsertItem(\ItemClass $i){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->INSERT_ITEM);
        $query->bindValue(":nome_item", $i->getNome_item());
        $query->bindValue(":aplicacao", $i->getAplicacao());
        $query->bindValue(":tipo", $i->getTipo());
        //$query->bindValue(":dominio", $a->getDominio());
        $query->execute();
        $conn->fecharConexao();
        if($query){
            return "success";
        }else{
            return "Erro ao registar item";
        }
    }
 
    public function GetItemsAplicacao(\ItemClass $i){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_ITEMS_APLICACAO);
        $query->bindValue(":aplicacao", $i->getAplicacao());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs;
                //  return $dados;
            }
            return $dados;
        }else{
            return "Não há Items para essa Aplicação";
        }
    }
    public function GetItem(\ItemClass $i){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_ITEM);
        $query->bindValue(":id", $i->getId());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados = $rs;
                return $dados;
            }
        }else{
            return "Não há Item com esse id";
        }
    }
    
    public function AvaliarItem(\AvaliacaoClass $a){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->INSERT_AVALIACAO);
        $query->bindValue(":nota", $a->getNota());
        $query->bindValue(":item", $a->getId_item());
        $query->bindValue(":tipo", $a->getTipo());
        $query->bindValue(":projeto_id", $a->getId_projeto());
        //$query->bindValue(":dominio", $a->getDominio());
        $query->execute();
        $conn->fecharConexao();
        if($query){
            return "success";
        }
        else{
            return "Erro ao registar item";
        }
    }

    public function GetAvaliacaoStatus(\AvaliacaoClass $a){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_AVALIACAO_STATUS);
        $query->bindValue(":item", $a->getId_item());
        $query->bindValue(":tipo", $a->getTipo());
        $query->bindValue(":projeto_id", $a->getId_projeto());
        //$query->bindValue(":dominio", $a->getDominio());
        $query->execute();
        $conn->fecharConexao();
    
        if ($query) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)){
                $dados = $rs;
                $dados->status = "success";
                return $dados;
            }
        }else{
            return "Erro";
        }
    }

    public function DeleteItem(\ItemClass $i){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        //var_dump($i);
        $query = $conexao->prepare($this->DELETE_ITEM);
        $query->bindValue(":iditem", $i->getId());
        //$query->bindValue(":dominio", $a->getDominio());
        $query->execute();
        $conn->fecharConexao();
        if($query){
            return "Item Deletado";
        }else{
            return "Erro ao deletar o item";
        }
    }

    public function UpdateMedia(\AvaliacaoClass $a){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->UPDATE_MEDIA);
        $query->bindValue(":item", $a->getId_item());
        $query->bindValue(":tipo", $a->getTipo());
        //$query->bindValue(":dominio", $a->getDominio());
        $query->execute();
        $conn->fecharConexao();
        if($query){
            return "success";
        }else{
            return "Erro ao registar item";
        }
    }
}

