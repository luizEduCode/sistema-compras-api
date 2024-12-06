<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Função para verificar os parâmetros vindos do FrontEnd
*/
function verificarParam($atributos, $lista)
{
    // Verificar se os elementos do Front estão nos atributos necessários
    foreach ($lista as $key => $value) {
        if (property_exists($atributos, $key)) {
            $estatus = 1;
        } else {
            $estatus = 0;
            break;
        }
    }

    // Verificar a quantidade de elementos
    if (count(get_object_vars($atributos)) != count($lista)) {
        $estatus = 0;
    }

    return $estatus;
}

/*
Função para trocar o ' (aspas simples) por ` (acento agudo)
para podermos montar a String para compor o campo comando
*/
function trocaCaractere($value)
{
    $retorno = str_replace("'", "`", $value);
    return $retorno;
}

/*
Função para validar valores de estoque mínimo e máximo
Retorna:
1 - Estoque válido
0 - Estoque inválido
*/
function validarEstoque($estoq_minimo, $estoq_maximo)
{
    if ($estoq_minimo < 0 || $estoq_maximo < 0) {
        return 0; // Estoque não pode ser negativo
    }
    if ($estoq_minimo > $estoq_maximo) {
        return 0; // Estoque mínimo não pode ser maior que o máximo
    }
    return 1; // Estoque válido
}

/*
Função para sanitizar e validar entradas de strings
*/
function sanitizarString($string)
{
    // Remover espaços extras e caracteres indesejados
    $string = trim($string);
    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    return $string;
}

/*
Função para verificar se um número é inteiro e positivo
*/
function validarInteiroPositivo($numero)
{
    return (is_numeric($numero) && $numero >= 0 && floor($numero) == $numero);
}