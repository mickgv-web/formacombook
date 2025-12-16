<?php
require_once __DIR__ . '/includes/funciones.php';
include __DIR__ . '/includes/header.php';

$conn = conectarBD();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = trim($_POST["nombre"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($nombre && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $hash);

        if ($stmt->execute()) {
            $mensaje = '<div class="notification is-success">Registro completado. Ya puedes iniciar sesión.</div>';
        } else {
            $mensaje = '<div class="notification is-danger">Error: el email ya existe.</div>';
        }
        $stmt->close();
    } else {
        $mensaje = '<div class="notification is-warning">Datos inválidos. Revisa el formulario.</div>';
    }
}
?>

<h1 class="title">Registro</h1>
<?php echo $mensaje; ?>

<form method="post" class="box">
  <div class="field">
    <label class="label">Nombre</label>
    <div class="control">
      <input class="input" type="text" name="nombre" required>
    </div>
  </div>

  <div class="field">
    <label class="label">Email</label>
    <div class="control">
      <input class="input" type="email" name="email" required>
    </div>
  </div>

  <div class="field">
    <label class="label">Contraseña</label>
    <div class="control">
      <input class="input" type="password" name="password" minlength="6" required>
    </div>
  </div>

  <div class="field">
    <div class="control">
      <button class="button is-primary" type="submit">Crear cuenta</button>
    </div>
  </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
