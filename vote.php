<?php
require_once __DIR__ . '/includes/funciones.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

$conn = conectarBD();
$usuario_id = $_SESSION["usuario_id"];
$foto_id = (int)($_POST["foto_id"] ?? 0);

// Comprobamos que la foto existe y no es del propio usuario
$stmt = $conn->prepare("SELECT usuario_id FROM foto WHERE foto_id = ?");
$stmt->bind_param("i", $foto_id);
$stmt->execute();
$res = $stmt->get_result();
$foto = $res->fetch_assoc();
$stmt->close();

if (!$foto || $foto["usuario_id"] == $usuario_id) {
    header("Location: index.php");
    exit;
}

// Insertar o actualizar voto
$stmt = $conn->prepare("
  INSERT INTO votos (foto_id, usuario_id, valor) VALUES (?, ?, 1)
  ON DUPLICATE KEY UPDATE valor = VALUES(valor), updated_at = CURRENT_TIMESTAMP
");
$stmt->bind_param("ii", $foto_id, $usuario_id);
$stmt->execute();
$stmt->close();

header("Location: index.php");
exit;
