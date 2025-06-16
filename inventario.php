<?php
// ==== CONFIGURACIÓN DE CONEXIÓN A POSTGRES ====
$host = 'localhost';
$db   = 'inventario';
$user = 'postgres';
$pass = 'tu_contraseña';
$port = 5432;

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// ==== ACCIONES ====
$accion = $_GET['accion'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($accion === 'guardar') {
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, precio, cantidad, proveedor) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['nombre'], $_POST['precio'], $_POST['cantidad'], $_POST['proveedor']]);
    } elseif ($accion === 'actualizar') {
        $stmt = $pdo->prepare("UPDATE productos SET nombre=?, precio=?, cantidad=?, proveedor=? WHERE id=?");
        $stmt->execute([$_POST['nombre'], $_POST['precio'], $_POST['cantidad'], $_POST['proveedor'], $_POST['id']]);
    }
    header("Location: inventario.php");
    exit;
}
if ($accion === 'eliminar' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id=?");
    $stmt->execute([$_GET['id']]);
    header("Location: inventario.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">📦 Inventario de Productos</h2>

        <?php if ($accion === 'editar' && isset($_GET['id'])):
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $producto = $stmt->fetch();
  ?>
        <h4>✏️ Editar Producto</h4>
        <form method="POST" action="?accion=actualizar" class="mb-4">
            <input type="hidden" name="id" value="<?= $producto['id'] ?>">
            <input class="form-control mb-2" name="nombre" value="<?= $producto['nombre'] ?>" required>
            <input class="form-control mb-2" name="precio" type="number" step="0.01" value="<?= $producto['precio'] ?>"
                required>
            <input class="form-control mb-2" name="cantidad" type="number" value="<?= $producto['cantidad'] ?>"
                required>
            <input class="form-control mb-2" name="proveedor" value="<?= $producto['proveedor'] ?>"
                placeholder="Nombre del proveedor" required>
            <button class="btn btn-primary">Guardar Cambios</button>
            <a href="inventario.php" class="btn btn-secondary">Cancelar</a>
        </form>
        <?php else: ?>
        <h4>➕ Agregar Nuevo Producto</h4>
        <form method="POST" action="?accion=guardar" class="mb-4">
            <input class="form-control mb-2" name="nombre" placeholder="Nombre del producto" required>
            <input class="form-control mb-2" name="precio" type="number" step="0.01" placeholder="Precio" required>
            <input class="form-control mb-2" name="cantidad" type="number" placeholder="Cantidad" required>
            <input class="form-control mb-2" name="proveedor" placeholder="Nombre del proveedor" required>
            <button class="btn btn-success">Agregar</button>
        </form>
        <?php endif; ?>

        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pdo->query("SELECT * FROM productos ORDER BY id") as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['nombre'] ?></td>
                    <td>$<?= number_format($p['precio'], 2) ?></td>
                    <td><?= $p['cantidad'] ?></td>
                    <td><?= $p['proveedor'] ?></td>
                    <td>
                        <a href="?accion=editar&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                        <a href="?accion=eliminar&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar producto?')">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>