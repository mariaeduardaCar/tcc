<?php
session_start();
include 'db_connect.php'; // Conexão ao banco de dados

if (isset($_SESSION['token'])) {
    // Remove a sessão do banco de dados
    $stmt = $conn->prepare("DELETE FROM sessoes WHERE token = ?");
    $stmt->bind_param("s", $_SESSION['token']);
    $stmt->execute();
}

// Destrói a sessão e redireciona para a página de login
session_destroy();
header("Location: login.php");
exit();
?>
