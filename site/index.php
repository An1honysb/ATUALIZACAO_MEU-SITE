<?php
session_start();

$erro = '';
$usuarioPadrao = 'anthonysb';
$senhaPadrao = '123456';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($usuario === $usuarioPadrao && $senha === $senhaPadrao) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuarioPadrao;
        $_SESSION['nome'] = 'Anthony SB';
        $_SESSION['foto'] = 'img/avatar-padrao.png';
        header('Location: feed.php');
        exit;
    }
    if (isset($_SESSION['usuario'], $_SESSION['senha'])) {
        if ($usuario === $_SESSION['usuario'] && $senha === $_SESSION['senha']) {
            $_SESSION['logado'] = true;
            header('Location: feed.php');
            exit;
        } else {
            $erro = "Usuário ou senha inválidos.";
        }
    } else {
        $erro = "Nenhum usuário cadastrado. Cadastre-se primeiro.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Login - Minha Rede Social</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="centralizador">
    <div class="container-login">
        <h1>Login</h1>
        <?php if (!empty($erro)): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <button type="submit" class="botao-login">Entrar</button>
        </form>
        <div class="link-cadastro">
            <span>Não tem conta?</span>
            <a href="cadastro.php">Cadastre-se</a>
        </div>
    </div>
</div>
</body>
</html>
