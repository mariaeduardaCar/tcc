<?php
$servername = "mysql-db";  // ou o endereço do seu servidor de banco de dados
$username = "root";         // seu usuário do banco de dados
$password = "";             // sua senha do banco de dados
$dbname = "tech_connect";   // nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
