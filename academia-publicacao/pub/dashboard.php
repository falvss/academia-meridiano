<?php
require __DIR__.'/../app/bootstrap.php';
require __DIR__.'/../app/catalogo.php';
staff();
$totalCadetes=(int)query('SELECT COUNT(*) FROM cadetes')->fetchColumn();
$totalTurmas=(int)query('SELECT COUNT(*) FROM turmas')->fetchColumn();
$totalInscricoes=(int)query('SELECT COUNT(*) FROM inscricoes')->fetchColumn();
$mediaAno=(float)(query('SELECT COALESCE(AVG(ano_formacao),0) FROM cadetes')->fetchColumn() ?: 0);
$missionRows=query('SELECT t.missao, COUNT(c.id) AS total FROM turmas t LEFT JOIN cadetes c ON c.turma_id=t.id GROUP BY t.missao ORDER BY total DESC, t.missao ASC')->fetchAll();
$yearRows=query('SELECT COALESCE(ano_formacao,0) AS ano, COUNT(*) AS total FROM cadetes GROUP BY ano_formacao ORDER BY ano')->fetchAll();
$recent=query('SELECT c.nome,c.email,c.ano_formacao,c.especialidade,t.nome AS turma,t.missao FROM cadetes c JOIN turmas t ON t.id=c.turma_id ORDER BY c.id DESC LIMIT 8')->fetchAll();
$maxMission=max(1,...array_map(fn($row)=>(int)$row['total'],$missionRows));
$maxYear=max(1,...array_map(fn($row)=>(int)$row['total'],$yearRows));
startPage('Dashboard'); ?>
<main id="conteudo" class="page dashboard-page">
  <div class="dashboard-heading"><div><p class="kicker">SECRETARIA ACADÊMICA · VISÃO GERAL</p><h1>Dashboard de cadetes</h1><p class="lead">Um painel rápido para acompanhar quem está atravessando os circuitos da Academia.</p></div><div class="dashboard-actions"><a class="under" href="secretaria.php">← Secretaria</a><a class="button" href="gestao.php?tipo=cadetes">Gerenciar cadetes <span>→</span></a></div></div>
  <section class="metric-grid" aria-label="Indicadores gerais">
    <article class="metric-card"><span class="metric-label">Cadetes ativos</span><strong><?=number_format($totalCadetes,0,',','.')?></strong><small>registros no sistema</small></article>
    <article class="metric-card"><span class="metric-label">Turmas</span><strong><?=number_format($totalTurmas,0,',','.')?></strong><small>destinos cadastrados</small></article>
    <article class="metric-card"><span class="metric-label">Inscrições</span><strong><?=number_format($totalInscricoes,0,',','.')?></strong><small>processos seletivos</small></article>
    <article class="metric-card"><span class="metric-label">Ano médio</span><strong><?=number_format($mediaAno,1,',','.')?>º</strong><small>formação dos cadetes</small></article>
  </section>
  <section class="dashboard-grid">
    <article class="dashboard-panel"><div class="panel-heading"><div><p class="kicker">DISTRIBUIÇÃO</p><h2>Cadetes por missão</h2></div><span class="panel-badge">LIVE</span></div><div class="bar-list"><?php foreach($missionRows as $row): $value=(int)$row['total'];?><div class="bar-row"><div><span><?=h(MISSOES[$row['missao']] ?? $row['missao'])?></span><strong><?=$value?></strong></div><div class="bar-track"><i style="width:<?=round(($value/$maxMission)*100)?>%"></i></div></div><?php endforeach;?></div></article>
    <article class="dashboard-panel"><div class="panel-heading"><div><p class="kicker">PROGRESSÃO</p><h2>Etapa de formação</h2></div><span class="panel-badge"><?=number_format($totalCadetes,0,',','.')?> TOTAL</span></div><div class="year-chart"><?php foreach($yearRows as $row): $year=(int)$row['ano']; $value=(int)$row['total'];?><div class="year-column"><strong><?=$value?></strong><i style="height:<?=max(10,round(($value/$maxYear)*150))?>px"></i><span><?=$year ? $year.'º ano' : 'A confirmar'?></span></div><?php endforeach;?></div></article>
  </section>
  <section class="dashboard-panel recent-panel"><div class="panel-heading"><div><p class="kicker">ÚLTIMAS ENTRADAS</p><h2>Cadetes cadastrados</h2></div><a class="under" href="gestao.php?tipo=cadetes">Ver todos ↗</a></div><div class="recent-table"><div class="recent-row recent-head"><span>Cadete</span><span>Turma</span><span>Etapa</span><span>Especialidade</span></div><?php foreach($recent as $row):?><div class="recent-row"><span><strong><?=h($row['nome'])?></strong><small><?=h($row['email'])?></small></span><span><?=h($row['turma'])?></span><span><?=$row['ano_formacao'] ? h($row['ano_formacao'].'º ano') : 'A confirmar'?></span><span><?=h($row['especialidade'] ?: 'A confirmar')?></span></div><?php endforeach;?></div></section>
</main><?php endPage(); ?>
