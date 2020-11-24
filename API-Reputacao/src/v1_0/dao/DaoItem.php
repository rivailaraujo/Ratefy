<?php


class DaoItem{
    // procedimento pro banco de dados: 
    //DELIMITER $$
    // CREATE PROCEDURE getNota (IN item INT, IN projeto INT, IN tipoavaliacao INT)
        //     BEGIN
        //     IF tipoavaliacao = 1 THEN
        //     SELECT COUNT(*) FROM avaliacao WHERE nota = 5 AND avaliacao.item = item AND avaliacao.projeto_id = projeto AND tipo = 1 into @nota5;
        //     SELECT COUNT(*) FROM avaliacao WHERE nota = 4 AND avaliacao.item = item AND avaliacao.projeto_id = projeto AND tipo = 1 into @nota4;
        //     SELECT COUNT(*) FROM avaliacao WHERE nota = 3 AND avaliacao.item = item AND avaliacao.projeto_id = projeto AND tipo = 1 into @nota3;
        //     SELECT COUNT(*) FROM avaliacao WHERE nota = 2 AND avaliacao.item = item  AND avaliacao.projeto_id = projeto AND tipo = 1 into @nota2;
        //     SELECT COUNT(*) FROM avaliacao WHERE nota = 1 AND avaliacao.item = item AND avaliacao.projeto_id = projeto AND tipo = 1 into @nota1;
        //     SELECT @nota1 AS qtd_nota1, @nota2 AS qtd_nota2, @nota3 AS qtd_nota3, @nota4 AS qtd_nota4, @nota5 AS qtd_nota5;
        //     END IF;

            // IF tipoavaliacao = 2 THEN
            // SELECT COUNT(*) FROM avaliacao WHERE nota = 1 AND avaliacao.item = item AND avaliacao.projeto_id = projeto AND avaliacao.item = item AND tipo = 2 into @like;
            // SELECT COUNT(*) FROM avaliacao WHERE nota = 0 AND avaliacao.item = item AND avaliacao.item = item AND avaliacao.projeto_id = projeto AND tipo = 2 into @dislike;
        
            // SELECT @like AS qtd_like, @dislike AS qtd_dislike;
            // END IF;

        // END
    // DELIMITER;

    private $INSERT_ITEM= "INSERT INTO `item` (`id`, `data_insercao`, `nome_item`, `nota_media`, `aplicacao`, `tipo`) VALUES (NULL, NOW(), :nome_item, 0, :aplicacao, :tipo);";
    private $GET_STATUS = "SELECT COUNT(*) AS quantidade, COUNT(distinct item) AS qtd_items_avaliados, tipo_avaliacao.descricao AS tipo_avaliacao FROM avaliacao,tipo_avaliacao WHERE avaliacao.tipo = tipo_avaliacao.id AND projeto_id = :aplicacao GROUP BY tipo_avaliacao.descricao";
    private $GET_ITEM = "SELECT item AS id_item, tipo_avaliacao.id AS id_avaliacao ,tipo_avaliacao.descricao AS tipo_avaliacao,avg(nota) AS media, COUNT(nota) AS qtd_total ,MAX(nota) AS nota_maxima, MIN(nota) AS nota_minima FROM `avaliacao`, `tipo_avaliacao` WHERE avaliacao.tipo = tipo_avaliacao.id AND avaliacao.item = :item AND avaliacao.projeto_id = :aplicacao GROUP BY tipo_avaliacao ORDER BY tipo_avaliacao.id";
    private $GET_ITEM_POR_TIPO = "SELECT item AS id_item, tipo_avaliacao.id AS id_avaliacao ,tipo_avaliacao.descricao AS tipo_avaliacao,avg(nota) AS media, COUNT(nota) AS qtd_total ,MAX(nota) AS nota_maxima, MIN(nota) AS nota_minima FROM `avaliacao`, `tipo_avaliacao` WHERE avaliacao.tipo = tipo_avaliacao.id AND avaliacao.item = :item AND avaliacao.projeto_id = :aplicacao AND avaliacao.tipo = :avaliacao GROUP BY tipo_avaliacao ORDER BY tipo_avaliacao.id";
    private $GET_DETALHES_NOTA_FIVE_STARS = "call getNota(:item, :aplicacao, :tipo)";
    private $GET_STATUS_TIPO = "SELECT nota, item, tipo_avaliacao.descricao AS tipo_avaliacao, tipo_item FROM `avaliacao`, `tipo_avaliacao` WHERE tipo = tipo_avaliacao.id AND tipo = :tipo AND projeto_id = :aplicacao";
    private $INSERT_AVALIACAO = "INSERT INTO `avaliacao` (`id`, `nota`, `item`, `tipo`, `tipo_item`,`projeto_id`) VALUES (NULL, :nota, :item, :tipo, :tipo_item, :projeto_id);";
    private $UPDATE_MEDIA = "UPDATE `item` SET `nota_media` = (SELECT avg(nota) FROM avaliacao WHERE item = :item AND tipo = :tipo) WHERE `item`.`id` = :item";
    private $DELETE_ITEM = "UPDATE `item` SET `excluido` = '1' WHERE `item`.`id` = :iditem";
    private $GET_AVALIACAO_STATUS = "SELECT avg(nota) AS media , count(avaliacao.tipo) AS quantidade, tipo_avaliacao.descricao AS tipo_avaliacao, avaliacao.tipo_item FROM avaliacao, tipo_avaliacao WHERE item = :item AND tipo_avaliacao.id = :tipo AND avaliacao.tipo = :tipo AND projeto_id = :projeto_id";
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
 
