<?php
// Cargar variables desde keys.env manualmente
if (file_exists(__DIR__ . '/keys.env')) {
    $lines = file(__DIR__ . '/keys.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // ignorar comentarios
        putenv(trim($line));
    }
}

// Leer variables de entorno
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'inventario';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASS') ?: '';
$port = getenv('DB_PORT') ?: 5432;

// Crear conexión PDO
$dsn = "pgsql:host=$host;port=$port;dbname=$db;";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// === ACCIONES CRUD ===
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

// Obtener lista de productos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
$productos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Inventario Tech United</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 40px;
        background: #f5f7fa;
        color: #333;
    }

    h1 {
        color: #007acc;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #007acc;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #e6f2ff;
    }

    form {
        margin-top: 20px;
        background: white;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }

    input[type=text],
    input[type=number] {
        width: 100%;
        padding: 8px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #007acc;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #005f99;
    }

    .acciones a {
        margin-right: 12px;
        color: #007acc;
        text-decoration: none;
    }

    .acciones a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>

    <h1>Inventario Tech United</h1>

    <?php
// Para editar, carga el producto a editar
if ($accion === 'editar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();
} else {
    $producto = ['id'=>'', 'nombre'=>'', 'precio'=>'', 'cantidad'=>'', 'proveedor'=>''];
}
?>

    <form method="post" action="inventario.php?accion=<?php echo $producto['id'] ? 'actualizar' : 'guardar'; ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>" />
        <label>Nombre del Producto</label>
        <input type="text" name="nombre" required value="<?php echo htmlspecialchars($producto['nombre']); ?>" />
        <label>Precio</label>
        <input type="number" step="0.01" name="precio" required
            value="<?php echo htmlspecialchars($producto['precio']); ?>" />
        <label>Cantidad</label>
        <input type="number" name="cantidad" required value="<?php echo htmlspecialchars($producto['cantidad']); ?>" />
        <label>Proveedor</label>
        <input type="text" name="proveedor" required value="<?php echo htmlspecialchars($producto['proveedor']); ?>" />
        <button type="submit"><?php echo $producto['id'] ? 'Actualizar' : 'Guardar'; ?></button>
    </form>

    <table>
        <thead>
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
            <?php foreach ($productos as $p): ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                <td><?php echo number_format($p['precio'], 2); ?></td>
                <td><?php echo $p['cantidad']; ?></td>
                <td><?php echo htmlspecialchars($p['proveedor']); ?></td>
                <td class="acciones">
                    <a href="inventario.php?accion=editar&id=<?php echo $p['id']; ?>">Editar</a>
                    <a href="inventario.php?accion=eliminar&id=<?php echo $p['id']; ?>"
                        onclick="return confirm('¿Seguro que quieres eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>