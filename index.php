<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/fotos.php';
require_once __DIR__ . '/includes/card.php';
include __DIR__ . '/includes/header.php';

$conn = conectarBD();

// Orden
$orden = $_GET['orden'] ?? 'fecha';
$fotos = obtenerFotos($conn, $orden, 12);
?>

<h1 class="title">
  <i class="fa-solid fa-images"></i>&nbsp;Últimas fotos
  <?php if ($orden === 'likes'): ?>
    <span class="icon has-text-danger"><i class="fa-solid fa-heart"></i></span>
  <?php else: ?>
    <span class="icon has-text-grey"><i class="fa-solid fa-calendar"></i></span>
  <?php endif; ?>
</h1>

<!-- Selector -->
<form method="get" class="mb-4">
  <div class="field is-grouped">
    <div class="control">
      <div class="select">
        <select name="orden">
          <option value="fecha" <?php if($orden === 'fecha') echo 'selected'; ?>>Más recientes</option>
          <option value="likes" <?php if($orden === 'likes') echo 'selected'; ?>>Más votadas</option>
        </select>
      </div>
    </div>
    <div class="control">
      <button class="button is-link" type="submit">Ordenar</button>
    </div>
  </div>
</form>

<div class="columns is-multiline">
    <?php foreach ($fotos as $foto): ?>
        <?php renderCard($conn, $foto); ?>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
