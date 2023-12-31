<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class login extends CI_Controller {

        function index() {
            $this->load->view('index');
        }
        public function logar(){
            
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;
            $senha = $resultado->senha;

            if(trim($usuario) == ''){
                $retorno = array('codigo' => 2, 'msg' => 'Usuario não informado');
            } elseif (trim($senha) == ''){
                $retorno = array('codigo' => 3, 'msg' => 'Senha não informada');
            } else {
                $this->load->model('m_acesso');

                $retorno = $this->m_acesso->validalogin($usuario, $senha);
            }
            echo json_encode($retorno);
        }  
    }
?>