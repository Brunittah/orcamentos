<?php
include 'config.php';

if (isset($_POST['nome'])) {
    $nome = $conn->real_escape_string($_POST['nome']);
    $result = $conn->query("SELECT * FROM materiais WHERE nome LIKE '%$nome%'");

    while ($row = $result->fetch_assoc()) {
        echo "<p data-info='" . $row['nome'] . "|" . $row['medida'] . "|" . $row['preco'] . "' onclick=\"selecionarMaterial(this)\">" 
        . $row['nome'] . " (" . $row['medida'] . ")</p>";
    }
}
?>
