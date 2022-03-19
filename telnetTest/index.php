<?
// 2009-11-23
// Este exemplo funcionou com a versão 5.2.10 do PHP

// incluindo uma classe para facilitar o trabalho com sessões telnet
require_once("telnet.php");

$telnet = new Telnet();


// conectando na maquina 127.0.0.1
$telnet->set_host("172.24.4.2");
$telnet->connect();

// quando o servidor enviar 'login', fornecer o nome do usuario
$telnet->set_prompt(">>User name:");
$telnet->wait_prompt();
$telnet->write("root");


// quando o servidor enviar 'Password', fornecer a senha
$telnet->set_prompt(">>User password:");
$telnet->wait_prompt();
$telnet->write("admin");

// quando o servidor indicar que o prompt está pronto para receber comandos
$telnet->set_prompt("OLT8PON>");
$telnet->wait_prompt();


// executar um comando para criar um diretorio 'testando-php' em /tmp
$telnet->write('enable');
$telnet->write('show');

// fechando a conexao 
$telnet->disconnect();


?>