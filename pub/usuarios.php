<?php
require __DIR__.'/../app/bootstrap.php';
staff();
$error='';$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nome=field($_POST,'nome');$email=strtolower(field($_POST,'email'));$senha=field($_POST,'senha');$confirmacao=field($_POST,'confirmacao');
 if(!goodText($nome,100))$error='Informe um nome válido com até 100 caracteres.';
 elseif(!filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($email)>160)$error='Informe um e-mail válido.';
 elseif(strlen($senha)<8)$error='A senha precisa ter pelo menos 8 caracteres.';
 elseif($senha!==$confirmacao)$error='A confirmação da senha não confere.';
 else{
  try{query('INSERT INTO admin_users(nome,email,senha_hash,ativo) VALUES(:nome,:email,:hash,1)',['nome'=>$nome,'email'=>$email,'hash'=>password_hash($senha,PASSWORD_DEFAULT)]);$ok='Acesso cadastrado com sucesso.';$nome=$email=$senha=$confirmacao='';}
  catch(PDOException $e){$error=((int)$e->errorInfo[1]===1062)?'Este e-mail já possui um acesso cadastrado.':'Não foi possível cadastrar o acesso.';}
 }
}
$users=query('SELECT id,nome,email,ativo,criado FROM admin_users ORDER BY nome')->fetchAll();
startPage('Acessos da Secretaria'); ?>
<main id="conteudo" class="page users-page"><p class="kicker">SECRETARIA · CONTROLE DE ACESSOS</p><h1>Cadastrar acesso</h1><p class="lead">Somente uma pessoa já autenticada pode criar uma conta administrativa.</p><div class="user-admin-grid"><form class="card access-form" method="post"><?=csrf()?><h2>Novo usuário</h2><label>Nome<input name="nome" value="<?=h($nome ?? '')?>" maxlength="100" required></label><label>E-mail<input type="email" name="email" value="<?=h($email ?? '')?>" maxlength="160" autocomplete="off" required></label><label>Senha<input type="password" name="senha" minlength="8" autocomplete="new-password" required></label><label>Confirmar senha<input type="password" name="confirmacao" minlength="8" autocomplete="new-password" required></label><button>Cadastrar acesso <span>→</span></button><?php if($error):?><p class="error"><?=h($error)?></p><?php endif;?><?php if($ok):?><p class="success"><?=h($ok)?></p><?php endif;?></form><section class="card access-list"><div class="panel-heading"><div><p class="kicker">CONTAS ATIVAS</p><h2>Equipe autorizada</h2></div><a class="under" href="secretaria.php">Voltar ↗</a></div><?php foreach($users as $item):?><div class="access-row"><span class="access-avatar"><?=h(mb_strtoupper(mb_substr($item['nome'],0,1,'UTF-8'),'UTF-8'))?></span><span><strong><?=h($item['nome'])?></strong><small><?=h($item['email'])?></small></span><i>Ativo</i></div><?php endforeach;?></section></div><p class="notice security-note"><strong>Importante:</strong> não há botão público de “esqueci a senha”. Isso evita que qualquer pessoa consiga iniciar uma recuperação. Um administrador autenticado deve criar um novo acesso.</p></main><?php endPage(); ?>
