<?php
session_start();
include('../config/db.php');

// Se já estiver logado, redireciona
if (isset($_SESSION['usuario_id'])) {
    header("Location: tarefas.php");
    exit();
}

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        header("Location: tarefas.php");
        exit();
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - TaskMaster</title>
</head>
<body>
    <h2>Login - TaskMaster</h2>

    <?php if (isset($erro)): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="E-mail" required><br><br>
        <input type="password" name="senha" placeholder="Senha" required><br><br>
        <button type="submit">Entrar</button>
    </form>

    <p>Não tem conta? <a href="register.php">Crie uma conta</a></p>
</body>
</html>
