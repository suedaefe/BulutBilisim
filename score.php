<?php

$connect = mysqli_connect(
    'db',       // service name
    'php_docker', // username
    'password', // password
    'php_docker' // db table
);


if ($connect->connect_errno) {
    die("Baglanti hatasi: " . $connect->connect_error);
}

$d = json_decode(file_get_contents("php://input"), true);
$uid = $d['uid'];
$skor = $d['skor'];

// Kullanıcının ID'sine ait skoru kontrol et
$Scoresql = "SELECT * FROM game WHERE user_id = '$uid'";
$result = $connect->query($Scoresql);

if ($result->num_rows > 0) {
    // Kullanıcının daha önce skoru var, yeni skor eski skordan büyük mü kontrol et
    $row = $result->fetch_assoc();
    $Scoreo = $row['highest_score'];

    if ($skor > $Scoreo) {
        // Yeni skor eski skordan büyük, güncelleme işlemi yap
        $ScoreUpdatesql = "UPDATE game SET highest_score = '$skor' WHERE user_id = '$uid'";
        if ($connect->query($ScoreUpdatesql) === TRUE) {
            echo "Skor guncellendi";
        } else {
            echo "Hata: " . $connect->error;
        }
    } else {
        echo "Yeni skor eski skordan kucuk veya esit";
    }
} else {
    // Kullanıcının daha önce skoru yok, yeni skoru ekle
    $ScoreInsertsql = "INSERT INTO game (`user_id`, `highest_score`) VALUES ('$uid', '$skor')";
    if ($connect->query($ScoreInsertsql) === TRUE) {
        echo "Skor eklendi";
    } else {
        echo "Hata: " . $connect->error;
    }
}

$connect->close();

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
