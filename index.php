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

    <form>
        <input type="hidden" name="id" value="" />
        <label>Nombre del Producto</label>
        <input type="text" name="nombre" placeholder="Ingrese nombre" />
        <label>Precio</label>
        <input type="number" step="0.01" name="precio" placeholder="0.00" />
        <label>Cantidad</label>
        <input type="number" name="cantidad" placeholder="0" />
        <label>Proveedor</label>
        <input type="text" name="proveedor" placeholder="Nombre proveedor" />
        <button type="button" disabled>Guardar (simulado)</button>
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
            <tr>
                <td>1</td>
                <td>Teclado Mecánico</td>
                <td>950.00</td>
                <td>15</td>
                <td>TechPro</td>
                <td class="acciones">
                    <a href="#">Editar</a>
                    <a href="#" onclick="return confirm('¿Seguro que quieres eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Monitor 24"</td>
                <td>3200.50</td>
                <td>8</td>
                <td>ViewTech</td>
                <td class="acciones">
                    <a href="#">Editar</a>
                    <a href="#" onclick="return confirm('¿Seguro que quieres eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Mouse Inalámbrico</td>
                <td>450.00</td>
                <td>30</td>
                <td>MouseMaster</td>
                <td class="acciones">
                    <a href="#">Editar</a>
                    <a href="#" onclick="return confirm('¿Seguro que quieres eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>