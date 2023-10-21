<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class usuario extends CI_Controller{
        public function inserir(){

            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;
            $senha = $resultado->senha;
            $nome = $resultado->nome;
            $tipo_usuario = strtoupper($resultado->tipo_usuario);

            $usu_sistema = strtoupper($resultado->usu_sistema);

            if(trim($usu_sistema) == ''){
                $retorno = array('codigo' => 8, 'msg' => 'Usuario do sistema não informado');
            }
            elseif(trim($usuario) == ''){
                $retorno = array('codigo' => 2, 'msg' => 'Usuario não informado');
            } elseif(trim($senha) == ''){
                $retorno = array('codigo' => 3, 'msg' => 'Senha não informada');
            } elseif(trim($nome) == ''){
                $retorno = array('codigo' => 4, 'msg' => 'Nome não informado');
            } elseif ((trim($tipo_usuario) != 'ADMINISTRADOR' && 
                       trim($tipo_usuario) != 'COMUM') || trim($tipo_usuario) == ''){

                $retorno = array('codigo' => 5, 'msg' => 'Tipo de usuário inválido');
            } else {
                $this->load->model('m_usuario');

                $retorno = $this->m_usuario->inserir($usuario, $senha, $nome, $tipo_usuario, $usu_sistema);
            }

            echo json_encode($retorno);
        }

        public function deletar(){
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;

            if(trim($usuario) == ''){
                $retorno = array('codigo' => 2, 'msg' => 'Usuario não informado');
            } else {
                $this->load->model('m_usuario');

                $retorno = $this->m_usuario->deletar($usuario);
            }

            echo json_encode($retorno);
        }

        public function atualizasenha(){
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;
            $senhaAtual = $resultado->senhaAtual;
            $senhaNova = $resultado->senhaNova;

            if(trim($senhaAtual) == ''){
                $retorno = array('codigo' => 4, 'msg' => 'Senha atual não informada');
            } elseif (trim($senhaNova) == ''){
                $retorno = array('codigo' => 3, 'msg' => 'Senha nova não informada');
            } elseif(trim($usuario) == ''){
                $retorno = array('codigo' => 2, 'msg' => 'Usuario não informado');
            } else {
                $this->load->model('m_usuario');

                $retorno = $this->m_usuario->atualizasenha($usuario, $senhaAtual, $senhaNova);
            }

            echo json_encode($retorno);
        }

        public function consultar(){
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;
            $nome = $resultado->nome;
            $tipo_usuario = strtoupper($resultado->tipo_usuario);

            if(trim($tipo_usuario) != 'ADMINISTRADOR' && trim($tipo_usuario) != 'COMUM' && trim($tipo_usuario) != ''){
                $retorno = array('codigo' => 5, 'msg' => 'Tipo de usuario inválido');
            } else {
                $this->load->model('m_usuario');

                $retorno = $this->m_usuario->consultar($usuario, $nome, $tipo_usuario);
            }

            echo json_encode($retorno);
        }

        public function alterar(){
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;
            $senha = $resultado->senha;
            $nome = $resultado->nome;
            $tipo_usuario = strtoupper($resultado->tipo_usuario);

            $usu_sistema = strtoupper($resultado->usu_sistema);

            if($usu_sistema == ''){
                $retorno = array('codigo' => 2, 'msg' => 'Usuario do sistema não informado');
            } elseif(trim($tipo_usuario) != 'ADMINISTRADOR' && trim($tipo_usuario) != 'COMUM' && trim($tipo_usuario) != ''){
                $retorno = array('codigo' => 6, 'msg' => 'Tipo de usuário invalido');
            } elseif(trim($nome) == ''){
                $retorno = array('codigo' => 3, 'msg' => 'Nome não informado');
            } elseif(trim($usuario) == ''){
                $retorno = array('codigo' => 4, 'msg' => 'Usuario não informada');
            } elseif(trim($senha) == ''){
                $retorno = array('codigo' => 5, 'msg' => 'Senha não informada');
            } else {
                $this->load->model('m_usuario');

                $retorno = $this->m_usuario->alterar($usuario, $nome, $senha, $tipo_usuario, $usu_sistema);
            }

            echo json_encode($retorno);
        }

        public function desativar(){
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;
            $usu_sistema = strtoupper($resultado->usu_sistema);

            if($usu_sistema == ''){
                $retorno = array('codigo' => 2, 'msg' => 'Usuario do sistema não informado');
            }elseif(trim($usuario == '')){
                $retorno = array('codigo' => 3, 'msg' => 'Usuario não informado');
            } else  {
                $this->load->model('m_usuario');

                $retorno = $this->m_usuario->desativar($usuario, $usu_sistema);
            }

            echo json_encode($retorno);
        }

        public function ativar(){
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $usuario = $resultado->usuario;
            $usu_sistema = strtoupper($resultado->usu_sistema);

            if($usu_sistema == ''){
                $retorno = array('codigo' => 2, 'msg' => 'Usuario do sistema não informado');
            }elseif(trim($usuario == '')){
                $retorno = array('codigo' => 3, 'msg' => 'Usuario não informado');
            } else  {
                $this->load->model('m_usuario');

                $retorno = $this->m_usuario->ativar($usuario, $usu_sistema);
            }

            echo json_encode($retorno);
        }
    }
?>