    public function GetItem(\ItemClass $i){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_ITEM);
        $query->bindValue(":item", $i->getId());
        $query->bindValue(":aplicacao", $i->getAplicacao());
        $query->execute();
       // $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs;
                //  return $dados;
            }
             $j = 0;
             while ($j < count($dados)) {
                 if ($dados[$j]->id_avaliacao == 1){
                     //$conn = new conexao();
                     $query = $conexao->prepare($this->GET_DETALHES_NOTA_FIVE_STARS);
                     $query->bindValue(":item", $i->getId());
                     $query->bindValue(":aplicacao", $i->getAplicacao());
                     $query->bindValue(":tipo", 1);
                     $query->execute();
                     
                     if ($query->rowcount() > 0) {
                         while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                             $dados[$j]->detalhes_nota = $rs;
                             //  return $dados;
                         }
                     }
                     //return $dados;
                    unset($dados[$j]->id_avaliacao);
                 }if($dados[$j]->id_avaliacao == 2){
                     $query = $conexao->prepare($this->GET_DETALHES_NOTA_FIVE_STARS);
                     $query->bindValue(":item", $i->getId());
                     $query->bindValue(":aplicacao", $i->getAplicacao());
                     $query->bindValue(":tipo", 2);
                     $query->execute();
                     
                     if ($query->rowcount() > 0) {
                         while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                             $dados[$j]->detalhes_nota = $rs;
                             //  return $dados;
                         }
                     }
                    unset($dados[$j]->nota_minima);
                    unset($dados[$j]->nota_maxima);
                    unset($dados[$j]->media);
                    unset($dados[$j]->id_avaliacao);
                 }
                 if($dados[$j]->id_avaliacao == 3){
                    unset($dados[$j]->id_avaliacao);
                 }   
                $j++; 
             }
             $conn->fecharConexao();
             return $dados;
        }else{
            return "Id de item não encontrado para essa Aplicação";
        }
    }

    public function GetItemPorTipo(\ItemClass $i, \AvaliacaoClass $a){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_ITEM_POR_TIPO);
        $query->bindValue(":item", $i->getId());
        $query->bindValue(":aplicacao", $i->getAplicacao());
        $query->bindValue(":avaliacao", $a->getTipo());
        $query->execute();
       // $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados = $rs;
                if ($a->getTipo() == 1){
                    //$conn = new conexao();
                    $query = $conexao->prepare($this->GET_DETALHES_NOTA_FIVE_STARS);
                    $query->bindValue(":item", $i->getId());
                    $query->bindValue(":aplicacao", $i->getAplicacao());
                    $query->bindValue(":tipo", 1);
                    $query->execute();
                    
                    if ($query->rowcount() > 0) {
                        while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                            $dados->detalhes_nota = $rs;
                            //  return $dados;
                        }
                    }
                    //return $dados;
                   unset($dados->id_avaliacao);
                }else if($a->getTipo() == 2){
                    $query = $conexao->prepare($this->GET_DETALHES_NOTA_FIVE_STARS);
                    $query->bindValue(":item", $i->getId());
                    $query->bindValue(":aplicacao", $i->getAplicacao());
                    $query->bindValue(":tipo", 2);
                    $query->execute();
                    
                    if ($query->rowcount() > 0) {
                        while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                            $dados->detalhes_nota = $rs;
                            //  return $dados;
                        }
                    }
                   unset($dados->nota_minima);
                   unset($dados->nota_maxima);
                   unset($dados->media);
                   unset($dados->id_avaliacao);
                }
                else if($a->getTipo() == 3){
                   unset($dados->id_avaliacao);
                }   
                //  return $dados;
            }     
                 
        }else{
           // return $response->withJson("Id de item inválido ou tipo de avaliação não encontrado para o item", 403)->withHeader('Content-type', 'application/json');
            return "Id de item inválido ou tipo de avaliação não encontrado para o item";
        }
             $conn->fecharConexao();
             return $dados;
    }

    

    public function GetStatus(\ItemClass $i){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_STATUS);
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

    public function GetStatusTipo(\ItemClass $i, \AvaliacaoClass $a){
        
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->GET_STATUS_TIPO);
        $query->bindValue(":tipo",  $a->getTipo());
        $query->bindValue(":aplicacao", $i->getAplicacao());
        $query->execute();
        $conn->fecharConexao();
        if ($query->rowcount() > 0) {
            while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
                $dados[] = $rs;
            }
            return $dados;
        }else{
            return "Não há Item com esse id";
        }
    }
    
    public function AvaliarItem(\AvaliacaoClass $a, $tipo_item){
        $conn = new conexao();
        $conexao = $conn->getConexao();
        $query = $conexao->prepare($this->INSERT_AVALIACAO);
        $query->bindValue(":nota", $a->getNota());
        $query->bindValue(":item", $a->getId_item());
        $query->bindValue(":tipo", $a->getTipo());
        $query->bindValue(":projeto_id", $a->getId_projeto());
        $query->bindValue(":tipo_item", $tipo_item);
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

