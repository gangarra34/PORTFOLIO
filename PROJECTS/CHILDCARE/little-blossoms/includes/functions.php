<?php
// Central session, authorisation, validation, and output helpers.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','samesite'=>'Lax']);
    session_start();
}
require_once __DIR__ . '/connect.php';
function e($value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function user(): ?array { return $_SESSION['user'] ?? null; }
function role(): string { return user()['role'] ?? 'public'; }
function require_role(string ...$roles): void {
    if (!in_array(role(), $roles, true)) {
        $_SESSION['flash'] = 'Please sign in with the correct account.';
        header('Location: /little-blossoms/login.php'); exit;
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '
<input type="hidden" name="csrf" value="'.e(csrf_token()).'">
'; }
function verify_csrf(): void {
if (!hash_equals(csrf_token(), (string)($_POST['csrf'] ?? ''))) {
http_response_code(403); exit('Invalid form token. Please reload and try again.');
}
}
function post(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function redirect(string $path, string $message = ''): void {
if ($message !== '') $_SESSION['flash'] = $message;
header('Location: '.$path); exit;
}
function flash(): string { $v=$_SESSION['flash'] ?? ''; unset($_SESSION['flash']); return $v; }
function valid_date(string $date): bool { $d=DateTime::createFromFormat('!Y-m-d',$date); return $d && $d->format('Y-m-d') === $date; }
function own_child(PDO $pdo, int $id): ?array {
if (role() === 'admin') { $s=$pdo->prepare('SELECT * FROM child WHERE id=?');$s->execute([$id]); }
else { $s=$pdo->prepare('SELECT * FROM child WHERE id=? AND parent_id=?');$s->execute([$id,user()['id'] ?? 0]); }
return $s->fetch() ?: null;
}
