<?php
session_start();

function sanitizeInput($input) {
    return preg_replace("/[^A-Za-z0-9 ]/", '', $input);
}

function decryptData($encryptedData, $passwordHash) {
    $method = 'AES-256-CBC';
    $ivlen = openssl_cipher_iv_length($method);
    $iv = substr(base64_decode($encryptedData), 0, $ivlen);
    $encryptedData = substr(base64_decode($encryptedData), $ivlen);
    return openssl_decrypt($encryptedData, $method, $passwordHash, 0, $iv);
}

function userFile($email) {
    return "../../data/accounts/{$email}.php";
}

$email = sanitizeInput($_POST['email']);
$password = $_POST['password'];

if (!file_exists(userFile($email))) {
    echo "Email not found";
    exit;
}

$userFileContent = file_get_contents(userFile($email));
$userFileContent = str_replace("<?php\nheader('Location: ../../app');\nexit;\n?>\n", '', $userFileContent);

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$decryptedData = decryptData($userFileContent, $passwordHash);

$data = json_decode($decryptedData, true);

if ($data['decrypt'] === true) {
    $_SESSION['hash'] = $passwordHash;
    $_SESSION['user'] = $email;
}
?>