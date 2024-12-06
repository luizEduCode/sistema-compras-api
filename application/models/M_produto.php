<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_produto extends CI_Model
{

    // Método para inserir um novo produto
    public function inserir($descricao, $unid_medida, $estoq_minimo, $estoq_maximo, $usucria)
    {
        try {
            // Verifica se a unidade de medida existe
            $this->db->where('sigla', $unid_medida); // Atualizado para 'sigla'
            $this->db->where('estatus', '');
            $query = $this->db->get('unid_medida');

            if ($query->num_rows() == 0) {
                return array('codigo' => 4, 'msg' => 'Unidade de medida não encontrada.');
            }

            // Query de inserção
            $sql = "INSERT INTO produtos (descricao, unid_medida, estoq_minimo, estoq_maximo, usucria) 
                VALUES ('$descricao', '$unid_medida', $estoq_minimo, $estoq_maximo, '$usucria')";

            $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                // Salvar log
                $this->load->model('M_log');
                $log_result = $this->M_log->inserirLog($usucria, $sql);

                if ($log_result['codigo'] == 1) {
                    return array('codigo' => 1, 'msg' => 'Produto inserido com sucesso.');
                } else {
                    return array('codigo' => 8, 'msg' => 'Produto inserido, mas houve problema ao salvar o log.');
                }
            } else {
                return array('codigo' => 6, 'msg' => 'Erro ao inserir produto.');
            }
        } catch (Exception $e) {
            return array('codigo' => 0, 'msg' => 'Erro: ' . $e->getMessage());
        }
    }

    public function consultar($cod_produto, $descricao, $unid_medida, $usucria)
    {
        try {
            $sql = "SELECT * FROM produtos WHERE estatus = ''";

            if (trim($cod_produto) != '') {
                $sql .= " AND cod_produto = '$cod_produto'";
            }

            if (trim($descricao) != '') {
                $sql .= " AND descricao LIKE '%$descricao%'";
            }

            if (trim($unid_medida) != '') {
                $sql .= " AND unid_medida = '$unid_medida'";
            }

            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) {
                return array('codigo' => 1, 'msg' => 'Consulta realizada com sucesso.', 'dados' => $query->result());
            } else {
                return array('codigo' => 6, 'msg' => 'Dados não encontrados.');
            }
        } catch (Exception $e) {
            return array('codigo' => 0, 'msg' => 'Erro ao consultar: ' . $e->getMessage());
        }
    }

    public function alterar($cod_produto, $descricao, $unid_medida, $estoq_minimo, $estoq_maximo, $usucria)
    {
        try {
            // Verifica se o produto existe
            $this->db->where('cod_produto', $cod_produto);
            $this->db->where('estatus', '');
            $query = $this->db->get('produtos');

            if ($query->num_rows() == 0) {
                return array('codigo' => 6, 'msg' => 'Produto não encontrado para alterar.');
            }

            // Monta os dados que serão atualizados
            $data = array();

            // Caso alguma validação seja necessária, faça antes:
            // Por exemplo, se umid_medida foi informada, verificar se ela existe
            if (trim($unid_medida) != '') {
                $this->db->where('sigla', $unid_medida);
                $this->db->where('estatus', '');
                $um_query = $this->db->get('unid_medida');

                if ($um_query->num_rows() == 0) {
                    return array('codigo' => 4, 'msg' => 'Unidade de medida não encontrada.');
                }

                $data['unid_medida'] = $unid_medida;
            }

            if (trim($descricao) != '') {
                $data['descricao'] = $descricao;
            }

            if (trim($estoq_minimo) != '') {
                $data['estoq_minimo'] = (int)$estoq_minimo;
            }

            if (trim($estoq_maximo) != '') {
                $data['estoq_maximo'] = (int)$estoq_maximo;
            }

            // Se não houve nenhum campo alterado
            if (count($data) == 0) {
                return array('codigo' => 3, 'msg' => 'Nenhum parâmetro de alteração informado.');
            }

            $this->db->where('cod_produto', $cod_produto);
            $this->db->update('produtos', $data);

            if ($this->db->affected_rows() > 0) {
                // Salvar log
                $this->load->model('M_log');
                $sql = $this->db->last_query();
                $log_result = $this->M_log->inserirLog($usucria, $sql);

                if ($log_result['codigo'] == 1) {
                    return array('codigo' => 1, 'msg' => 'Produto alterado com sucesso.');
                } else {
                    return array('codigo' => 8, 'msg' => 'Produto alterado, mas houve problema ao salvar o log.');
                }
            } else {
                return array('codigo' => 6, 'msg' => 'Não foi possível alterar o produto ou nenhum campo foi modificado.');
            }
        } catch (Exception $e) {
            return array('codigo' => 0, 'msg' => 'Erro ao alterar produto: ' . $e->getMessage());
        }
    }

    public function desativar($cod_produto, $usucria)
    {
        try {
            // Verifica se o produto existe
            $this->db->where('cod_produto', $cod_produto);
            $this->db->where('estatus', '');
            $query = $this->db->get('produtos');

            if ($query->num_rows() == 0) {
                return array('codigo' => 6, 'msg' => 'Produto não encontrado para desativar.');
            }

            $this->db->where('cod_produto', $cod_produto);
            $this->db->update('produtos', array('estatus' => 'D'));

            if ($this->db->affected_rows() > 0) {
                // Salvar log
                $this->load->model('M_log');
                $sql = $this->db->last_query();
                $log_result = $this->M_log->inserirLog($usucria, $sql);

                if ($log_result['codigo'] == 1) {
                    return array('codigo' => 1, 'msg' => 'Produto desativado com sucesso.');
                } else {
                    return array('codigo' => 8, 'msg' => 'Produto desativado, mas houve problema ao salvar o log.');
                }
            } else {
                return array('codigo' => 6, 'msg' => 'Não foi possível desativar o produto.');
            }
        } catch (Exception $e) {
            return array('codigo' => 0, 'msg' => 'Erro ao desativar produto: ' . $e->getMessage());
        }
    }

}