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

startPage('Secretaria');
?>
<main id="conteudo" class="page secretary-page">
  <h1>Secretaria</h1>
  <?php if (admin()): ?>
    <div class="actions secretary-actions">
      <a class="button" href="dashboard.php">Abrir dashboard</a>
      <a class="button" href="gestao.php?tipo=turmas">Gerenciar turmas</a>
      <a class="button" href="gestao.php?tipo=cadetes">Gerenciar cadetes</a>
      <a class="button" href="taf.php">Avaliações do TAF</a>
    </div>
    <form method="post"><?=csrf()?><button name="acao" value="sair">Sair</button></form>
  <?php else: ?>
    <form method="post"><?=csrf()?><button type="submit">Entrar</button></form>
  <?php endif; ?>
</main>
<?php endPage(); ?>
