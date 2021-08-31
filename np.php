<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<style media="print">
    * {
        font-size: 10pt !important;
    }
	
	.nota{
		page-break-inside:avoid; 
		}
</style>
<?php
include "database.php";
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

function extenso($valor = 0, $maiusculas = false) {

$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
"quatrilhões");

$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
"sessenta", "setenta", "oitenta", "noventa");
$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
"dezesseis", "dezesete", "dezoito", "dezenove");
$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
"sete", "oito", "nove");

$z = 0;
$rt = "";

$valor = number_format($valor, 2, ".", ".");
$inteiro = explode(".", $valor);
for($i=0;$i<count($inteiro);$i++)
for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
$inteiro[$i] = "0".$inteiro[$i];

$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
for ($i=0;$i<count($inteiro);$i++) {
$valor = $inteiro[$i];
$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
$ru) ? " e " : "").$ru;
$t = count($inteiro)-1-$i;
$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
if ($valor == "000")$z++; elseif ($z > 0) $z--;
if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
}

if(!$maiusculas){
return($rt ? $rt : "zero");
} else {

if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
return (($rt) ? ($rt) : "Zero");
}

}


function get_np($contrato){
	$sql = "select c.id, c.data, p.razao, p.fantasia, p.endereco, p.numero, p.bairro, p.cnpj_cpf as doc, p.cep, p.tipo_pessoa " ;
	$sql .= "from cliente_contrato c " ;
	$sql .= "left join cliente p on p.id = c.id_cliente " ;
	// $sql .= "left join radpop_radio t on t.id = o.id_transmissor " ;
	$sql .= "where c.id = '$contrato'" ;
	// $sql .= "SELECT * FROM `filial`" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0];
}

function get_filial($contrato){
	$sql = "SELECT * FROM `filial` " ;
	$sql .= "where id = '$contrato'" ;
	// $sql .= "" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0];
}

function gera_np($dados){
	
	$f = get_filial(2);
	
	echo '<div class="nota">';
	echo '<div class="row">';
	echo '    <div class="col s12">';
	echo '      <div class="card-panel ">';
	echo '        <span class="center-align">';
	echo '		<b><h6 class="center-align">NOTA PROMISSÓRIA </b></h6>';
	echo '        </span>';
	echo '		<p class="">';
	// echo '		Vencimento em  '.strftime("%d de %B de %Y", strtotime(date('Y-m-d')));
	echo '        </p>';
	echo '		<p class="">';
	echo '		Numero de Controle: ';
	echo '        </p>';
	echo '		<p class="">';
	echo '		Valor: R$ 500,00 ';
	echo '        </p>';
	echo '		<p class="">';
	// echo '		No dia '.strftime("%d de %B de %Y", strtotime(date('Y-m-d'))).' pagarei por esta única via de NOTA PROMISSÓRIA  a '.$f['razao'].', CNPJ '.$f['cnpj'].', ou a sua ordem a quantia de '.extenso(500).' em moeda corrente do país. ';
	echo '		Pagarei por esta única via de NOTA PROMISSÓRIA  à '.$f['razao'].', CNPJ '.$f['cnpj'].', ou a sua ordem, a quantia de '.extenso(500).' em moeda corrente do país. ';

	echo '        </p>';
	echo '		<p class="">';
	echo '		Pagável em Brasília - DF ';
	echo '        </p>';
	echo '		<p class="">';
	echo '		Emitente: '.$dados['razao'].' <br>
				CPF: '.$dados['doc'].' <br>
				Endereço: '.$dados['endereco'].' Numero '.$dados['numero'].' - '.$dados['bairro'].' ';
	echo '        </p>';
	echo '		<p class="">';
	echo '		Brasília - DF, '.strftime("%d de %B de %Y", strtotime(date('Y-m-d')));
	echo '        </p>';
	echo '        </p>';
	echo '		<p class="center-align">';
	echo '		_______________________________________<br>';
	echo $dados['razao'];
	echo '        </p>';
	echo '      </div>';
	echo '    </div>';
	echo '  </div>';
	echo '</div>';
}

if($_GET['lista']){
	$lista = explode(" ", $_GET['lista']);
	// var_dump($lista);
	foreach($lista as $contrato){
		gera_np(get_np($contrato));
	}
}else{
	echo '<div class="container">';
	echo '<div class="row">';
	echo '    <div class="col s12">';
	echo '      <div class="card-panel ">
					<div class="card-content">';
	echo '        <span class="center-align">';
	echo '		<b><h5 class="center-align">NOTA PROMISSÓRIA </b></h5>';
	echo '        </span>
				<form action="np.php" method="get" class="col s12">
				<div class="row">';
	echo '		<div class="input-field col s12">';
	echo '		<input name="lista" class="active" type="text" placeholder="Lista" >
					<label for="lista">Digite a lista dos numeros de contratos, separados por espaço</label>';
	echo '      </div>
				<button type="submit" class="waves-effect waves-teal btn-flat green">Gerar Notas Promissorias</button>
				</form>
				</div>
				
				
				<p>https://sites.google.com/site/zeitoneglobal/empresarial-ii/1-15-nota-promissoria</p>
				';
	
	echo '      </div>
				</div>';
	echo '    </div>';
	echo '  </div>';
	echo '</div>
	</div>';
}


// gera_np(get_np(5305));

?>