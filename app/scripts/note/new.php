<?php
function sanitizeNoteName($noteName) {
    return preg_replace("/[^A-Za-z0-9 ]/", '', $noteName);
}

function encryptNote($noteContent, $passwordHash) {
    $method = 'AES-256-CBC';

    $ivlen = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($ivlen);

    $encryptedNote = openssl_encrypt($noteContent, $method, $passwordHash, 0, $iv);

    return base64_encode($iv . $encryptedNote);
}

$noteName = $_POST['noteName'];
$noteContent = $_POST['noteContent'];

$noteName = sanitizeNoteName($noteName);

$passwordHash = $_COOKIE['uph'];

$userId = $_COOKIE['uid'];

$encryptedNote = encryptNote($noteContent, $passwordHash);

$fileName = "user/{$userId}/notes/r1/{$noteName}.php";
$fileContent = "<?php\nheader('Location: ../app');\nexit;\n?>\n{$encryptedNote}";
file_put_contents($fileName, $fileContent);
?>