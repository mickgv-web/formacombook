<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formacombook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/navbar.js" defer></script>
</head>

<body>
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="index.php">
                <i class="fa-solid fa-camera-retro"></i>&nbsp;<strong>Formacombook</strong>
            </a>

            <!-- Botón hamburguesa -->
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="mainNavbar">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <!-- Menú principal -->
        <div id="mainNavbar" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="index.php"><i class="fa-solid fa-home"></i>&nbsp;Inicio</a>
                <a class="navbar-item" href="upload.php"><i class="fa-solid fa-upload"></i>&nbsp;Subir foto</a>
            </div>

            <div class="navbar-end">
                <?php if (isset($_SESSION["usuario_id"])): ?>
                    <div class="navbar-item">
                        <i class="fa-solid fa-user"></i>&nbsp;<strong><?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?></strong>
                    </div>
                    <a class="navbar-item" href="perfil.php">
                        <i class="fa-solid fa-id-card"></i>&nbsp;Mi perfil
                    </a>
                    <a class=" navbar-item" href="logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>&nbsp;Cerrar sesión
                    </a>
                <?php else: ?>
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-light" href="login.php"><i class="fa-solid fa-right-to-bracket"></i>&nbsp;Login</a>
                            <a class="button is-primary" href="register.php"><i class="fa-solid fa-user-plus"></i>&nbsp;Registro</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="section">
        <div class="container">