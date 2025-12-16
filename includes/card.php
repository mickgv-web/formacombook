<?php
function renderCard(mysqli $conn, array $foto): void
{
?>
    <div class="column is-full-mobile is-half-tablet is-one-quarter-desktop">
        <div class="card">
            <div class="card-image">
                <figure class="image is-4by3">
                    <img src="uploads/fotos/<?php echo htmlspecialchars($foto['ruta_imagen']); ?>"
                        alt="Foto de <?php echo htmlspecialchars($foto['nombre']); ?>">
                </figure>
            </div>
            <div class="card-content">
                <!-- Nombre y fecha -->
                <p class="is-size-6 has-text-weight-semibold has-text-link">
                    <?php echo htmlspecialchars($foto['nombre']); ?>
                </p>
                <p class="is-size-7 has-text-grey">
                    <?php echo htmlspecialchars($foto['created_at']); ?>
                </p>

                <!-- Votos + botón -->
                <div class="card-actions">
                    <?php if (isset($_SESSION["usuario_id"])): ?>
                        <?php if ($_SESSION["usuario_id"] != $foto["usuario_id"]): ?>
                            <?php $valor = usuarioYaVoto($conn, $_SESSION["usuario_id"], $foto["foto_id"]); ?>

                            <?php if ($valor == 1): ?>
                                <!-- Corazón lleno (like activo) -->
                                <form method="post" action="unvote.php" class="is-inline">
                                    <input type="hidden" name="foto_id" value="<?php echo (int)$foto['foto_id']; ?>">
                                    <button class="button is-white" type="submit">
                                        <span class="icon has-text-danger">
                                            <i class="fa-solid fa-heart"></i>
                                        </span>
                                        <span><?php echo (int)$foto['votos']; ?></span>
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- Corazón vacío (like inactivo) -->
                                <form method="post" action="vote.php" class="is-inline">
                                    <input type="hidden" name="foto_id" value="<?php echo (int)$foto['foto_id']; ?>">
                                    <button class="button is-white" type="submit">
                                        <span class="icon has-text-grey">
                                            <i class="fa-regular fa-heart"></i>
                                        </span>
                                        <span><?php echo (int)$foto['votos']; ?></span>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Propia foto: contador fijo -->
                            <span class="icon has-text-grey">
                                <i class="fa-regular fa-heart"></i>
                            </span>
                            <span><?php echo (int)$foto['votos']; ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Usuario no logueado: solo contador -->
                        <span class="icon has-text-grey">
                            <i class="fa-regular fa-heart"></i>
                        </span>
                        <span><?php echo (int)$foto['votos']; ?></span>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
<?php
}
