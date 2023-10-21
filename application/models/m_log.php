<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class m_log extends CI_Model{
        public function inserir_log($usuario, $comando){
            $dblog = $this->load->database('log', TRUE);

            $comando = troca_caracter($comando);

            $dblog->query("insert into log(usuario, comando) values('$usuario', '$comando')");

            if($dblog->affected_rows() > 0){
                $dados = array('codigo' => 1, 'msg' => 'log cadastrado com sucesso');
            } else {
                $dados = array('codigo' => 6, 'msg' => 'houve algum erro na inserção do log');
            }

            $dblog->close();

            return $dados;
        }
    }
?>