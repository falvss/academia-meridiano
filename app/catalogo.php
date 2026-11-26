<?php
const MISSOES=['comum'=>'Formação comum','galileia'=>'Galileia e Judeia','brasil'=>'Brasil Colônia','guerra'=>'Segunda Guerra Mundial','futuro'=>'Curitiba em 2125'];
function entidade():string {
 $tipo=field($_GET,'tipo');
 if(!in_array($tipo,['turmas','cadetes'],true))fail('Cadastro desconhecido.',404);
 return $tipo;
}

const ESPECIALIDADES=['Corpo Básico', 'Travessia', 'Observação', 'Ciências Temporais', 'História e Linguagens', 'Saúde e Readaptação Temporal', 'Intendência Temporal'];
