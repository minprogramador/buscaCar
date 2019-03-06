<?php

require('util.php');
require('cpf.php');
require('cnpj.php');

$dados = file_get_contents('debugcnpj.html');

if(!stristr($dados, 'Renavam')) {
	$retorno = array('msg' => 'nada encontrado');
}elseif(stristr($dados, 'GISTRO EM CNPJ JURIDICO')) {
	$retorno = filtroCnpj($dados);
}else{
	$retorno = filtroCpf($dados);
}
print_r($retorno);


die;


