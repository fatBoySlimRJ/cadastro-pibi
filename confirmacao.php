<?php
$mensagem_erro = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 text-center">
        <?php if ($_GET['status'] === 'sucesso'): ?>
            <h1 class="text-success">✅ Cadastro realizado!</h1>
        <?php else: ?>
            <h1 class="text-danger">❌ Erro!</h1>
            <p class="text-muted"><?= $mensagem_erro ?></p>
        <?php endif; ?>
        <a href="index.html" class="btn btn-primary mt-3">Voltar</a>
    </div>
</body>
</html>