<?php
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- CSS AQUI -->
    <title>Criar Orçamento</title>
    <?php include 'menu.php'; ?>
  


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#material").keyup(function () {
                let nome = $(this).val();
                if (nome.length > 1) {
                    $.post("buscar_materiais.php", { nome: nome }, function (data) {
                        $("#sugestoes").html(data).show();
                    });
                } else {
                    $("#sugestoes").hide();
                }
            });

            $(document).on("click", "#sugestoes p", function () {
                let valores = $(this).data("info").split("|");
                $("#material").val(valores[0]);
                $("#medida").val(valores[1]);
                $("#preco").val(valores[2]);
                $("#sugestoes").hide();
            });
        });

        function adicionarMaterial() {
            let nome = $("#material").val();
            let medida = $("#medida").val();
            let preco = parseFloat($("#preco").val());
            let quantidade = parseInt($("#quantidade").val());

            if (nome && medida && preco && quantidade) {
                let subtotal = preco * quantidade;
                $("#lista-materiais").append(`<tr>
                    <td>${nome}</td>
                    <td>${medida}</td>
                    <td>${preco.toFixed(2)}</td>
                    <td>${quantidade}</td>
                    <td class="subtotal">${subtotal.toFixed(2)}</td>
                </tr>`);
                atualizarTotal();
                limparCampos();
            }
        }

        function atualizarTotal() {
            let total = 0;
            $(".subtotal").each(function () {
                total += parseFloat($(this).text());
            });
            $("#total").text(total.toFixed(2));
        }

        function limparCampos() {
            $("#material").val("");
            $("#medida").val("");
            $("#preco").val("");
            $("#quantidade").val("");
        }

        function gerarPDF() {
            let materiais = [];
            $("#lista-materiais tr").each(function () {
                let linha = $(this).find("td");
                materiais.push({
                    nome: linha.eq(0).text(),
                    medida: linha.eq(1).text(),
                    preco: linha.eq(2).text(),
                    quantidade: linha.eq(3).text()
                });
            });

            $.post("gerar_pdf.php", { materiais: materiais }, function () {
                window.open("orcamento.pdf");
            });
        }
    </script>
    <style>
        #sugestoes { border: 1px solid #ccc; position: absolute; background: white; display: none; max-width: 200px; }
        #sugestoes p { margin: 0; padding: 5px; cursor: pointer; }
        #sugestoes p:hover { background: #eee; }
    </style>
</head>
<body>
    <h1>Criar Orçamento</h1>
    <form>
        Material: <input type="text" id="material" required>
        <div id="sugestoes"></div>
        Medida: <input type="text" id="medida" required readonly>
        Preço: <input type="text" id="preco" required readonly>
        Quantidade: <input type="number" id="quantidade" required>
        <button type="button" onclick="adicionarMaterial()">Adicionar</button>
    </form>

    <h2>Materiais Adicionados</h2>
    <table border="1" id="lista-materiais">
        <tr>
            <th>Material</th>
            <th>Medida</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
        </tr>
    </table>

    <h3>Total: €<span id="total">0.00</span></h3>
   
    

    <form id="formPDF" method="POST" action="gerar_pdf.php" target="_blank">
    <input type="hidden" name="materiais" id="materiaisPDF">
</form>

<form id="formPDFCliente" method="POST" action="gerar_pdf_cliente.php" target="_blank">
    <input type="hidden" name="materiais" id="materiaisPDFCliente">
</form>

<button onclick="gerarPDF()">Gerar PDF (Completo)</button>
<button onclick="gerarPDFCliente()">Gerar PDF (Cliente)</button>

<script>
    function gerarPDF() {
        let materiais = JSON.stringify(capturarMateriais());
        $("#materiaisPDF").val(materiais);
        $("#formPDF").submit();
    }

    function gerarPDFCliente() {
        let materiais = JSON.stringify(capturarMateriais());
        $("#materiaisPDFCliente").val(materiais);
        $("#formPDFCliente").submit();
    }

    function capturarMateriais() {
        let materiais = [];
        $("#lista-materiais tr").each(function () {
            let linha = $(this).find("td");
            if (linha.length) {
                let nome = linha.eq(0).text().trim();
                let medida = linha.eq(1).text().trim();
                let preco = linha.eq(2).text().trim(); 
                let quantidade = linha.eq(3).text().trim(); // Agora pega corretamente a quantidade

                if (nome && medida && quantidade) {
                    materiais.push({ nome, medida, quantidade, preco });
                }
            }
        });
        return materiais;
    }
</script>


</body>
</html>
