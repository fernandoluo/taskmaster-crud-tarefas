<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $_SESSION['usuario_id']]);

header("Location: tarefas.php");
