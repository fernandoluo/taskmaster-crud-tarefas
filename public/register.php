<?php
session_start();
include('../config/db.php');

// Se o usuário já estiver logado, redireciona
if (isset($_SESSION['usuario_id'])) {
    header("Location: tarefas.php");
    exit();
}

// Processa o formulário de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail já está em uso
    $verifica = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $verifica->execute([$email]);
    
    if ($verifica->rowCount() > 0) {
        $erro = "E-mail já está cadastrado.";
    } else {
        // Hash da senha e cadastro
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $hash]);
        
        // Redireciona para o login
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar - TaskMaster</title>
</head>
<body>
    <h2>Cadastro de Usuário - TaskMaster</h2>

    <?php if (isset($erro)): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nome" placeholder="Seu nome" required><br><br>
        <input type="email" name="email" placeholder="Seu e-mail" required><br><br>
        <input type="password" name="senha" placeholder="Senha" required><br><br>
        <button type="submit">Cadastrar</button>
    </form>

    <p>Já tem conta? <a href="login.php">Faça login</a></p>
</body>
</html>
