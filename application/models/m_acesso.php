<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class m_acesso extends CI_Model{
        public function validalogin($usuario, $senha){
            $retorno = $this->db->query("select * from usuarios where usuario = '$usuario' and senha = md5('$senha') and state = ''");

            if($retorno->num_rows() > 0){
                $dados = array('codigo' => 1, 'msg' => 'Usuario Correto');
            } else {
                $dados = array('codigo' => 4, 'msg' => 'Usuario ou senha errados');
            }

            return $dados;
        }
    }
?>