<?php

$mysqli = new mysqli("localhost", "root", "", "kriptoke2");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
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

    // Ambil password terenkripsi dari database
    $query = "SELECT password FROM pertemuan2 WHERE id = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedEncryptedPassword = $row['password'];

        $key = "dylan";

        // Dekripsi password yang diambil dari database
        $storedPassword = vigenereDecrypt($storedEncryptedPassword, $key);

        // Periksa apakah password yang dimasukkan sesuai dengan password di database
        if ($password === $storedPassword) {
            echo "<script>alert('Login berhasil!'); window.location.href='loginSite.php';</script>";
        } else {
            echo "<script>alert('Login gagal. Password atau Username salah!'); window.location.href='loginSite.php';</script>";
        }
    } else {
        echo "<script>alert('Login gagal. Password atau Username salah!'); window.location.href='loginSite.php';</script>";
    }
}



$mysqli->close();
?>
