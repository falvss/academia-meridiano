<?php
if(PHP_SAPI!=='cli') exit;
fwrite(STDERR,"Senha da secretaria (mínimo de 8 caracteres): ");
$senha=rtrim((string)fgets(STDIN),"\r\n");
if(strlen($senha)<8){fwrite(STDERR,"Senha curta.\n");exit(1);}
echo password_hash($senha,PASSWORD_DEFAULT),PHP_EOL;
