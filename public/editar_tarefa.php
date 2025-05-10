<?php
session_start();
include('../config/db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Verifica se a tarefa existe
if (!isset($_GET['id'])) {
    header("Location: tarefas.php");
    exit();
}

$id = $_GET['id'];

// Busca a tarefa
$stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $usuario_id]);
$tarefa = $stmt->fetch();

if (!$tarefa) {
    header("Location: tarefas.php");
    exit();
}

// Processa a atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, status = ? WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$titulo, $descricao, $status, $id, $usuario_id]);

    header("Location: tarefas.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<h2>Editar Tarefa - TaskMaster</h2>
<form method="POST">
    <label>Título:</label><br>
    <input type="text" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required><br><br>

    <label>Descrição:</label><br>
    <textarea name="descricao" required><?= htmlspecialchars($tarefa['descricao']) ?></textarea><br><br>

    <label>Status:</label><br>
    <select name="status">
        <option value="Pendente" <?= $tarefa['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="Em andamento" <?= $tarefa['status'] == 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
        <option value="Concluída" <?= $tarefa['status'] == 'Concluída' ? 'selected' : '' ?>>Concluída</option>
    </select><br><br>

    <button type="submit">Atualizar</button>
</form>

<p><a href="tarefas.php">Voltar para a lista de tarefas</a></p>

<?php include('../includes/footer.php'); ?>
