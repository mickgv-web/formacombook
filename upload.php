<?php
require_once __DIR__ . '/includes/funciones.php';
include __DIR__ . '/includes/header.php';

$conn = conectarBD();
$mensaje = "";

// Aseguramos que el usuario esté logueado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["foto"])) {
    $usuario_id = $_SESSION["usuario_id"];
    $file = $_FILES["foto"];

    // Validaciones básicas
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $mensaje = '<div class="notification is-danger">Error al subir la foto.</div>';
    } elseif ($file["size"] > 2 * 1024 * 1024) { // 2 MB
        $mensaje = '<div class="notification is-warning">La foto es demasiado grande (máx 2MB).</div>';
    } else {
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($ext, $permitidas)) {
            $mensaje = '<div class="notification is-warning">Formato no permitido. Usa JPG, PNG o WEBP.</div>';
        } else {
            // Generar nombre seguro
            $nombreSeguro = bin2hex(random_bytes(8)) . "." . $ext;
            $directorio = __DIR__ . "/uploads/fotos";

            if (!is_dir($directorio)) {
                mkdir($directorio, 0775, true);
            }

            $destino = $directorio . "/" . $nombreSeguro;

            if (move_uploaded_file($file["tmp_name"], $destino)) {
                // Guardar en BD
                $stmt = $conn->prepare("INSERT INTO foto (ruta_imagen, usuario_id) VALUES (?, ?)");
                $stmt->bind_param("si", $nombreSeguro, $usuario_id);

                if ($stmt->execute()) {
                    $mensaje = '<div class="notification is-success">Foto subida correctamente.</div>';
                } else {
                    $mensaje = '<div class="notification is-danger">Error al guardar en la base de datos.</div>';
                }
                $stmt->close();
            } else {
                $mensaje = '<div class="notification is-danger">No se pudo mover el archivo.</div>';
            }
        }
    }
}
?>

<h1 class="title">Subir fotografía</h1>
<?php echo $mensaje; ?>

<form method="post" enctype="multipart/form-data" class="box">
  <div class="field">
    <label class="label">Selecciona una foto</label>
    <div class="control">
      <input class="input" type="file" name="foto" accept="image/jpeg,image/png,image/webp" required>
    </div>
  </div>

  <div class="field">
    <div class="control">
      <button class="button is-primary" type="submit">Subir</button>
    </div>
  </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
