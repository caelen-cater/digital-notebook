<?php
function sanitizeInput($input) {
    return preg_replace("/[^A-Za-z0-9 ]/", '', $input);
}

function encryptData($data, $passwordHash) {
    $method = 'AES-256-CBC';
    $ivlen = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encryptedData = openssl_encrypt($data, $method, $passwordHash, 0, $iv);
    return base64_encode($iv . $encryptedData);
}

function userExists($email) {
    return file_exists("../../data/accounts/{$email}.php");
}

$firstName = sanitizeInput($_POST['firstName']);
$lastName = sanitizeInput($_POST['lastName']);
$email = sanitizeInput($_POST['email']);
$password = $_POST['password'];

if (userExists($email)) {
    echo "Email is already in use";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$data = [
    'decrypt' => true,
    'firstName' => $firstName,
    'lastName' => $lastName,
    'email' => $email,
    'password' => $password,
    'passwordHash' => $passwordHash
];
$data = json_encode($data, JSON_PRETTY_PRINT);

$encryptedData = encryptData($data, $passwordHash);

$fileName = "../../data/accounts/{$email}.php";
$fileContent = "<?php\nheader('Location: ../../app');\nexit;\n?>\n{$encryptedData}";
file_put_contents($fileName, $fileContent);
?>