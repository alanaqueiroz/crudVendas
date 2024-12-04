<?php
    session_start();

    // Verifica se a sessão está ativa e a destrói
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset(); // Remove todas as variáveis de sessão
        session_destroy();
    }

    header("Location: login.php");
    exit();
?>