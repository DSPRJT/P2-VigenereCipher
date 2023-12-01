<?php
$mysqli = new mysqli("localhost", "root", "", "kriptoke2");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function vigenereEncrypt($text, $key)
{
    $result = "";
    $text = strtoupper($text);
    $key = strtoupper($key);
    $keyLength = strlen($key);

    for ($i = 0, $j = 0; $i < strlen($text); $i++) {
        $char = $text[$i];

        if (ctype_upper($char)) {
            $result .= strtolower(chr((ord($char) + ord($key[$j % $keyLength]) - 2 * ord('A')) % 26 + ord('A')));
            $j++;
        } elseif (ctype_lower($char)) {
            $result .= strtolower(chr((ord($char) + ord($key[$j % $keyLength]) - 2 * ord('A')) % 26 + ord('a')));
            $j++;
        } else {
            $result .= $char;
        }
    }

    return $result;
}


function vigenereDecrypt($text, $key)
{
    $result = "";
    $text = strtoupper($text);
    $key = strtoupper($key);
    $keyLength = strlen($key);

    for ($i = 0, $j = 0; $i < strlen($text); $i++) {
        $char = $text[$i];

        if (ctype_upper($char)) {
            $result .= strtolower(chr((ord($char) - ord($key[$j % $keyLength]) + 26) % 26 + ord('A')));
            $j++;
        } elseif (ctype_lower($char)) {
            $result .= strtolower(chr((ord($char) - ord($key[$j % $keyLength]) + 26 - 2 * ord('A')) % 26 + ord('a')));
            $j++;
        } else {
            $result .= $char;
        }
    }

    return $result;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $key = "dylan";
    $encryptedPassword = vigenereEncrypt($password, $key);
    $query = "INSERT INTO pertemuan2 (id, password) VALUES ('$username', '$encryptedPassword')";
    if ($mysqli->query($query) === TRUE) {
        echo "<script>alert('Registrasi berhasil. Silakan login'); window.location.href='loginSite.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal. Silakan Coba lagi.'); window.location.href='registerSite.php';</script>";
    }
}

$mysqli->close();
?>


