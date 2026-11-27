<?php
require __DIR__.'/../app/bootstrap.php';require __DIR__.'/../app/catalogo.php';staff();
$tipo=entidade();$isTurma=$tipo==='turmas';$id=(int)field($_GET,'id');
$r=$id?query("SELECT * FROM $tipo WHERE id=:id",['id'=>$id])->fetch():['nome'=>'','ano'=>'2027','missao'=>'comum','email'=>'','turma_id'=>'','ano_formacao'=>1,'especialidade'=>'Corpo Básico'];
if(!$r)fail('Registro não encontrado.',404);
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
 if(field($_POST,'acao')==='excluir'){
 if(!$id||field($_POST,'confirmar')!=='sim')throw new DomainException('Confirme a exclusão do registro.');
 query("DELETE FROM $tipo WHERE id=:id",['id'=>$id]);
 }else{
 $p=['nome'=>field($_POST,'nome')];
 foreach($isTurma?['missao','ano']:['email','turma_id','ano_formacao','especialidade'] as $k)$p[$k]=field($_POST,$k);
 $r=array_merge($r,$p);
 if(!goodText($p['nome'],100))throw new DomainException('Informe um nome com até 100 caracteres.');
 if($isTurma){
 if(!isset(MISSOES[$p['missao']])||!ctype_digit($p['ano'])||(int)$p['ano']<1900||(int)$p['ano']>9999)throw new DomainException('Informe uma missão válida e um ano entre 1900 e 9999.');
 $sql=$id?'UPDATE turmas SET nome=:nome, missao=:missao, ano=:ano WHERE id=:id':'INSERT INTO turmas(nome,missao,ano) VALUES(:nome,:missao,:ano)';
 }else{
 if(!in_array($p['ano_formacao'],['1','2','3','4'],true) || !in_array($p['especialidade'],ESPECIALIDADES,true) || (($p['ano_formacao']==='1') !== ($p['especialidade']==='Corpo Básico')))throw new DomainException('No primeiro ano, selecione Corpo Básico. Nos anos seguintes, selecione uma especialidade.');
 if(!filter_var($p['email'],FILTER_VALIDATE_EMAIL)||strlen($p['email'])>160)throw new DomainException('Informe um e-mail válido.');
 if(!ctype_digit($p['turma_id'])||!query('SELECT id FROM turmas WHERE id=:id',['id'=>(int)$p['turma_id']])->fetchColumn())throw new DomainException('Selecione uma turma existente.');
 $sql=$id?'UPDATE cadetes SET nome=:nome,email=:email,ano_formacao=:ano_formacao,especialidade=:especialidade,turma_id=:turma_id WHERE id=:id':'INSERT INTO cadetes(nome,email,turma_id,ano_formacao,especialidade) VALUES(:nome,:email,:turma_id,:ano_formacao,:especialidade)';
 }
 if($id)$p['id']=$id;query($sql,$p);
 }
 go('gestao.php?tipo='.$tipo);
 }catch(DomainException $e){$error=$e->getMessage();}catch(PDOException $e){if($e->getCode()!=='23000')throw $e;$error='Conflito com outro registro. Confira duplicidade de nome de turma, e-mail ou vínculo de cadetes.';}
}
startPage('Ficha acadêmica');?>
<main class="page"><h1><?=$id?'Editar':'Cadastrar'?> <?=$isTurma?'turma':'cadete'?></h1><p class="error"><?=h($error)?></p><form method="post"><?=csrf()?><label>Nome<input name="nome" maxlength="100" required value="<?=h($r['nome'])?>"></label><?php if($isTurma):?><label>Missão<select name="missao"><?php foreach(MISSOES as $k=>$v):?><option value="<?=$k?>" <?=$r['missao']===$k?'selected':''?>><?=$v?></option><?php endforeach;?></select></label><label>Ano letivo<input type="number" min="1900" max="9999" name="ano" required value="<?=h($r['ano'])?>"></label><?php else:?><label>E-mail<input type="email" maxlength="160" required name="email" value="<?=h($r['email'])?>"></label><label>Ano de formação<select name="ano_formacao" required><option value="">Selecione</option><?php foreach([1,2,3,4] as $n):?><option value="<?=$n?>" <?=(int)$r['ano_formacao']===$n?'selected':''?>><?=$n?>º ano</option><?php endforeach;?></select></label><label>Especialidade<select name="especialidade" required><option value="">Selecione</option><?php foreach(ESPECIALIDADES as $nome):?><option <?=$r['especialidade']===$nome?'selected':''?>><?=h($nome)?></option><?php endforeach;?></select></label><label>Turma<select required name="turma_id"><option value="">Selecione</option><?php foreach(query('SELECT id,nome FROM turmas ORDER BY nome') as $t):?><option value="<?=$t['id']?>" <?=(int)$r['turma_id']===(int)$t['id']?'selected':''?>><?=h($t['nome'])?></option><?php endforeach;?></select></label><?php endif;?><button>Salvar registro</button></form>
<?php if($id):?><form method="post"><?=csrf()?><label><input type="checkbox" style="display:inline;width:auto" required name="confirmar" value="sim"> Confirmo a exclusão de <?=h($r['nome'])?></label><button name="acao" value="excluir">Excluir</button></form><?php endif;?></main><?php endPage();?>
