<?php


class ItemClass
{
    private $id;
    private $data_insercao;
    private $nota_media;
    private	$nome_item;
    private $aplicacao;
    private $tipo;

    public function getDataInsercao()
    {
        return $this->data_insercao;
    }

    /**
     * Set the value of nomeAplicacao
     *
     * @return  self
     */ 
    public function setDataInsercao($data_insercao)
    {
        $this->data_insercao = $data_insercao;

        return $this;
    }
   

    /**
     * Get the value of nota_media
     */ 
    public function getNota_media()
    {
        return $this->nota_media;
    }

    /**
     * Set the value of nota_media
     *
     * @return  self
     */ 
    public function setNota_media($nota_media)
    {
        $this->nota_media = $nota_media;

        return $this;
    }

    /**
     * Get the value of nome_item
     */ 
    public function getNome_item()
    {
        return $this->nome_item;
    }

    /**
     * Set the value of nome_item
     *
     * @return  self
     */ 
    public function setNome_item($nome_item)
    {
        $this->nome_item = $nome_item;

        return $this;
    }

    /**
     * Get the value of aplicacao
     */ 
    public function getAplicacao()
    {
        return $this->aplicacao;
    }

    /**
     * Set the value of aplicacao
     *
     * @return  self
     */ 
    public function setAplicacao($aplicacao)
    {
        $this->aplicacao = $aplicacao;

        return $this;
    }

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
