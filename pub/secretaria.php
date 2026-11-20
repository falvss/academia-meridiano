<?php
require __DIR__.'/../app/bootstrap.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST'){
 if(field($_POST,'acao')==='sair'){unset($_SESSION['admin']);session_regenerate_id(true);go('index.php');}
 if($config['admin_hash'] && password_verify(field($_POST,'senha'),$config['admin_hash'])){
    session_regenerate_id(true);$_SESSION['admin']=true;go('secretaria.php');
 }
 $error='Senha inválida ou acesso ainda não configurado.';
}
startPage('Secretaria'); ?>
<main class="page"><p class="kicker">ESTAÇÃO DA TORRE</p><h1>Secretaria</h1>
<?php if(admin()): ?><div class="actions"><a class="button" href="gestao.php?tipo=turmas">Gerenciar turmas</a><a class="button" href="gestao.php?tipo=cadetes">Gerenciar cadetes</a><a class="button" href="taf.php">Avaliações do TAF</a></div><form method="post"><?=csrf()?><button name="acao" value="sair">Encerrar sessão</button></form>
<?php else: ?><p>Acesso da equipe de formação acadêmica.</p><form method="post"><?=csrf()?><label>Senha da secretaria<input type="password" name="senha" required autocomplete="current-password"></label><button>Entrar</button></form><p class="error"><?=h($error)?></p><?php endif; ?></main><?php endPage(); ?>
