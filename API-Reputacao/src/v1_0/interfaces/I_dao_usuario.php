<?php

/**
 *
 */
interface I_dao_usuario {

    //adiciona novo usuario no banco de dados
    public function PostUsuario(UsuUsuario $u);
    
    //dispara a consulta com a senha ja cripitigrafada
    public function ValidaSenha(\UsuUsuario $u, $versaoSenha, \Token $t);

    //retorna os dados do usuario
    public function RetornaPerfil(UsuUsuario $u);
    


    public function VerificaEmail(UsuUsuario $u);
    
    


    public function RetornaIdToken(\Token $t);
    
   
    
    
    public function RetornaEmail(\UsuUsuario $u);
    
   
    
    public function TesteSenha(\UsuUsuario $meu_usuario);
    
    public function TrocaSenha(\UsuUsuario $meu_usuario);
    

    
    
    public function AlterarFotoPerfil(\UsuUsuario $meu_usuario);
    

    
}
