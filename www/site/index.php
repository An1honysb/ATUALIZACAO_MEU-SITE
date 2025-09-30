<?php
session_start();
$nome = $_SESSION['nome'] ?? 'Anthony Santos Batista';
$usuario = $_SESSION['usuario'] ?? 'anthony_s.b';
$foto = $_SESSION['foto'] ?? 'img/avatar-padrao.png'; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Minha Rede Social</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <nav class="barra-navegacao">
        <ul>
            <li><a href="#">🏠 Início</a></li>
            <li><a href="#">🔍 Pesquisa</a></li>
            <li><a href="#">➕ Nova Postagem</a></li>
            <li><a href="#">👤 Perfil</a></li>
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
            <form class="form-nova-postagem">
                <textarea placeholder="No que está pensando?"></textarea>
                <div class="form-actions">
                    <button type="submit" class="btn btn-postar">Postar</button>
                </div>
            </form>
        </header>

        <section class="feed">

            <article class="post">
                <div class="post-header">
                    <img src="img/avatar-tete.png" alt="Foto de Perfil de Tete dos Teclados" class="avatar-post">
                    <div class="post-info-autor">
                        <span class="nome-autor">Tete dos Teclados</span>
                        <span class="nickname-autor">@teteteclas</span>
                    </div>
                </div>
                <div class="post-content">
                    <p>Programação tá me deixando maluco 💻</p>
                </div>
                <div class="post-footer">
                    <div class="post-actions">
                        <button>❤️</button>
                        <label>717 likes</label>
                    </div>
                    <div class="post-actions">
                        <button>💬</button>
                        <label>262 comments</label>
                    </div>
                </div>
            </article>

            <article class="post">
                <div class="post-header">
                    <img src="img/avatar-pablo.png" alt="Foto de Perfil de Pablo Oficial" class="avatar-post">
                    <div class="post-info-autor">
                        <span class="nome-autor">Pablo</span>
                        <span class="nickname-autor">@pablooficial</span>
                    </div>
                </div>
                <div class="post-content">
                    <p>Criando um mini feed da Expovales com HTML e CSS.</p>
                </div>
                <div class="post-footer">
                    <div class="post-actions">
                        <button>❤️</button>
                        <label>558k likes</label>
                    </div>
                    <div class="post-actions">
                        <button>💬</button>
                        <label>22k comments</label>
                    </div>
                </div>
            </article>
        
        </section>

    </main>
</body>
</html>
