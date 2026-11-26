<?php
require __DIR__.'/../app/bootstrap.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST'){
 if(field($_POST,'acao')==='sair'){unset($_SESSION['admin_user_id'],$_SESSION['admin']);session_regenerate_id(true);go('index.php');}
 $email=strtolower(field($_POST,'email'));$senha=field($_POST,'senha');
 $user=$email!=='' ? query('SELECT id,senha_hash FROM admin_users WHERE email=:email AND ativo=1 LIMIT 1',['email'=>$email])->fetch() : false;
 if($user && password_verify($senha,$user['senha_hash'])){
    session_regenerate_id(true);$_SESSION['admin_user_id']=(int)$user['id'];$_SESSION['admin']=true;go('secretaria.php');
 }
 $error='E-mail ou senha inválidos.';
}
startPage('Secretaria'); ?>
<main id="conteudo" class="page secretary-page"><p class="kicker">ESTAÇÃO DA TORRE</p><h1>Secretaria</h1>
<?php if(admin()): $user=adminUser(); ?><p class="lead">Painel administrativo da formação, turmas e cadetes.</p><div class="admin-identity"><span class="status"><i></i> Sessão autenticada</span><span><?=h($user['nome'] ?? '')?> · <?=h($user['email'] ?? '')?></span></div><div class="actions secretary-actions"><a class="button" href="dashboard.php">Abrir dashboard <span>↗</span></a><a class="button" href="gestao.php?tipo=turmas">Gerenciar turmas</a><a class="button" href="gestao.php?tipo=cadetes">Gerenciar cadetes</a><a class="button" href="usuarios.php">Cadastrar acesso</a><a class="button" href="taf.php">Avaliações do TAF</a></div><form method="post"><?=csrf()?><button name="acao" value="sair">Encerrar sessão</button></form>
<?php else: ?><p>Acesso restrito à equipe de formação acadêmica.</p><form class="secretary-login" method="post"><?=csrf()?><label>E-mail<input type="email" name="email" required autocomplete="username" placeholder="secretaria@meridiano.test"></label><label>Senha<input type="password" name="senha" required autocomplete="current-password"></label><button>Entrar <span>→</span></button></form><p class="error"><?=h($error)?></p><p class="notice security-note"><strong>Recuperação protegida:</strong> não existe redefinição pública de senha. Se perder o acesso, peça a um administrador para cadastrar um novo acesso em Secretaria → Cadastrar acesso.</p><?php endif; ?></main><?php endPage(); ?>
