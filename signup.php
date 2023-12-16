<?php

if (empty($_POST["name"])) {
    die("isim girmek zorunludur");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("gecerli email girin");
}

if (strlen($_POST["password"]) < 8) {
    die("sifre en az 8 karakter icermek zorunda");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("sifre en az bir harf icermek zorunda");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("sifre en az bir sayi icermek zorunda");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("sifreler eslesmeli");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$connect = mysqli_connect(
    'db',       // service name
    'php_docker', // username
    'password', // password
    'php_docker' // db table
);

$sql = "INSERT INTO user2 (name, email, password_hash)
        VALUES (?, ?, ?)";
        
$stmt = $connect->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);
                  
if ($stmt->execute()) {

    header("Location: nowlogin.html");
    exit;
    
} else {
    
    if ($connect->errno === 1062) {
        die("email kullanimda");
    } else {
        die($connect->error . " " . $mysqli->errno);
    }
}