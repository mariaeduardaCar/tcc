<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "Usuário não autenticado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id']; // Agora, deve estar disponível na sessão
    $valor = 20.00;
    $status = 'pendente';
    $metodo_pagamento = 'cartao';

    $stmt = $conn->prepare("INSERT INTO pagamentos (usuario_id, valor, status, metodo_pagamento) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $usuario_id, $valor, $status, $metodo_pagamento);

    if ($stmt->execute()) {
        $pagamento_id = $stmt->insert_id;
        $stmt = $conn->prepare("UPDATE pagamentos SET status = 'concluído' WHERE id = ?");
        $stmt->bind_param("i", $pagamento_id);
        $stmt->execute();
        
        echo "Pagamento concluído com sucesso!";
        header("Location: login.php");
    } else {
        echo "Erro ao processar pagamento.";
    }
}
?>
