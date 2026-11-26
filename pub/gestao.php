<?php
require __DIR__.'/../app/bootstrap.php';require __DIR__.'/../app/catalogo.php';staff();$tipo=entidade();
$q=field($_GET,'q');$missao=field($_GET,'missao');$ordem=field($_GET,'ordem');
$ordens=['az'=>'r.nome ASC','za'=>'r.nome DESC','recentes'=>'r.id DESC'];if(!isset($ordens[$ordem]))$ordem='az';
$isTurma=$tipo==='turmas';
$from=$isTurma?' FROM turmas r':' FROM cadetes r JOIN turmas t ON t.id=r.turma_id';
$col=$isTurma?'r.missao':'t.missao';$params=['q'=>'%'.$q.'%'];$where=' WHERE r.nome LIKE :q';
if(isset(MISSOES[$missao])){$where.=" AND $col=:missao";$params['missao']=$missao;}
$total=(int)query('SELECT COUNT(*)'.$from.$where,$params)->fetchColumn();
$pages=max(1,(int)ceil($total/6));$page=min($pages,max(1,(int)field($_GET,'p')));
$select=$isTurma?'r.*, r.nome AS turma':'r.*,t.nome AS turma,t.missao';
$rows=query("SELECT $select".$from.$where.' ORDER BY '.$ordens[$ordem].',r.id LIMIT :lim OFFSET :off',$params+['lim'=>6,'off'=>6*($page-1)]);
function pageLink($p){global $tipo,$missao,$ordem,$q;return '?'.http_build_query(compact('p','tipo','missao','ordem','q'));}
startPage(ucfirst($tipo));?>
<main class="page"><p class="kicker">SECRETARIA ACADÊMICA</p><h1><?=ucfirst($tipo)?></h1><a class="button" href="registro.php?tipo=<?=$tipo?>"><?=$isTurma?'Criar turma':'Cadastrar cadete'?></a>
<form class="filters"><input type="hidden" name="tipo" value="<?=$tipo?>"><label>Pesquisar nome<input name="q" value="<?=h($q)?>"></label><label>Missão<select name="missao"><option value="">Todas</option><?php foreach(MISSOES as $v=>$l):?><option value="<?=$v?>" <?=$v===$missao?'selected':''?>><?=$l?></option><?php endforeach;?></select></label><label>Ordem<select name="ordem"><?php foreach(['az'=>'A–Z','za'=>'Z–A','recentes'=>'Recentes'] as $v=>$l):?><option value="<?=$v?>" <?=$v===$ordem?'selected':''?>><?=$l?></option><?php endforeach;?></select></label><button>Aplicar</button></form>
<p><?=$total?> registro(s)</p><div class="grid"><?php foreach($rows as $r):?><article class="card"><h2><?=h($r['nome'])?></h2><p><?=h(MISSOES[$r['missao']])?> · <?=h($isTurma?$r['ano']:$r['turma'])?></p><?php if(!$isTurma):?><p><?=h($r['ano_formacao'] ? $r['ano_formacao'].'º ano' : 'Ano a confirmar')?> · <?=h($r['especialidade'] ?? 'Especialidade a confirmar')?></p><p><?=h($r['email'])?></p><?php endif;?><a href="registro.php?tipo=<?=$tipo?>&amp;id=<?=(int)$r['id']?>">Editar ou excluir →</a></article><?php endforeach;?></div><nav class="pager"><?php if($page>1):?><a href="<?=h(pageLink($page-1))?>">Anterior</a><?php endif;?>Página <?=$page?> de <?=$pages?><?php if($page<$pages):?><a href="<?=h(pageLink($page+1))?>">Próxima</a><?php endif;?></nav></main><?php endPage(); ?>
