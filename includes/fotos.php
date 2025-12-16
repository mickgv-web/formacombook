<?php
require_once __DIR__ . '/funciones.php';

function obtenerFotos(mysqli $conn, string $orden = 'fecha', int $limite = 12): array {
    $orderBy = "f.created_at DESC";
    if ($orden === 'likes') {
        $orderBy = "votos DESC";
    }

    $sql = "
      SELECT f.foto_id, f.ruta_imagen, f.created_at, u.nombre, u.usuario_id,
             COALESCE(SUM(v.valor), 0) AS votos
      FROM foto f
      JOIN usuario u ON u.usuario_id = f.usuario_id
      LEFT JOIN votos v ON v.foto_id = f.foto_id
      GROUP BY f.foto_id
      ORDER BY $orderBy
      LIMIT ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limite);
    $stmt->execute();
    $res = $stmt->get_result();
    $fotos = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $fotos;
}

function usuarioYaVoto(mysqli $conn, int $usuario_id, int $foto_id): ?int {
    $sql = "SELECT valor FROM votos WHERE usuario_id = ? AND foto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $foto_id);
    $stmt->execute();
    $stmt->bind_result($valor);
    $stmt->fetch();
    $stmt->close();
    return $valor ?? null;
}
