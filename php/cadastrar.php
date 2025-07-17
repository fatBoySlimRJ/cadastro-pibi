<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Função para remover acentos e caracteres especiais
function removerAcentos($texto) {
    $texto = preg_replace('/[áàãâä]/u', 'A', $texto);
    $texto = preg_replace('/[éèêë]/u', 'E', $texto);
    $texto = preg_replace('/[íìîï]/u', 'I', $texto);
    $texto = preg_replace('/[óòõôö]/u', 'O', $texto);
    $texto = preg_replace('/[úùûü]/u', 'U', $texto);
    $texto = preg_replace('/[ç]/u', 'C', $texto);
    $texto = preg_replace('/[ñ]/u', 'n', $texto);
    $texto = preg_replace('/[ÁÀÃÂÄ]/u', 'A', $texto);
    $texto = preg_replace('/[ÉÈÊË]/u', 'E', $texto);
    $texto = preg_replace('/[ÍÌÎÏ]/u', 'I', $texto);
    $texto = preg_replace('/[ÓÒÕÔÖ]/u', 'O', $texto);
    $texto = preg_replace('/[ÚÙÛÜ]/u', 'U', $texto);

    return $texto;
}


// Dados de conexão
$host = "localhost";
$user = "root";
$password = "";
$database = "cadastro_usuarios";

// Conectar ao banco
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Processar dados do formulário

$nome = $_POST['nome']; // Recebe o nome original
$nome = strtoupper($nome); // 1º: Converte TUDO para MAIÚSCULAS
$nome = removerAcentos($nome); // 2º: Remove acentos (já está em maiúsculas)
$nome = str_replace(['Ç'], 'C', $nome); // 3º: Substitui Ç por C
$idade = intval($_POST['idade']); // Garante que é número
$membro_pibi = $_POST['membro_pibi'];
$trabalhou_resgate = $_POST['trabalhou_resgate'];
$equipe = $_POST['equipe'];
$telefone = $_POST['telefone']; // Já formatado via JS
$batizado = $_POST['batizado'];

// Validar idade (máximo 2 dígitos e valor razoável)
if ($idade < 1 || $idade > 99) {
    die("Idade inválida!");
}

// Validar telefone (formato: (XX)XXXXX-XXXX)
if (!preg_match('/^\(\d{2}\)\d{5}-\d{4}$/', $telefone)) {
    die("Telefone no formato incorreto!");
}

// Inserir no banco (com proteção contra SQL Injection)
$sql = $conn->prepare("INSERT INTO usuarios (nome, idade, telefone, batizado, membro_pibi, trabalhou_resgate, equipe) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param("sisssss", $nome, $idade, $telefone, $batizado, $membro_pibi, $trabalhou_resgate, $equipe);

if ($sql->execute()) {
    // Redireciona para a página de confirmação com status=sucesso
    header("Location: ../confirmacao.php?status=sucesso");
    exit(); // Isso é CRÍTICO para evitar execução adicional
} else {
    // Redireciona para a página de confirmação com status=erro
    header("Location: ../confirmacao.php?status=erro&msg=" . urlencode($sql->error));
    exit();
}
$conn->close();
?>