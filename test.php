<?php

function xss($data, $problem='') {
	$data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = strip_tags($data);
	if ($problem && strlen($data) == 0) {
		return ($problem);
	}
    return $data;
}

function corta($str, $left, $right) {
    $str = substr ( stristr ( $str, $left ), strlen ( $left ) );
	@$leftLen = strlen ( stristr ( $str, $right ) );
	$leftLen = $leftLen ? - ($leftLen) : strlen ( $str );
	$str = substr ( $str, 0, $leftLen );
	return $str;
}

function clearStr($str) {
	if(is_array($str)){
		return $str;
	}

	if(strlen($str) == 0) {
		return null;
	}

	$str = xss($str);
	$str = rtrim($str);
	$str = ltrim($str);
	$str = str_replace(array("\n", "\t", "  ", "	", "\r", "(", ")", ";", ">", "<", "$"), '', $str);
	$str = utf8_decode($str);
	return $str;
}

function filtroCnpj($res) {
	if(!stristr($res, 'Renavam</td>')) {
		return array('msg' => 'nada encontrado');
	}
	$dom = new DOMDocument();

	$dados = explode('<table width="100%" class="tabelaHs">', $res);
	$dados11 = $dados[1];
	$dados11 = corta($dados11, '<tbody>', '</tbody>');
	$dados12 = $dados[2];
	$dados12 = corta($dados12, '<tbody>', '</tbody>');
	$dados13 = $dados[3];
	$dados13 = corta($dados13, '<tbody>', '</tbody>');
	$dados14 = $dados[4];
	$dados14 = corta($dados14, '<tbody>', '</tbody>');
	$dados15 = $dados[5];
	$dados15 = corta($dados15, '<tbody>', '</tbody>');

	//ultimo pagamento?
	$dados16 = $dados[6];
	$dados16 = corta($dados16, '<tbody>', '</tbody>');
	//ultimo pagamento fim..

	$dados17 = $dados[7];
	$dados17 = corta($dados17, '<tbody>', '</tbody>');

	//nome arredatario/cpf?/tipo
	$dados18 = $dados[8];
	$dados18 = corta($dados18, '<tbody>', '</tbody>');

	$dados19 = $dados[9];
	$dados19 = corta($dados19, '<tbody>', '</tbody>');

	$dados10 = $dados[10];
	$dados10 = corta($dados10, '<tbody>', '</tbody>');


	$dom->loadHTML($dados11);
	$Detail = $dom->getElementsByTagName('td');

//	$nome   = clearStr($Detail[0]->textContent);
	$mae    = clearStr($Detail[1]->textContent);
	$recVec = clearStr($Detail[2]->textContent);

	$dom->loadHTML($dados12);
	$Detail2 = $dom->getElementsByTagName('td');
	$nome   = clearStr($Detail2[0]->textContent);
	$cpfcnpj = clearStr($Detail2[1]->textContent);

	$dom->loadHTML($dados13);
	$Detail3   = $dom->getElementsByTagName('td');


	$idade   = clearStr($Detail3[1]->textContent);
	$signo   = clearStr($Detail3[2]->textContent);
	$sexo    = clearStr($Detail3[3]->textContent);
	$situacaodono = clearStr($Detail2[4]->textContent);
	$nascimento   = clearStr($Detail3[0]->textContent);



	$dom->loadHTML($dados14);
	$Detail4 = $dom->getElementsByTagName('td');

	$categoria = clearStr($Detail4[0]->textContent);
	$placa     = clearStr($Detail4[1]->textContent);
	$chassi    = clearStr($Detail4[2]->textContent);
	$renavam   = clearStr($Detail4[3]->textContent);
	$cpfcnpjcar = clearStr($Detail3[4]->textContent);


	$dom->loadHTML($dados15);
	$Detail5 = $dom->getElementsByTagName('td');	

	$cor     = clearStr($Detail5[1]->textContent);
	$anofab  = clearStr($Detail5[2]->textContent);
	$anomol  = clearStr($Detail5[3]->textContent);
	$anoexercicio = clearStr($Detail5[4]->textContent);
	$marcmodelo   = clearStr($Detail5[0]->textContent);



	$dom->loadHTML($dados16);
	$Detail6 = $dom->getElementsByTagName('td');	

	$municipioEmplacamento  = clearStr($Detail6[0]->textContent);
	$EstadoEmplacamento     = clearStr($Detail6[1]->textContent);

	$dom->loadHTML($dados18);
	$Detail8 = $dom->getElementsByTagName('td');
	$registroRoubo    		 = clearStr($Detail8[1]->textContent);
	$sinalizacaoRoubo    	 = clearStr($Detail8[2]->textContent);
	$sinalizacaoLocalizacao  = clearStr($Detail8[3]->textContent);
	$ordemJudidicalApreensao = clearStr($Detail8[0]->textContent);
	
	$dom->loadHTML($dados10);
	$Detail10   = $dom->getElementsByTagName('td');
	$endEstado = clearStr($Detail10[0]->textContent);
	$endCidade = clearStr($Detail10[1]->textContent);
	$endBairro = clearStr($Detail10[2]->textContent);
	$endRua    = clearStr($Detail10[3]->textContent);
	$endNumero = clearStr($Detail10[4]->textContent);
	$endCep    = clearStr($Detail10[6]->textContent);
	$endComplemento = clearStr($Detail10[5]->textContent);

	$retorno = array(
		'veiculo' => array(
			'placa'   => $placa,
			'chassi'  => $chassi,
			'renavam' => $renavam,
			'cor'     => $cor,
			'ano_fab' => $anofab,
			'ano_modelo'    => $anomol,
			'ano_exercicio' => $anoexercicio,
			'marca_modelo'  => $marcmodelo,
			'registro_veiculo' => $recVec,
			'categoria' => $categoria,
			'municipio_emplacamento'   => $municipioEmplacamento,
			'estado_emplacamento'      => $EstadoEmplacamento,
			'ordem_judicial_apreensao' => $ordemJudidicalApreensao,
			'registro_de_roubo'    => $registroRoubo,
			'sinalizacao_de_roubo' => $sinalizacaoRoubo,
			'sinalizacao_de_localizacao' => $sinalizacaoLocalizacao,
			'tipo_dono' => $recVec,
			'doc' => $cpfcnpjcar
		),
		'proprietario' => array(
			'doc'  => $cpfcnpj,
			'nome' => $nome,
			'mae'  => $mae,
			'nascimento' => $nascimento,
			'idade'  => $idade,
			'signo'  => $signo,
			'sexo'   => $sexo,
			'situacao' => $situacaodono,
			'endereco' => array(
				'bairro' => $endBairro,
				'rua'    =>  $endRua,
				'numero' => $endNumero,
				'complemento' => $endComplemento,
				'cep'    => $endCep,
				'estado' => $endEstado,
				'cidade' => $endCidade
			)
		)
	);

	return $retorno;

}

