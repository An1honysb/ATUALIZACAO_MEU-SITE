<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: index.php');
    exit;
}

$nome = $_SESSION['nome'] ?? 'Anthony Santos Batista';
$usuario = $_SESSION['usuario'] ?? 'anthony_s.b';
$foto = $_SESSION['foto'] ?? 'img/avatar-padrao.png';

$postsFixos = [
    [
        'nome' => 'Tete dos Teclados',
        'usuario' => 'teteteclas',
        'foto' => 'img/avatar-tete.png',
        'mensagem' => 'Programação tá me deixando maluco 💻',
        'data' => '01/10/2025 21:54',
        'likes' => 0,
        'comments' => 0,
    ],
    [
        'nome' => 'Pablo',
        'usuario' => 'pablooficial',
        'foto' => 'img/avatar-pablo.png',
        'mensagem' => 'Criando um mini feed da Expovales com HTML e CSS.',
        'data' => '01/10/2025 21:54',
        'likes' => 0,
        'comments' => 0,
    ],
];

// Inicializa feed e comentários na sessão
if (!isset($_SESSION['feed'])) $_SESSION['feed'] = [];
if (!isset($_SESSION['comments'])) $_SESSION['comments'] = [];

foreach ($postsFixos as &$postFixo) {
    if (!isset($postFixo['comments'])) $postFixo['comments'] = 0;
    if (!isset($postFixo['likes'])) $postFixo['likes'] = 0;
}
unset($postFixo);

foreach ($_SESSION['feed'] as &$postUser) {
    if (!isset($postUser['comments'])) $postUser['comments'] = 0;
    if (!isset($postUser['likes'])) $postUser['likes'] = 0;
}
unset($postUser);

$posts = array_merge($_SESSION['feed'], $postsFixos);
$tipoPosts = array_merge(array_fill(0, count($_SESSION['feed']), 'u'), array_fill(0, count($postsFixos), 'f'));

// Criar nova postagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensagem']) && trim($_POST['mensagem']) !== '') {
    $nova_postagem = [
        'nome' => $nome,
        'usuario' => $usuario,
        'foto' => $foto,
        'mensagem' => htmlspecialchars(trim($_POST['mensagem'])),
        'data' => date('d/m/Y H:i'),
        'likes' => 0,
        'comments' => 0,
    ];
    array_unshift($_SESSION['feed'], $nova_postagem);
    header("Location: feed.php");
    exit;
}

// Curtidas
if (isset($_GET['like'])) {
    $idxRaw = $_GET['like'];
    $tipo = substr($idxRaw, 0, 1);
    $idx = (int)substr($idxRaw, 1);

    if ($tipo == 'u' && isset($_SESSION['feed'][$idx])) {
        $_SESSION['feed'][$idx]['likes']++;
    } elseif ($tipo == 'f' && isset($postsFixos[$idx])) {
        $postsFixos[$idx]['likes']++;
    }
    header("Location: feed.php");
    exit;
}

// Comentários
if (isset($_POST['comment']) && isset($_POST['post_type']) && isset($_POST['post_idx'])) {
    $tipo = $_POST['post_type'];
    $idx = (int)$_POST['post_idx'];
    $comentario = trim($_POST['comment']);
    if ($comentario !== '') {
        $keyComent = $tipo . $idx;
        if (!isset($_SESSION['comments'][$keyComent])) $_SESSION['comments'][$keyComent] = [];
        $_SESSION['comments'][$keyComent][] = [
            'usuario' => $usuario,
            'mensagem' => htmlspecialchars($comentario),
            'data' => date('d/m/Y H:i')
        ];
        if ($tipo == 'u' && isset($_SESSION['feed'][$idx])) {
            $_SESSION['feed'][$idx]['comments']++;
        } elseif ($tipo == 'f' && isset($postsFixos[$idx])) {
            $postsFixos[$idx]['comments']++;
        }
    }
    header("Location: feed.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Feed - Minha Rede Social</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<nav class="barra-navegacao">
    <ul>
        <li><a href="#">🏠 Início</a></li>
        <li><a href="#">🔍 Pesquisa</a></li>
        <li><a href="#">➕ Nova Postagem</a></li>
        <li><a href="#">👤 Perfil</a></li>
        <li><a href="logout.php" style="color:#74d69d;">Sair</a></li>
    </ul>
</nav>
<main class="conteudo-principal">
    <header class="cabecalho-pagina">
        <div class="info-perfil">
            <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de Perfil" class="avatar" />
            <div class="info-texto-perfil">
                <span class="nome-usuario"><?= htmlspecialchars($nome) ?></span>
                <span class="nickname-usuario">@<?= htmlspecialchars($usuario) ?></span>
            </div>
            <a href="cadastro.php" class="btn btn-editar-perfil">Editar Perfil</a>
        </div>
        <form class="form-nova-postagem" method="post" action="">
            <textarea name="mensagem" placeholder="No que está pensando?" required></textarea>
            <div class="form-actions">
                <button type="submit" class="btn btn-postar">Postar</button>
            </div>
        </form>
    </header>
    <section class="feed">
        <?php foreach ($posts as $idx => $post): ?>
            <?php $tipo = $tipoPosts[$idx]; ?>
            <article class="post">
                <div class="post-header">
                    <img src="<?= htmlspecialchars($post['foto']) ?>" alt="Foto de Perfil" class="avatar-post" />
                    <div class="post-info-autor">
                        <span class="nome-autor"><?= htmlspecialchars($post['nome']) ?></span>
                        <span class="nickname-autor">@<?= htmlspecialchars($post['usuario']) ?></span>
                        <span style="font-size:12px;color:#abb2f9;"><?= $post['data'] ?></span>
                    </div>
                </div>
                <div class="post-content">
                    <p><?= nl2br($post['mensagem']) ?></p>
                </div>
                <div class="post-footer">
                    <div class="post-actions">
                        <form style="display:inline;" method="get" action="">
                            <input type="hidden" name="like" value="<?= $tipo . $idx ?>">
                            <button type="submit" class="like-btn" title="Curtir">❤</button>
                        </form>
                        <label><?= $post['likes'] ?> likes</label>
                    </div>
                    <div class="post-actions">
                        <button title="Comentários">💬</button>
                        <label><?= $post['comments'] ?> comments</label>
                    </div>
                </div>
                <div class="comment-list">
                    <?php 
                    $keyComent = $tipo . $idx;
                    if (!empty($_SESSION['comments'][$keyComent])):
                        foreach ($_SESSION['comments'][$keyComent] as $comentario): ?>
                            <div class="comment-item">
                                <span class="comment-user"><?= htmlspecialchars($comentario['usuario']) ?></span>:
                                <?= nl2br($comentario['mensagem']) ?>
                                <span class="comment-date">(<?= $comentario['data'] ?>)</span>
                            </div>
                        <?php endforeach; 
                    endif;
                    ?>
                </div>
                <form class="comment-form" method="post" action="">
                    <textarea name="comment" placeholder="Escreva um comentário..." rows="2" required></textarea>
                    <input type="hidden" name="post_type" value="<?= $tipo ?>" />
                    <input type="hidden" name="post_idx" value="<?= $idx ?>" />
                    <button type="submit">Comentar</button>
                </form>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
