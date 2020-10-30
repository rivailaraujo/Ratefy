<?php


class AvaliacaoClass
{

    private $nota;
    private $id_item;
    private $tipo;
    private $id_projeto;


    /**
     * Get the value of nota
     */ 

    public function getId_projeto()
    {
        return $this->id_projeto;
    }

    /**
     * Set the value of nota
     *
     * @return  self
     */ 
    public function setId_projeto($id_projeto)
    {
        $this->id_projeto = $id_projeto;

        return $this;
    }

    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set the value of nota
     *
     * @return  self
     */ 
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get the value of id_item
     */ 
    public function getId_item()
    {
        return $this->id_item;
    }

    /**
     * Set the value of id_item
     *
     * @return  self
     */ 
    public function setId_item($id_item)
    {
        $this->id_item = $id_item;

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
}
