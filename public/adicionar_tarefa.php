<?php
session_start();
include('../config/db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'] ?? 'Pendente';  // Se não escolher, padrão será 'Pendente'

    $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, status, usuario_id, data_criacao) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$titulo, $descricao, $status, $usuario_id]);

    header("Location: tarefas.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<h2>Adicionar Tarefa - TaskMaster</h2>
<form method="POST">
    <label>Título:</label><br>
    <input type="text" name="titulo" placeholder="Título" required><br><br>

    <label>Descrição:</label><br>
    <textarea name="descricao" placeholder="Descrição" required></textarea><br><br>

    <label>Status:</label><br>
    <select name="status">
        <option value="Pendente">Pendente</option>
        <option value="Em andamento">Em andamento</option>
        <option value="Concluída">Concluída</option>
    </select><br><br>

    <button type="submit">Salvar</button>
</form>

<p><a href="tarefas.php">Voltar para a lista de tarefas</a></p>

<?php include('../includes/footer.php'); ?>
