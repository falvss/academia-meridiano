<?php
require __DIR__.'/../app/bootstrap.php';
require __DIR__.'/../app/selecao.php';
staff();
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=(int)field($_POST,'id');
    $corrida=filter_var(field($_POST,'corrida'),FILTER_VALIDATE_INT,['options'=>['min_range'=>0,'max_range'=>10000]]);
    $tempo=filter_var(field($_POST,'tempo'),FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>3600]]);
    $avaliador=field($_POST,'avaliador');
    $data=field($_POST,'data');
    $dia=DateTimeImmutable::createFromFormat('!Y-m-d',$data);
    if ($corrida===false || $tempo===false || !goodText($avaliador,100) || !$dia || $dia->format('Y-m-d')!==$data || $data>date('Y-m-d')) {
        $error='Confira as duas marcas, o nome do avaliador e a data da avaliação.';
    } else {
        $salvo=query('UPDATE inscricoes SET corrida_m=:m,percurso_s=:s,data_taf=:d,avaliador=:a
          WHERE id=:id AND edicao=2 AND gerais>=2 AND especificos>=2 AND nota>=5 AND data_taf IS NULL',
          ['m'=>$corrida,'s'=>$tempo,'d'=>$data,'a'=>$avaliador,'id'=>$id])->rowCount();
        if ($salvo) go('taf.php?protocolo='.$id);
        $error='Esta inscrição já foi avaliada ou não está convocada.';
    }
}
$protocolo=field($_GET,'protocolo');$args=[];
$where=' WHERE edicao=2 AND gerais>=2 AND especificos>=2 AND nota>=5';
if ($protocolo!=='') {$where.=' AND id=:id';$args['id']=(int)$protocolo;}
$total=(int)query('SELECT COUNT(*) FROM inscricoes'.$where,$args)->fetchColumn();
$pages=max(1,(int)ceil($total/8));$page=min($pages,max(1,(int)field($_GET,'p')));
$rows=query('SELECT * FROM inscricoes'.$where.' ORDER BY data_taf IS NULL DESC,id LIMIT :n OFFSET :off',$args+['n'=>8,'off'=>8*($page-1)]);
startPage('Avaliações do TAF');?>
<main class="page"><p class="kicker">SECRETARIA · BANCA EXAMINADORA</p><h1>Teste de Aptidão Física</h1><form><label>Número de protocolo<input type="number" min="1" name="protocolo" value="<?=h($protocolo)?>"></label><button>Consultar</button></form><p class="error"><?=h($error)?></p><p><?=$total?> convocado(s) localizado(s).</p>
<?php foreach($rows as $r):?><article class="card"><h2><?=h($r['nome'])?></h2><p>Protocolo <?=h($r['id'])?> · <?=h(resultadoSelecao($r))?></p>
<?php if(!$r['data_taf']):?><form method="post"><?=csrf()?><input type="hidden" name="id" value="<?=(int)$r['id']?>"><label>Distância em 8 minutos (m)<input required type="number" name="corrida" min="0" max="10000"></label><label>Tempo do percurso de 4 × 10 m (s inteiros)<input required type="number" min="1" max="3600" name="tempo"></label><label>Data do teste<input required type="date" name="data" max="<?=date('Y-m-d')?>"></label><label>Nome do avaliador<input maxlength="100" required name="avaliador"></label><button>Confirmar marcas e encerrar avaliação</button></form>
<?php else:?><p><?=h($r['corrida_m'])?> m · <?=h($r['percurso_s'])?> s · <?=h($r['data_taf'])?> · <?=h($r['avaliador'])?></p><?php endif;?></article><?php endforeach;?><nav class="pager"><?php if($page>1):?><a href="?<?=h(http_build_query(['p'=>$page-1,'protocolo'=>$protocolo]))?>">Anterior</a><?php endif;?>Página <?=$page?> de <?=$pages?><?php if($page<$pages):?><a href="?<?=h(http_build_query(['p'=>$page+1,'protocolo'=>$protocolo]))?>">Próxima</a><?php endif;?></nav></main><?php endPage();?>
