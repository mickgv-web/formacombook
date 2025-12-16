<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/fotos.php';
require_once __DIR__ . '/includes/card.php';
include __DIR__ . '/includes/header.php';

$conn = conectarBD();

// Aseguramos que el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

// Consultar datos del usuario
$stmt = $conn->prepare("SELECT nombre FROM usuario WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$usuario = $res->fetch_assoc();
$stmt->close();

// Obtener estadísticas del usuario
$sqlStats = "
  SELECT COUNT(*) AS total_fotos,
         COALESCE(SUM(v.valor),0) AS total_likes
  FROM foto f
  LEFT JOIN votos v ON v.foto_id = f.foto_id
  WHERE f.usuario_id = ?
";
$stmt = $conn->prepare($sqlStats);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$stats = $res->fetch_assoc();
$stmt->close();

// Obtener fotos del usuario
$sqlFotos = "
  SELECT f.foto_id, f.ruta_imagen, f.created_at, u.nombre, u.usuario_id,
         COALESCE(SUM(v.valor), 0) AS votos
  FROM foto f
  JOIN usuario u ON u.usuario_id = f.usuario_id
  LEFT JOIN votos v ON v.foto_id = f.foto_id
  WHERE f.usuario_id = ?
  GROUP BY f.foto_id
  ORDER BY f.created_at DESC
";
$stmt = $conn->prepare($sqlFotos);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$fotos = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<h1 class="title">
  <i class="fa-solid fa-user"></i>&nbsp;Perfil de <?php echo htmlspecialchars($usuario['nombre']); ?>
</h1>

<!-- Bloque de estadísticas -->
<div class="box has-text-centered mb-5">
  <p><strong><?php echo (int)$stats['total_fotos']; ?></strong> fotos subidas</p>
  <p><strong><?php echo (int)$stats['total_likes']; ?></strong> likes recibidos</p>
</div>

<!-- Galería de fotos del usuario -->
<div class="columns is-multiline">
    <?php foreach ($fotos as $foto): ?>
        <?php renderCard($conn, $foto); ?>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
