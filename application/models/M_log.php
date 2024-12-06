<?php
class M_log extends CI_Model {
    public function inserirLog($usuario, $comando){
        try{
            //Instancia do banco de log
            $dblog = $this->load->database('log', TRUE);

            //Chamada da função na Helper para nos auxiliar
            $comando = trocaCaractere($comando);

            //Query de inserção dos dados
            $dblog->query("insert into log (usuario, comando)
                            values ('$usuario', '$comando')");

            //Verificar se a inserção ocorreu com sucesso
            if($dblog->affected_rows() > 0){
                $dados = array('codigo' => 1,
                                'msg' => 'Log cadastrado corretamente');
            }else{
                $dados = array('codigo' => 6,
                                'msg' => 'Houve algum problema na inserção do log');
            }

            //Fecho a conexão com o banco de log
            $dblog->close();
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                            'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->', $e->getMessage(), "\n");
        }

        return $dados;
    }
}
?>