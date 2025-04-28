<html>
<head>
<title>Desafio PHP + MySQL + Docker</title>
<style>
    body { font-family: sans-serif; margin: 20px; }
    .error { color: #D8000C; background-color: #FFD2D2; border: 1px solid; padding: 5px; margin-top: 10px;}
    .success { color: #4F8A10; background-color: #DFF2BF; border: 1px solid; padding: 5px; margin-top: 10px;}
    code { background-color: #eee; padding: 2px 4px; border-radius: 3px; }
</style>
</head>
<body>

<h1>Executando PHP no Docker!</h1>

<?php
// Habilitar exibição de erros para depuração
ini_set("display_errors", 1);
error_reporting(E_ALL); 
// Definir charset para UTF-8 (mais moderno)
header('Content-Type: text/html; charset=utf-8'); 

echo 'Versao Atual do PHP: ' . phpversion() . '<br><hr>';

// --- DETALHES DA CONEXÃO CORRIGIDOS ---
$servername = "db";         // <<< CORRIGIDO: Nome do serviço MySQL
$username = "root";         // OK (do docker-compose.yml)
$password = "root";         // <<< CORRIGIDO: Senha do docker-compose.yml
$database = "toshiro_db";   // <<< CORRIGIDO: Banco do docker-compose.yml

echo "Tentando conectar ao banco de dados: " . htmlspecialchars($database) . "@" . htmlspecialchars($servername) . "...<br>";

// Criar conexão com mysqli
$link = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($link->connect_error) {
    echo "<div class='error'>Falha na conexão MySQL: (" . $link->connect_errno . ") " . htmlspecialchars($link->connect_error) . "</div>";
    exit(); // Para a execução aqui se não conectar
} else {
    echo "<div class='success'>Conexão MySQL bem-sucedida!</div>";
}

// --- LÓGICA DE INSERÇÃO ORIGINAL ---
echo "<hr>Tentando inserir dados aleatórios...<br>";
$valor_rand1 =  rand(1, 999);
$valor_rand2 = strtoupper(substr(bin2hex(random_bytes(4)), 1));
$host_name = gethostname(); // Pega o hostname do container PHP/App

$query = "INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host) VALUES ('$valor_rand1' , '$valor_rand2', '$valor_rand2', '$valor_rand2', '$valor_rand2','$host_name')";
echo "Executando Query: `" . htmlspecialchars($query) . "`<br>";

if ($link->query($query) === TRUE) {
  // Verificar se a query realmente inseriu algo
  if ($link->affected_rows > 0) {
      echo "<div class='success'>Novo registro criado com sucesso!</div>";
  } else {
      // Query rodou mas não inseriu - A tabela 'dados' existe? O banco.sql criou ela?
       echo "<div class='error'>Query executada, mas 0 linhas afetadas. Verifique se a tabela 'dados' existe no banco 'toshiro_db' e se as colunas estão corretas (conforme `banco.sql`).</div>";
  }
} else {
  // Erro ao executar a query (ex: tabela não existe, sintaxe SQL errada, etc.)
  echo "<div class='error'>Erro na Query: " . htmlspecialchars($link->error) . "</div>";
}

$link->close(); // Fechar a conexão
echo "<br>Conexão fechada.";

?>
</body>
</html>
