<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_usuario extends CI_Model
{

    public function inserir($usuario, $senha, $nome, $tipo_usuario, $usu_sistema)
    {
        // $this->db->query("insert into usuarios (usuario, senha, nome, tipo)
        // values('$usuario', md5('$senha)', '$nome', '$tipo_usuario')");

        $retorno = $this->db->query("select * from usuarios where usuario = '$usuario'");

        if ($retorno->num_rows() > 0) {
            $dados = array('codigo' => 7, 'msg' => 'Usuário já existe');
        } else {
            $sql = "insert into usuarios (usuario, senha, nome, tipo)
            values('$usuario', md5('$senha'), '$nome', '$tipo_usuario')";
            
            $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                
                //chama
                $this->load->model('m_log');

                $retorno_log = $this->m_log->inserir_log($usu_sistema, $sql);

                if($retorno_log['codigo'] == 1){
                    $dados = array('codigo' => 1, 'msg' => 'Usuario cadastrado corretamente');
                } else {
                    $dados = array('codigo' => 9, 'msg' => 'Houve algum problema no salvamento do Log, porem, usuário cadastrado corretamente');
                }
                
            } else {
                $dados = array('codigo' => 6, 'msg' => 'Houve algum problema na inserção na tabela de usuários');
            }
        }

        return $dados;
    }

    public function deletar($usuario)
    {
        $this->db->query("delete from usuarios where usuario = '$usuario'");

        if ($this->db->affected_rows() > 0) {
            $dados = array('codigo' => 1, 'msg' => 'Usuário deletado com sucesso');
        } else {
            $dados = array('codigo' => 6, 'msg' => 'Houve algum problema na exclusão da tabela de usuários');
        }
        return $dados;
    }

    public function atualizasenha($usuario, $senhaAtual, $senhaNova)
    {
        $this->db->query("update usuarios set senha = md5('$senhaNova') where usuario = '$usuario' and senha = md5('$senhaAtual')");

        if ($this->db->affected_rows() > 0) {
            $dados = array('codigo' => 1, 'msg' => 'Senha atualizada com sucesso');
        } else {
            $dados = array('codigo' => 5, 'msg' => 'Houve algum problema na atualização da senha');
        }
        return $dados;
    }

    public function consultar($usuario, $nome, $tipo_usuario)
    {
        $sql = "select * from usuarios where state = '' ";

        if (trim($usuario) != '') {
            $sql = $sql . "and usuario = '$usuario' ";
        }

        if (trim($tipo_usuario) != '') {
            $sql = $sql . "and tipo = '$tipo_usuario' ";
        }

        if (trim($nome) != '') {
            $sql = $sql . "and nome like '%$nome%' ";
        }

        $retorno = $this->db->query($sql);

        if ($retorno->num_rows() > 0) {
            $dados = array('codigo' => 1, 'msg' => 'Consulta efetuada com sucesso', 'dados' => $retorno->result());
        } else {
            $dados = array('codigo' => 6, 'msg' => "Dados não encontrados");
        }

        return $dados;
    }

    public function alterar($usuario, $nome, $senha, $tipo_usuario, $usu_sistema)
    {
        
        //verifica se usuario existe 
        $this->db->query("select * from usuarios where usuario = '$usuario' and senha = md5('$senha') ");        
        
        if ($this->db->affected_rows() > 0) {
            // se usuário encontrado -> tenta alterar usuario
           
            $this->db->query("select * from usuarios where usuario = '$usuario'  and state = 'D'");

            if ($this->db->affected_rows() > 0) {
                $dados = array('codigo' => 6, 'msg' => 'Não foi possível fazer a atualização pois o usuário foi DELETADO');
                
            } else {
                $sql = "update usuarios set nome = '$nome', tipo = '$tipo_usuario' where usuario = '$usuario' and senha = md5('$senha') ";

                $this->db->query($sql);

                if($this->db->affected_rows() > 0) {

                    $this->load->model('m_log');
                    $retorno_log = $this->m_log->inserir_log($usu_sistema, $sql);

                    if($retorno_log['codigo'] == 1){
                        $dados = array('codigo' => 1, 'msg' => "Usuário ATUALIZADO com sucesso");
                    } else {
                        $dados = array('codigo' => 4, 'msg' => 'Houve algum problema no salvamento do Log, porem, usuário ATUALIZADO corretamente');
                    }
                }else{
                    $dados = array ('codigo' => 8, 'msg' => "Houve um problema durante atualização"); 
                }
            }

        } else {
            $dados = array('codigo' => 7, 'msg' => 'Usuario não cadastrado ou senha invalida');
        }

        return $dados;
    }

    // public function alterar($usuario, $nome, $senha, $tipo_usuario)
    // {
        
    //     //verifica se usuario existe 
    //     $retorno = $this->verificaUsuario($usuario, $senha);
        
    //     if ($retorno['codigo'] == 7) {
    //         // se usuário encontrado -> tenta alterar usuario
    //        $this->db->query("update usuarios set nome = '$nome', tipo = '$tipo_usuario' where usuario = '$usuario' and senha = md5('$senha') ");
            
    //        if($this->db->affected_rows() > 0) {
    //             $dados = array('codigo' => 1, 'msg' => "Usuário ATUALIZADO com sucesso");
    //        }else{
    //            $dados = array ('codigo' => 8, 'msg' => "Houve um problema durante atualização"); 
    //        }           

    //     } else {
    //         $dados = $retorno;
    //     }

    //     return $dados;
    // }

    // public function verificaUsuario($usuario, $senha){
    //     $this->db->query("select * from usuarios where usuario = '$usuario' and senha = md5('$senha') ");    
        
    //     if ($this->db->affected_rows() > 0) {
    //         if($this->db->result()->row('state') == 'D' ){                
    //             $dados = array('codigo' => 6, 'msg' => "Usuário existente, mas consta como inativo na base dados");
    //         }else{
    //             $dados = array('codigo' => 7, 'msg' => "Usuário existente.");
    //         }
    //     }else{
    //         $dados = array('codigo' => 8, 'msg' => "Usuário inválido.");
    //     }

    //     return $dados;
    // }  


    public function desativar($usuario, $usu_sistema)
    {
        $sql = "update usuarios set state = 'D' where usuario = '$usuario'";

        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {

            $this->load->model('m_log');
            $retorno_log = $this->m_log->inserir_log($usu_sistema, $sql);

            if($retorno_log['codigo'] == 1){
                $dados = array('codigo' => 1, 'msg' => 'Usuario desativado corretamente');
            } else {
                $dados = array('codigo' => 4, 'msg' => 'Houve algum problema no salvamento do Log, porem, usuário desativado corretamente');
            }
            
        } else {
            $dados = array('codigo' => 5, 'msg' => 'Houve algum problema na desativação do usuario');
        }

        return $dados;
    }

    public function ativar($usuario, $usu_sistema)
    {
        $sql = "update usuarios set state = '' where usuario = '$usuario'";

        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {

            $this->load->model('m_log');
            $retorno_log = $this->m_log->inserir_log($usu_sistema, $sql);

            if($retorno_log['codigo'] == 1){
                $dados = array('codigo' => 1, 'msg' => 'Usuario ativado corretamente');
            } else {
                $dados = array('codigo' => 4, 'msg' => 'Houve algum problema no salvamento do Log, porem, usuário ativado corretamente');
            }
            
        } else {
            $dados = array('codigo' => 5, 'msg' => 'Houve algum problema na ativação do usuario');
        }

        return $dados;
    }
}