function filtroCpf($res) {
	if(!stristr($res, 'Renavam</td>')) {
		return array('msg' => 'nada encontrado');
	}
	$dom = new DOMDocument();

	$dados = explode('<table width="100%" class="tabelaHs">', $res);
	$dados11 = $dados[1];
	$dados11 = corta($dados11, '<tbody>', '</tbody>');
	$dados12 = $dados[2];
	$dados12 = corta($dados12, '<tbody>', '</tbody>');
	$dados13 = $dados[3];
	$dados13 = corta($dados13, '<tbody>', '</tbody>');
	$dados14 = $dados[4];
	$dados14 = corta($dados14, '<tbody>', '</tbody>');
	$dados15 = $dados[5];
	$dados15 = corta($dados15, '<tbody>', '</tbody>');

	//ultimo pagamento?
	$dados16 = $dados[6];
	$dados16 = corta($dados16, '<tbody>', '</tbody>');
	//ultimo pagamento fim..

	$dados17 = $dados[7];
	$dados17 = corta($dados17, '<tbody>', '</tbody>');

	//nome arredatario/cpf?/tipo
	$dados18 = $dados[8];
	$dados18 = corta($dados18, '<tbody>', '</tbody>');

	$dados19 = $dados[9];
	$dados19 = corta($dados19, '<tbody>', '</tbody>');


	$dom->loadHTML($dados11);
	$Detail = $dom->getElementsByTagName('td');
	$nome   = clearStr($Detail[0]->textContent);
	$mae    = clearStr($Detail[1]->textContent);
	$recVec = clearStr($Detail[2]->textContent);

	$dom->loadHTML($dados12);
	$Detail2 = $dom->getElementsByTagName('td');
	$idade   = clearStr($Detail2[1]->textContent);
	$signo   = clearStr($Detail2[2]->textContent);
	$sexo    = clearStr($Detail2[3]->textContent);
	$situacaodono = clearStr($Detail2[4]->textContent);
	$nascimento   = clearStr($Detail2[0]->textContent);


	$dom->loadHTML($dados13);
	$Detail3   = $dom->getElementsByTagName('td');
	$categoria = clearStr($Detail3[0]->textContent);
	$placa     = clearStr($Detail3[1]->textContent);
	$chassi    = clearStr($Detail3[2]->textContent);
	$renavam   = clearStr($Detail3[3]->textContent);
	$cpfcnpj   = clearStr($Detail3[4]->textContent);

	$dom->loadHTML($dados14);
	$Detail4 = $dom->getElementsByTagName('td');
	$cor     = clearStr($Detail4[1]->textContent);
	$anofab  = clearStr($Detail4[2]->textContent);
	$anomol  = clearStr($Detail4[3]->textContent);
	$anoexercicio = clearStr($Detail4[4]->textContent);
	$marcmodelo   = clearStr($Detail4[0]->textContent);


	$dom->loadHTML($dados15);
	$Detail5 = $dom->getElementsByTagName('td');
	$municipioEmplacamento  = clearStr($Detail5[0]->textContent);
	$EstadoEmplacamento     = clearStr($Detail5[1]->textContent);

	$dom->loadHTML($dados17);
	$Detail7 = $dom->getElementsByTagName('td');
	$registroRoubo    		 = clearStr($Detail7[1]->textContent);
	$sinalizacaoRoubo    	 = clearStr($Detail7[2]->textContent);
	$sinalizacaoLocalizacao  = clearStr($Detail7[3]->textContent);
	$ordemJudidicalApreensao = clearStr($Detail5[0]->textContent);




	$dom->loadHTML($dados19);

	$Detail9   = $dom->getElementsByTagName('td');
	$endEstado = clearStr($Detail9[0]->textContent);
	$endCidade = clearStr($Detail9[1]->textContent);
	$endBairro = clearStr($Detail9[2]->textContent);
	$endRua    = clearStr($Detail9[3]->textContent);
	$endNumero = clearStr($Detail9[4]->textContent);
	$endCep    = clearStr($Detail9[6]->textContent);
	$endComplemento = clearStr($Detail9[5]->textContent);

	$retorno = array(
		'veiculo' => array(
			'placa'   => $placa,
			'chassi'  => $chassi,
			'renavam' => $renavam,
			'cor'     => $cor,
			'ano_fab' => $anofab,
			'ano_modelo'    => $anomol,
			'ano_exercicio' => $anoexercicio,
			'marca_modelo'  => $marcmodelo,
			'registro_veiculo' => $recVec,
			'categoria' => $categoria,
			'municipio_emplacamento'   => $municipioEmplacamento,
			'estado_emplacamento'      => $EstadoEmplacamento,
			'ordem_judicial_apreensao' => $ordemJudidicalApreensao,
			'registro_de_roubo'    => $registroRoubo,
			'sinalizacao_de_roubo' => $sinalizacaoRoubo,
			'sinalizacao_de_localizacao' => $sinalizacaoLocalizacao,
		),
		'proprietario' => array(
			'doc'  => $cpfcnpj,
			'nome' => $nome,
			'mae'  => $mae,
			'nascimento' => $nascimento,
			'idade'  => $idade,
			'signo'  => $signo,
			'sexo'   => $sexo,
			'situacao' => $situacaodono,
			'endereco' => array(
				'bairro' => $endBairro,
				'rua'    =>  $endRua,
				'numero' => $endNumero,
				'complemento' => $endComplemento,
				'cep'    => $endCep,
				'estado' => $endEstado,
				'cidade' => $endCidade
			)
		)
	);

	return $retorno;

}

$dados = file_get_contents('debug.html');
if(!stristr($dados, 'Renavam')) {
	$retorno = array('msg' => 'nada encontrado');
}
if(stristr($dados, 'GISTRO EM CNPJ JURIDICO')) {
	$retorno = filtroCnpj($dados);
}else{
	$retorno = filtroCpf($dados);
}
print_r($retorno);


die;


