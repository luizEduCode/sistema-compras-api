<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_unidmedida extends CI_Model
{

    public function inserir($sigla, $descricao, $usuarioLogin)
    {
        try {
            // Verifica se o usuarioLogin existe na tabela usuarios
            $this->db->where('usuario', $usuarioLogin);
            $this->db->where('estatus', '');
            $query = $this->db->get('usuarios');
            if ($query->num_rows() == 0) {
                // Usuario não existe
                $dados = array(
                    'codigo' => 4,
                    'msg' => 'Usuário não existe na base de dados.'
                );
                return $dados;
            }

            // Verifica se já existe uma unidade de medida com a mesma sigla
            $this->db->where('sigla', $sigla);
            $this->db->where('estatus', '');
            $query = $this->db->get('unid_medida');
            if ($query->num_rows() > 0) {
                // Unidade de medida já existe com a mesma sigla
                $dados = array(
                    'codigo' => 5,
                    'msg' => 'Unidade de Medida com a mesma sigla já existe.'
                );
                return $dados;
            }

            // Verifica se já existe uma unidade de medida com a mesma descrição
            $this->db->where('descricao', $descricao);
            $this->db->where('estatus', '');
            $query = $this->db->get('unid_medida');
            if ($query->num_rows() > 0) {
                // Unidade de medida já existe com a mesma descrição
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Unidade de Medida com a mesma descrição já existe.'
                );
                return $dados;
            }




            //
            $sql = "insert into unid_medida (sigla, descricao, usucria)
                            values ('$sigla', '$descricao', '$usuarioLogin')";

            $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {

                $this->load->model('M_log');

                $retorno_log = $this->M_log->inserirLog($usuarioLogin, $sql);

                if ($retorno_log['codigo'] == 1) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Unidade de Medida cadastrada corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 7,
                        'msg' => 'Houve algum problema no salvamento do log, porém, a Unidade de Medida foi inserida corretamente'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Houve algum problema na inserção na tabela de unidade de medida'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENCAO: O seguinte erro aconteceu ->',
                $e->getMessage(),
                "\n"
            );
        }

        return $dados;
    }

    public function consultar($codigo, $sigla, $descricao)
    {
        try {
            $sql = "select * from unid_medida where estatus = '' ";


            if ($sigla != '') {
                $sql = $sql . " and sigla = '$sigla' ";
            }

            if ($descricao != '') {
                $sql = $sql . " and descricao like '%$descricao%' ";
            }

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta realizada com sucesso',
                    'dados' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Dados não encontrados'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENCAO: O seguinte erro aconteceu ->',
                $e->getMessage(),
                "\n"
            );
        }

        return $dados;
    }

    public function alterar($sigla, $descricao, $usuario)
    {
        try {

            // Verifica se o usuarioLogin existe na tabela usuarios
            $this->db->where('usuario', $usuario);
            $this->db->where('estatus', '');
            $query = $this->db->get('usuarios');
            if ($query->num_rows() == 0) {
                // Usuario não existe
                $dados = array(
                    'codigo' => 4,
                    'msg' => 'Usuário não existe na base de dados.'
                );
                return $dados;
            }


            // if (trim($sigla) != '' && trim($descricao) != '') {
            //     $sql = "update unid_medida set sigla = '$sigla', descricao = '$descricao' where cod_unidade = $codigo";
            // } elseif (trim($sigla) != '') {
            //     $sql = "update unid_medida set sigla = '$sigla' where cod_unidade = $codigo";
            // } else {
            //     $sql = "update unid_medida set descricao = '$descricao' where cod_unidade = $codigo";
            // }


            // Verifica se ao menos um dos campos foi informado
            if (trim($sigla) == '' && trim($descricao) == '') {
                $dados = array(
                    'codigo' => 4,
                    'msg' => 'Sigla ou Descrição devem ser informadas para atualização.'
                );
                return $dados;
            }

            // Verifica se existe outra unidade de medida com a mesma sigla (da pra adicionar uma validação verificando se o estatus da unidade de medida está desativado)
            if (trim($sigla) != '') {
                $this->db->where('sigla', $sigla);
                $this->db->where('sigla !=', $sigla);
                $query = $this->db->get('unid_medida');
                if ($query->num_rows() > 0) {
                    // Já existe outra unidade de medida com a mesma sigla
                    $dados = array(
                        'codigo' => 5,
                        'msg' => 'Outra Unidade de Medida com a mesma sigla já existe.'
                    );
                    return $dados;
                }
            }

            // Verifica se existe outra unidade de medida com a mesma descrição (da pra adicionar uma validação verificando se o estatus da unidade de medida está desativado)
            if (trim($descricao) != '') {
                $this->db->where('descricao', $descricao);
                $this->db->where('sigla !=', $sigla);
                $query = $this->db->get('unid_medida');
                if ($query->num_rows() > 0) {
                    // Já existe outra unidade de medida com a mesma descrição
                    $dados = array(
                        'codigo' => 6,
                        'msg' => 'Outra Unidade de Medida com a mesma descrição já existe.'
                    );
                    return $dados;
                }
            }

            $data = array();
            if (trim($sigla) != '') {
                $data['sigla'] = $sigla;
            }
            if (trim($descricao) != '') {
                $data['descricao'] = $descricao;
            }

            $this->db->where('sigla', $sigla);
            $this->db->update('unid_medida', $data);

            if ($this->db->affected_rows() > 0) {

                $this->load->model('M_log');

                // Log da query executada
                $sql = $this->db->last_query();

                $retorno_log = $this->M_log->inserirLog($usuario, $sql);

                if ($retorno_log['codigo'] == 1) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Unidade de Medida atualizada corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 7,
                        'msg' => 'Houve algum problema no salvamento do log, porém, a Unidade de Medida foi alterada corretamente'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Houve algum problema na alteração na tabela de unidade de medida'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENCAO: O seguinte erro aconteceu ->',
                $e->getMessage(),
                "\n"
            );
        }

        return $dados;
    }

    public function desativar($sigla, $usuario)
    {
        try {
            // Corrigido: Use aspas simples ao redor de $sigla
            $sql = "SELECT * FROM produtos WHERE unid_medida = '$sigla' AND estatus = ''";

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 3,
                    'msg' => 'Não podemos DESATIVAR, existem produtos associados a esta unidade de medida cadastrados.'
                );
            } else {
                // Corrigido: Use aspas simples ao redor de $sigla
                $sql2 = "UPDATE unid_medida SET estatus = 'D' WHERE sigla = '$sigla'";

                $this->db->query($sql2);

                if ($this->db->affected_rows() > 0) {
                    $this->load->model('M_log');

                    $retorno_log = $this->M_log->inserirLog($usuario, $sql2);

                    if ($retorno_log['codigo'] == 1) {
                        $dados = array(
                            'codigo' => 1,
                            'msg' => 'Unidade de medida DESATIVADA corretamente'
                        );
                    } else {
                        $dados = array(
                            'codigo' => 8,
                            'msg' => 'Houve algum problema no salvamento do log, porém, a Unidade de medida foi DESATIVADA corretamente'
                        );
                    }
                } else {
                    $dados = array(
                        'codigo' => 7,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO da unidade de medida'
                    );
                }
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENCAO: O seguinte erro aconteceu ->',
                $e->getMessage(),
                "\n"
            );
        }

        return $dados;
    }
}