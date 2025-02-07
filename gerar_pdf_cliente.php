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

$dompdf = new Dompdf();
$html = "<h1>Orçamento</h1>
<table border='1' width='100%' cellspacing='0' cellpadding='5'>
<tr>
    <th>Material</th>
    <th>Medida</th>
    <th>Quantidade</th>
</tr>";

foreach ($materiais as $material) {
    $nome = htmlspecialchars($material['nome']);
    $medida = htmlspecialchars($material['medida']);
    $quantidade = intval($material['quantidade']); // Pegando corretamente

    $html .= "<tr>
        <td>{$nome}</td>
        <td>{$medida}</td>
        <td>{$quantidade}</td>
    </tr>";
}

$html .= "</table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("orcamento_cliente.pdf", ["Attachment" => false]);
?>
