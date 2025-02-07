<?php
include 'config.php';

// Adicionar material
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar'])) {
    $nome = $conn->real_escape_string($_POST['nome']);
    $medida = $conn->real_escape_string($_POST['medida']);
    $preco = floatval($_POST['preco']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    
    $sql = "INSERT INTO materiais (nome, medida, preco, categoria) VALUES ('$nome', '$medida', '$preco', '$categoria')";
    $conn->query($sql);
    header("Location: index.php");
    exit;
}

// Editar material
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $medida = $conn->real_escape_string($_POST['medida']);
    $preco = floatval($_POST['preco']);
    
    $sql = "UPDATE materiais SET nome='$nome', medida='$medida', preco='$preco' WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
    exit;
}

// Remover material
if (isset($_GET['remover'])) {
    $id = intval($_GET['remover']);
    $sql = "DELETE FROM materiais WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
    exit;
}

// Listar materiais
$materiais = $conn->query("SELECT * FROM materiais ORDER BY categoria, nome");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Orçamento</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- CSS AQUI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'menu.php'; ?>

    <h1>Gerenciamento de Materiais</h1>
   

    <form method="POST">
        Nome: <input type="text" name="nome" required>
        Medida: <input type="text" name="medida" placeholder="Ex: 700x300" pattern="\d+x\d+" required>
        Preço: <input type="number" step="0.01" name="preco" required>
        Categoria: 
        <select name="categoria">
            <option value="Kit">Kit</option>
            <option value="Acessório">Acessório</option>
            <option value="Termolaminado">Termolaminado</option>
        </select>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <table border="1">
        <tr>
            <th>Nome</th>
            <th>Medida</th>
            <th>Preço</th>
            <th>Categoria</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $materiais->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['nome']; ?></td>
                <td><?= $row['medida']; ?></td>
                <td><?= $row['preco']; ?></td>
                <td><?= $row['categoria']; ?></td>
                <td>
                    <a href="?remover=<?= $row['id']; ?>">Remover</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
