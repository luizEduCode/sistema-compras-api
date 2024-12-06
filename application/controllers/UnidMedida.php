<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnidMedida extends CI_Controller
{
    // Atributos privados da classe
    private $codigo;
    private $sigla;
    private $descricao;
    private $usuarioLogin;

    // Getters dos atributos
    public function getCodigo()
    {
        return $this->codigo;
    }
    public function getSigla()
    {
        return $this->sigla;
    }
    public function getDescricao()
    {
        return $this->descricao;
    }
    public function getUsuarioLogin()
    {
        return $this->usuarioLogin;
    }

    // Setters dos atributos
    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }
    public function setSigla($siglaFront)
    {
        $this->sigla = $siglaFront;
    }
    public function setDescricao($descricaoFront)
    {
        $this->descricao = $descricaoFront;
    }
    public function setUsuarioLogin($usuarioLoginFront)
    {
        $this->usuarioLogin = $usuarioLoginFront;
    }

    public function inserir()
    {
        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "sigla" => '0',
                "descricao" => '0',
                "usuarioLogin" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                $this->setSigla($resultado->sigla);
                $this->setDescricao($resultado->descricao);
                $this->setUsuarioLogin($resultado->usuarioLogin);

                if (trim($this->getSigla()) == '') {
                    $retorno = array('codigo' => 2, 'msg' => 'Sigla não informada.');
                } elseif (strlen($this->getSigla()) > 3) {
                    $retorno = array('codigo' => 3, 'msg' => 'Sigla pode conter no máximo 3 caracteres.');
                } elseif (trim($this->getDescricao()) == '') {
                    $retorno = array('codigo' => 4, 'msg' => 'Descrição não informada.');
                } elseif (trim($this->getUsuarioLogin()) == '') {
                    $retorno = array('codigo' => 5, 'msg' => 'Usuário não informado.');
                } else {
                    $this->load->model('M_unidmedida');
                    $retorno = $this->M_unidmedida->inserir(
                        $this->getSigla(),
                        $this->getDescricao(),
                        $this->getUsuarioLogin()
                    );
                }
            } else {
                $retorno = array('codigo' => 99, 'msg' => 'Os campos vindos do FrontEnd não representam o método de login. Verifique.');
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0, 'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage());
        }

        echo json_encode($retorno);
    }

    public function consultar()
    {
        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "sigla" => '0',
                "descricao" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                $this->setCodigo($resultado->codigo ?? null);
                $this->setSigla($resultado->sigla);
                $this->setDescricao($resultado->descricao);

                if (strlen($this->getSigla()) > 3) {
                    $retorno = array('codigo' => 2, 'msg' => 'Sigla pode conter no máximo 3 caracteres.');
                } else {
                    $this->load->model('m_unidmedida');
                    $retorno = $this->m_unidmedida->consultar(
                        $this->getCodigo(),
                        $this->getSigla(),
                        $this->getDescricao()
                    );
                }
            } else {
                $retorno = array('codigo' => 99, 'msg' => 'Os campos vindos do FrontEnd não representam o método de login. Verifique.');
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0, 'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage());
        }

        echo json_encode($retorno);
    }
    
    public function alterar()
    {
        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "sigla" => '0',
                "descricao" => '0',
                "usuarioLogin" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setSigla($resultado->sigla);
                $this->setDescricao($resultado->descricao);
                $this->setUsuarioLogin($resultado->usuarioLogin);

                if (trim($this->getSigla()) == '' || trim($this->getSigla()) == 0) {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Código não informado.'
                    );
                } elseif (strlen(trim($this->getSigla())) > 3) {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Sigla pode conter no máximo 3 caracteres.'
                    );
                } elseif (trim($this->getDescricao()) == '' && trim($this->getDescricao()) == '') {
                    $retorno = array(
                        'codigo' => 4,
                        'msg' => 'Sigla ou Descrição não foram informadas.'
                    );
                } elseif (trim($this->getUsuarioLogin()) == '') {
                    $retorno = array(
                        'codigo' => 5,
                        'msg' => 'Usuario não informado.'
                    );
                } else {
                    $this->load->model('m_unidmedida');

                    $retorno = $this->m_unidmedida->alterar(
                        $this->getSigla(),
                        $this->getDescricao(),
                        $this->getUsuarioLogin()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam o método de login. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENCAO: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }

        echo json_encode($retorno);
    }

    public function desativar()
    {
        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "sigla" => '0',
                "usuarioLogin" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setSigla($resultado->sigla);
                $this->setUsuarioLogin($resultado->usuarioLogin);


                if (trim($this->getSigla()) == '' || trim($this->getSigla()) == 0) {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Sigla não informado.'
                    );
                } elseif (trim($this->getUsuarioLogin()) == '') {
                    $retorno = array(
                        'codigo' => 5,
                        'msg' => 'Usuario não informado.'
                    );
                } else {
                    $this->load->model('m_unidmedida');

                    $retorno = $this->m_unidmedida->desativar(
                        $this->getSigla(),
                        $this->getUsuarioLogin()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam o método de login. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENCAO: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }

        echo json_encode($retorno);
    }
}