<?php
declare(strict_types=1);
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
session_start();
$config = is_file(__DIR__.'/config.php') ? require __DIR__.'/config.php' : require __DIR__.'/config.example.php';
function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE,'UTF-8'); }
function field($source,$name): string { return is_string($source[$name] ?? null) ? trim($source[$name]) : ''; }
function db(): PDO {
    global $config;
    static $pdo;
    return $pdo ??= new PDO($config['dsn'],$config['user'],$config['password'],[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES=>false]);
}
function query($sql,$params=[]) {
    $stmt=db()->prepare($sql);
    foreach ($params as $key=>$value) {
        $stmt->bindValue(':'.$key,$value,is_int($value)?PDO::PARAM_INT:PDO::PARAM_STR);
    }
    $stmt->execute(); return $stmt;
}
function token(): string { return $_SESSION['token'] ??= bin2hex(random_bytes(32)); }
function csrf(): string { return '<input type="hidden" name="token" value="'.h(token()).'">'; }
function go($url): never { header('Location: '.$url,true,303); exit; }
function fail($message,$status=400): never { http_response_code($status); require __DIR__.'/header.php'; echo '<main class="page"><h1>Atendimento</h1><p>'.h($message).'</p><a href="index.php">Voltar ao início</a></main>'; require __DIR__.'/footer.php'; exit; }
if ($_SERVER['REQUEST_METHOD']==='POST' && !hash_equals(token(),field($_POST,'token'))) fail('Sua sessão expirou. Reabra a página e tente novamente.',403);
set_exception_handler(function(Throwable $error) { error_log($error->getMessage()); fail('Serviço indisponível. Confira a conexão e a importação do banco conforme o README.',503); });
function admin(): bool { return !empty($_SESSION['secretaria_aberta']); }
function staff(): void { if (!admin()) go('secretaria.php'); }
function goodText($s,$max): bool { return $s!=='' && preg_match('//u',$s)===1 && preg_match_all('/./us',$s)<=$max; }
function startPage($title): void { require __DIR__.'/header.php'; }
function endPage(): void { require __DIR__.'/footer.php'; }
