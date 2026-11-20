<?php
function convocado(int $gerais,int $especificos): bool {
    return $gerais >= 2 && $especificos >= 2 && $gerais + $especificos >= 5;
}
function resultadoSelecao(array $r): string {
    if ((int)$r['edicao']===1) return (int)$r['nota']>=4?'Habilitado pelo regulamento anterior':'Nota insuficiente pelo regulamento anterior';
    if (!convocado((int)$r['gerais'],(int)$r['especificos'])) return 'Não habilitado na prova escrita';
    if ($r['corrida_m']===null || $r['percurso_s']===null) return 'Convocado para o TAF';
    return (int)$r['corrida_m']>=800 && (int)$r['percurso_s']<=40
        ? 'Habilitado para matrícula' : 'Não habilitado no TAF';
}
