<?php
require __DIR__.'/../app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_regenerate_id(true);
    if (field($_POST, 'acao') === 'sair') {
        unset($_SESSION['secretaria_aberta']);
        go('index.php');
    }
    $_SESSION['secretaria_aberta'] = true;
    go('secretaria.php');
}
startPage('Secretaria'); ?>
<main id="conteudo" class="page secretary-page"><p class="kicker">ESTAÇÃO DA TORRE</p><h1>Secretaria</h1>
<?php if(admin()): ?><p class="lead">Painel administrativo da formação, turmas e cadetes.</p><div class="admin-identity"><span class="status"><i></i> Sessão aberta</span></div><div class="actions secretary-actions"><a class="button" href="dashboard.php">Abrir dashboard <span>↗</span></a><a class="button" href="gestao.php?tipo=turmas">Gerenciar turmas</a><a class="button" href="gestao.php?tipo=cadetes">Gerenciar cadetes</a><a class="button" href="taf.php">Avaliações do TAF</a></div><form method="post"><?=csrf()?><button name="acao" value="sair">Encerrar sessão</button></form>
<?php else: ?><form class="secretary-login" method="post"><?=csrf()?><button type="submit">Entrar <span>→</span></button></form><?php endif; ?></main><?php endPage(); ?>
