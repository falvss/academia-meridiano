<?php
require __DIR__.'/../app/bootstrap.php';
require __DIR__.'/../app/catalogo.php';
require __DIR__.'/../app/selecao.php';
$questions=require __DIR__.'/../app/prova.php';
$opcoes=MISSOES;unset($opcoes['comum']);
$interesse=field($_POST,'interesse')?:field($_GET,'interesse');
$error='';$nome=field($_POST,'nome');$email=field($_POST,'email');
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
 if(!goodText($nome,100)||strlen($email)>160||!filter_var($email,FILTER_VALIDATE_EMAIL))throw new DomainException('Confira o nome e o e-mail.');
 if(!isset($opcoes[$interesse]))throw new DomainException('Selecione uma missão de interesse.');
 $gerais=0;
 $especificos=0;
 $respostas=[];
 foreach ($questions as $i=>$question) {
     [$tema,$enunciado,$alternativas,$gabarito]=$question;
     $opcao=field($_POST,'q'.$i);
     if (!ctype_digit($opcao) || !isset($alternativas[(int)$opcao])) {
         throw new DomainException('Responda às oito questões.');
     }
     $acertou=(int)$opcao===$gabarito;
     $bloco=$i<4?'gerais':'especificos';
     if ($acertou && $bloco==='gerais') $gerais++;
     if ($acertou && $bloco==='especificos') $especificos++;
     $respostas[]=['item'=>$i+1,'bloco'=>$bloco,'tema'=>$tema,'enunciado'=>$enunciado,
                   'resposta'=>$alternativas[(int)$opcao],'gabarito'=>$alternativas[$gabarito],'acertou'=>$acertou];
 }
 query('INSERT INTO inscricoes(nome,email,interesse,edicao,gerais,especificos,nota,respostas) VALUES(:nome,:email,:interesse,2,:gerais,:especificos,:nota,:respostas)',
       ['nome'=>$nome,'email'=>$email,'nota'=>$gerais+$especificos,'gerais'=>$gerais,'especificos'=>$especificos,
        'interesse'=>$interesse,'respostas'=>json_encode($respostas,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);
 $_SESSION['vestibular']=['id'=>db()->lastInsertId()];go('vestibular.php?resultado=1');
 }catch(DomainException $e){$error=$e->getMessage();}catch(PDOException $e){if($e->getCode()!=='23000')throw $e;$error='Já existe uma inscrição para este e-mail.';}
}
startPage('Vestibular 2027');?>
<main class="page"><p class="kicker">PROCESSO SELETIVO · 2027</p><h1>Seleção de agentes</h1><a class="under" download href="edital.pdf">Baixar edital (PDF) ↓</a>
<?php if(isset($_GET['resultado'],$_SESSION['vestibular'])):?><?php $rec=query('SELECT * FROM inscricoes WHERE id=:id',['id'=>(int)$_SESSION['vestibular']['id']])->fetch();if(!$rec)fail('Inscrição não encontrada.',404);?><section class="card"><h2>Comprovante nº <?=h($rec['id'])?></h2><p><?=h($rec['nome'])?> · <?=h(resultadoSelecao($rec))?></p><?php if((int)$rec['edicao']===2):?><p>Gerais: <?=h($rec['gerais'])?>/4 · Específicos: <?=h($rec['especificos'])?>/4 · Total: <?=h($rec['nota'])?>/8.</p><?php if($rec['data_taf']):?><p>TAF em <?=h($rec['data_taf'])?> · Corrida: <?=h($rec['corrida_m'])?> m · Percurso: <?=h($rec['percurso_s'])?> s.</p><?php endif;endif;?><p>Guarde o protocolo. A secretaria informa a convocação individual e atende consultas posteriores.</p></section>
<?php else:?><p>Escolha sua área de interesse e responda às oito questões. São quatro itens gerais e quatro específicos. Com cinco acertos no total e pelo menos dois em cada bloco, você segue para o TAF presencial.</p><?php if(isset($_SESSION['vestibular'])):?><a href="?resultado=1">Consultar meu comprovante</a><?php endif;?><p class="error"><?=h($error)?></p><form class="admission" method="post"><?=csrf()?><section class="candidate"><h2>Ficha de inscrição</h2><div class="candidate-fields"><label>Nome completo<input name="nome" required maxlength="100" value="<?=h($nome)?>"></label><label>E-mail<input type="email" required maxlength="160" name="email" value="<?=h($email)?>"></label><label>Missão de interesse<select required name="interesse"><option value="">Selecione</option><?php foreach($opcoes as $v=>$l):?><option value="<?=$v?>" <?=$interesse===$v?'selected':''?>><?=$l?></option><?php endforeach;?></select></label></div></section><div class="exam-top"><h2>Prova de ingresso</h2><output id="exam-count" aria-live="polite"></output></div><?php foreach($questions as $i=>$question):?><fieldset><legend><small>Questão <?=$i+1?> de 8 · <?=h($question[0])?></small><?=h($question[1])?></legend><?php foreach($question[2] as $j=>$answer):?><label><input type="radio" required name="q<?=$i?>" value="<?=$j?>" <?=field($_POST,'q'.$i)===(string)$j?'checked':''?>> <?=h($answer)?></label><?php endforeach;?></fieldset><?php endforeach;?><button>Concluir inscrição e enviar prova</button></form><?php endif;?></main><?php endPage();?>
