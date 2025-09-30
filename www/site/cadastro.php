<?php
session_start();

$dados = [
    'nome' => '',
    'usuario' => '',
    'email' => '',
    'senha' => '',
    'idade' => '',
    'nascimento' => '',
    'hora' => '',
    'sexo' => 'Não informado',
    'interesses' => [],
    'curso' => '',
    'mensagem' => '',
    'foto' => '',
];
$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dados['nome']       = trim($_POST['nome'] ?? '');
    $dados['usuario']    = trim($_POST['usuario'] ?? '');
    $dados['email']      = trim($_POST['email'] ?? '');
    $dados['senha']      = $_POST['senha'] ?? '';
    $dados['idade']      = $_POST['idade'] ?? '';
    $dados['nascimento'] = $_POST['nascimento'] ?? '';
    $dados['hora']       = $_POST['hora'] ?? '';
    $dados['sexo']       = $_POST['sexo'] ?? 'Não informado';
    $dados['interesses'] = $_POST['interesses'] ?? [];
    $dados['curso']      = $_POST['curso'] ?? '';
    $dados['mensagem']   = $_POST['mensagem'] ?? '';

    if (!$dados['nome'] || !$dados['usuario'] || !$dados['email'] || !$dados['senha']) {
        $erro = 'Preencha os campos obrigatórios: Nome, Usuário, E-mail e Senha.';
    }

    // Processar upload da foto de perfil
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $arquivoTemporario = $_FILES['foto']['tmp_name'];
        $nomeArquivoOriginal = $_FILES['foto']['name'];
        $extensao = strtolower(pathinfo($nomeArquivoOriginal, PATHINFO_EXTENSION));

        if (in_array($extensao, $extensoesPermitidas)) {
            $novoNome = uniqid("foto_") . "." . $extensao;
            $destino = 'uploads/' . $novoNome;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }
            if (move_uploaded_file($arquivoTemporario, $destino)) {
                $dados['foto'] = $destino;
            } else {
                $erro = "Erro ao salvar a foto.";
            }
        } else {
            $erro = "Tipo de arquivo da foto não permitido. Use JPG, PNG ou GIF.";
        }
    } else {
        // Se não enviou foto, usar a padrão
        $dados['foto'] = 'img/avatar-padrao.png'; // Local: C:\laragon\www\site\img\avatar-padrao.png
    }

    if (!$erro) {
        // Salvar dados na sessão
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['usuario'] = $dados['usuario'];
        $_SESSION['email'] = $dados['email'];
        $_SESSION['foto'] = $dados['foto'];
        // Redirecionar para index.php
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Cadastro - Minha Rede Social</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #3a076d, #4b2c82, #0b749e, #2e8b57);
      height: 100vh; margin: 0;
      display: flex; justify-content: center; align-items: center;
      color: #f0f0f5;
    }
    .container {
      background: #1a1a2e; border-radius: 15px; padding: 40px 50px;
      width: 420px; box-shadow: 0 8px 30px rgba(18, 52, 86, 0.8);
      max-height: 90vh; overflow-y: auto; color: #c3c9f1;
    }
    h1 {
      text-align: center; color: #8a79af; font-weight: 900;
      margin-bottom: 30px; letter-spacing: 2px; font-family: 'Poppins', sans-serif;
    }
    form { display: flex; flex-direction: column; gap: 18px; }
    label {
      font-weight: 600; margin-bottom: 6px; display: flex;
      align-items: center; gap: 10px; font-size: 15px; color: #abb2f9;
    }
    label i { color: #74d69d; min-width: 18px; text-align: center; }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"],
    input[type="date"],
    input[type="time"],
    select,
    textarea {
      padding: 12px 15px; border: 2px solid #483d8b; border-radius: 10px;
      font-size: 15px; transition: border-color 0.3s ease;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; resize: vertical;
      background-color: #2d2f54; color: #e0e6ff;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    select:focus,
    textarea:focus {
      outline: none; border-color: #74d69d; box-shadow: 0 0 8px #74d69d;
      background-color: #393e72; color: #d6e7ff;
    }
    fieldset {
      border: 2px solid #483d8b; border-radius: 10px;
      padding: 12px 15px; margin-bottom: 15px;
    }
    legend {
      font-weight: 700; color: #8a79af; font-size: 16px; padding: 0 8px;
    }
    fieldset label {
      font-weight: normal; gap: 5px; font-size: 14px;
      color: #b9c7ff; cursor: pointer; margin-right: 15px;
    }
    input[type="checkbox"],
    input[type="radio"] {
      transform: scale(1.15);
      cursor: pointer;
    }
    input[type="file"] {
      cursor: pointer; border: none; padding-left: 0;
      background-color: transparent; color: #74d69d; font-weight: 600;
    }
    button, input[type="submit"] {
      background: #74d69d; border: none; color: #091e15;
      font-weight: 700; font-size: 18px; padding: 14px 0;
      border-radius: 12px; cursor: pointer;
      box-shadow: 0 6px 15px rgba(116, 214, 157, 0.7);
      transition: background 0.3s ease;
    }
    button:hover, input[type="submit"]:hover {
      background: #59a87d; box-shadow: 0 8px 20px rgba(89, 168, 125, 0.9);
    }
    .erro {
      background: #ff5555; color: #330000; padding: 12px 15px;
      margin-bottom: 20px; border-radius: 8px; font-weight: 700;
      text-align: center; box-shadow: 0 0 3px #660000;
    }
  </style>
</head>
<body>
  <div class="container" role="main">
    <h1><i class="fa-solid fa-user-plus"></i> Criar Conta</h1>

    <?php if ($erro): ?>
      <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data" novalidate>
      <label for="nome"><i class="fa-solid fa-user"></i> Nome:</label>
      <input type="text" id="nome" name="nome" required placeholder="Digite seu nome" value="<?= htmlspecialchars($dados['nome']) ?>">

      <label for="usuario"><i class="fa-solid fa-user-tag"></i> Usuário:</label>
      <input type="text" id="usuario" name="usuario" required placeholder="Digite seu usuário" value="<?= htmlspecialchars($dados['usuario']) ?>">

      <label for="email"><i class="fa-solid fa-envelope"></i> E-mail:</label>
      <input type="email" id="email" name="email" required placeholder="Digite seu e-mail" value="<?= htmlspecialchars($dados['email']) ?>">

      <label for="senha"><i class="fa-solid fa-lock"></i> Senha:</label>
      <input type="password" id="senha" name="senha" required minlength="6" placeholder="Digite sua senha">

      <label for="idade"><i class="fa-solid fa-cake-candles"></i> Idade:</label>
      <input type="number" id="idade" name="idade" min="0" max="120" placeholder="Ex: 25" value="<?= htmlspecialchars($dados['idade']) ?>">

      <label for="nascimento"><i class="fa-solid fa-calendar-day"></i> Data de nascimento:</label>
      <input type="date" id="nascimento" name="nascimento" value="<?= htmlspecialchars($dados['nascimento']) ?>">

      <label for="hora"><i class="fa-solid fa-clock"></i> Horário preferido:</label>
      <input type="time" id="hora" name="hora" value="<?= htmlspecialchars($dados['hora']) ?>">

      <fieldset>
        <legend><i class="fa-solid fa-venus-mars"></i> Sexo:</legend>
        <label><input type="radio" name="sexo" value="Masculino" <?= $dados['sexo'] == 'Masculino' ? 'checked' : '' ?>> Masculino</label>
        <label><input type="radio" name="sexo" value="Feminino" <?= $dados['sexo'] == 'Feminino' ? 'checked' : '' ?>> Feminino</label>
        <label><input type="radio" name="sexo" value="Outro" <?= $dados['sexo'] == 'Outro' ? 'checked' : '' ?>> Outro</label>
      </fieldset>

      <fieldset>
        <legend><i class="fa-solid fa-heart"></i> Interesses:</legend>
        <label><input type="checkbox" name="interesses[]" value="Programação" <?= in_array('Programação', $dados['interesses']) ? 'checked' : '' ?>> Programação</label>
        <label><input type="checkbox" name="interesses[]" value="Design" <?= in_array('Design', $dados['interesses']) ? 'checked' : '' ?>> Design</label>
        <label><input type="checkbox" name="interesses[]" value="Jogos" <?= in_array('Jogos', $dados['interesses']) ? 'checked' : '' ?>> Jogos</label>
      </fieldset>

      <label for="curso"><i class="fa-solid fa-graduation-cap"></i> Curso:</label>
      <select id="curso" name="curso">
        <option value="" <?= $dados['curso'] === '' ? 'selected' : '' ?>>-- Selecione --</option>
        <option value="Sistemas de Informação" <?= $dados['curso'] == 'Sistemas de Informação' ? 'selected' : '' ?>>Sistemas de Informação</option>
        <option value="Ciência da Computação" <?= $dados['curso'] == 'Ciência da Computação' ? 'selected' : '' ?>>Ciência da Computação</option>
        <option value="Engenharia de Software" <?= $dados['curso'] == 'Engenharia de Software' ? 'selected' : '' ?>>Engenharia de Software</option>
      </select>

      <label for="mensagem"><i class="fa-solid fa-comment"></i> Mensagem:</label>
      <textarea id="mensagem" name="mensagem" rows="3" placeholder="Digite sua mensagem"><?= htmlspecialchars($dados['mensagem']) ?></textarea>

      <label for="foto"><i class="fa-solid fa-image"></i> Foto de perfil:</label>
      <input type="file" id="foto" name="foto" accept="image/*">

      <input type="submit" value="Cadastrar">
    </form>
  </div>
</body>
</html>
