<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

if (!isset($_POST['materiais']) || empty($_POST['materiais'])) {
    die("Nenhum dado recebido!");
}

$materiais = json_decode($_POST['materiais'], true);
if (!$materiais || count($materiais) == 0) {
    die("Nenhum material encontrado!");
}

$total = 0;
$dompdf = new Dompdf();
$html = "<h1>Orçamento Completo</h1>
<table border='1' width='100%' cellspacing='0' cellpadding='5'>
<tr>
    <th>Material</th>
    <th>Medida</th>
    <th>Quantidade</th>
    <th>Preço Unitário (€)</th>
    <th>Total (€)</th>
</tr>";

foreach ($materiais as $material) {
    $nome = htmlspecialchars($material['nome']);
    $medida = htmlspecialchars($material['medida']);
    $quantidade = intval($material['quantidade']); // Pegando corretamente
    $preco = number_format(floatval($material['preco']), 2, ',', '.'); // Pegando corretamente
    $subtotal = number_format(floatval($material['preco']) * $quantidade, 2, ',', '.');

    $total += floatval($material['preco']) * $quantidade;

    $html .= "<tr>
        <td>{$nome}</td>
        <td>{$medida}</td>
        <td>{$quantidade}</td>
        <td>{$preco} €</td>
        <td>{$subtotal} €</td>
    </tr>";
}

$html .= "<tr>
    <td colspan='4' align='right'><strong>Total:</strong></td>
    <td><strong>" . number_format($total, 2, ',', '.') . " €</strong></td>
</tr>";

$html .= "</table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("orcamento_completo.pdf", ["Attachment" => false]);
?>
