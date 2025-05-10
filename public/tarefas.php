<?php
session_start();
include('../config/db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Se o filtro de status for passado na URL, filtra as tarefas
$statusFiltro = isset($_GET['status']) ? $_GET['status'] : '';

// Consulta as tarefas
if ($statusFiltro) {
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = ? AND status = ?");
    $stmt->execute([$usuario_id, $statusFiltro]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);
}

$tarefas = $stmt->fetchAll();
?>

<?php include('../includes/header.php'); ?>

<h2>Lista de Tarefas - TaskMaster</h2>

<!-- Filtro por status -->
<form method="GET">
    <label>Filtrar por status:</label>
    <select name="status">
        <option value="">Todos</option>
        <option value="Pendente" <?= isset($_GET['status']) && $_GET['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="Em andamento" <?= isset($_GET['status']) && $_GET['status'] == 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
        <option value="Concluída" <?= isset($_GET['status']) && $_GET['status'] == 'Concluída' ? 'selected' : '' ?>>Concluída</option>
    </select>
    <button type="submit">Filtrar</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Data de Criação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tarefas as $tarefa): ?>
            <tr>
                <td><?= htmlspecialchars($tarefa['titulo']) ?></td>
                <td><?= htmlspecialchars($tarefa['descricao']) ?></td>
                <td><?= $tarefa['status'] ?></td>
                <td><?= $tarefa['data_criacao'] ?></td>
                <td>
                    <a href="editar_tarefa.php?id=<?= $tarefa['id'] ?>">Editar</a> |
                    <a href="excluir_tarefa.php?id=<?= $tarefa['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include('../includes/footer.php'); ?>
