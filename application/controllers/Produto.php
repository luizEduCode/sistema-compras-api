<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto extends CI_Controller
{

    // Método para inserir um novo produto
    public function inserir()
    {
        try {
            $json = file_get_contents('php://input');
            $dados = json_decode($json);

            // Lista de parâmetros obrigatórios
            $lista = array(
                "descricao" => '0',
                "unid_medida" => '0',
                "estoq_minimo" => '0',
                "estoq_maximo" => '0',
                "usucria" => '0'
            );

            if (verificarParam($dados, $lista) == 1) {
                $descricao = $dados->descricao;
                $unid_medida = $dados->unid_medida;
                $estoq_minimo = $dados->estoq_minimo;
                $estoq_maximo = $dados->estoq_maximo;
                $usucria = $dados->usucria;

                $this->load->model('M_produto');
                $retorno = $this->M_produto->inserir($descricao, $unid_medida, $estoq_minimo, $estoq_maximo, $usucria);
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos enviados não correspondem ao formato esperado. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'Erro ao processar a solicitação: ' . $e->getMessage()
            );
        }

        echo json_encode($retorno);
    }

    // Método para consultar produtos
    public function consultar()
    {
        try {
            $json = file_get_contents('php://input');
            $dados = json_decode($json);

            // Lista de parâmetros opcionais
            $lista = array(
                "cod_produto" => '',
                "descricao" => '',
                "unid_medida" => '',
                "usucria" => ''
            );

            if (verificarParam($dados, $lista) == 1) {
                $cod_produto = $dados->cod_produto ?? '';
                $descricao = $dados->descricao ?? '';
                $unid_medida = $dados->unid_medida ?? '';
                $usucria = $dados->usucria ?? '';

                $this->load->model('M_produto');
                $retorno = $this->M_produto->consultar($cod_produto, $descricao, $unid_medida, $usucria);
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos enviados não correspondem ao formato esperado. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'Erro ao processar a solicitação: ' . $e->getMessage()
            );
        }

        echo json_encode($retorno);
    }

    // Método para alterar um produto
    public function alterar()
    {
        try {
            $json = file_get_contents('php://input');
            $dados = json_decode($json);

            // Lista de parâmetros obrigatórios
            $lista = array(
                "cod_produto" => '0',
                "descricao" => '',
                "unid_medida" => '',
                "estoq_minimo" => '',
                "estoq_maximo" => '',
                "usucria" => '0'
            );

            if (verificarParam($dados, $lista) == 1) {
                $cod_produto = $dados->cod_produto;
                $descricao = $dados->descricao ?? '';
                $unid_medida = $dados->unid_medida ?? '';
                $estoq_minimo = $dados->estoq_minimo ?? '';
                $estoq_maximo = $dados->estoq_maximo ?? '';
                $usucria = $dados->usucria;

                $this->load->model('M_produto');
                $retorno = $this->M_produto->alterar($cod_produto, $descricao, $unid_medida, $estoq_minimo, $estoq_maximo, $usucria);
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos enviados não correspondem ao formato esperado. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'Erro ao processar a solicitação: ' . $e->getMessage()
            );
        }

        echo json_encode($retorno);
    }

    // Método para desativar um produto
    public function desativar()
    {
        try {
            $json = file_get_contents('php://input');
            $dados = json_decode($json);

            // Lista de parâmetros obrigatórios
            $lista = array(
                "cod_produto" => '0',
                "usucria" => '0'
            );

            if (verificarParam($dados, $lista) == 1) {
                $cod_produto = $dados->cod_produto;
                $usucria = $dados->usucria;

                $this->load->model('M_produto');
                $retorno = $this->M_produto->desativar($cod_produto, $usucria);
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos enviados não correspondem ao formato esperado. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'Erro ao processar a solicitação: ' . $e->getMessage()
            );
        }

        echo json_encode($retorno);
    }
}