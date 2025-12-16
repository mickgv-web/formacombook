<?php
require_once __DIR__ . '/includes/funciones.php';
include __DIR__ . '/includes/header.php';

$conn = conectarBD();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT usuario_id, nombre, password FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();

        if ($usuario && password_verify($password, $usuario["password"])) {
            // Guardamos datos en sesión
            $_SESSION["usuario_id"] = $usuario["usuario_id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];

            header("Location: index.php");
            exit;
        } else {
            $mensaje = '<div class="notification is-danger">Credenciales incorrectas.</div>';
        }
    } else {
        $mensaje = '<div class="notification is-warning">Introduce email y contraseña.</div>';
    }
}
?>

<h1 class="title">Iniciar sesión</h1>
<?php echo $mensaje; ?>

<form method="post" class="box">
  <div class="field">
    <label class="label">Email</label>
    <div class="control">
      <input class="input" type="email" name="email" required>
    </div>
  </div>

  <div class="field">
    <label class="label">Contraseña</label>
    <div class="control">
      <input class="input" type="password" name="password" required>
    </div>
  </div>

  <div class="field">
    <div class="control">
      <button class="button is-primary" type="submit">Entrar</button>
    </div>
  </div>

  <div class="has-text-centered mt-3">
    <a href="register.php"><strong>¿No tienes cuenta?</strong> Regístrate</a><br>
    <a href="#">¿Olvidaste tu contraseña?</a>
  </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
