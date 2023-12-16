<?php


$connect = mysqli_connect(
    'db',       // service name
    'php_docker', // username
    'password', // password
    'php_docker' // db table
);

$table_name = "game";

$query = "SELECT user2.id, user2.name, game.highest_score
FROM user2
JOIN game ON user2.id = game.user_id
ORDER BY game.highest_score DESC";

$response = mysqli_query($connect, $query);

// Verileri döngü ile alıp HTML sayfasına ekleyelim
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>


.user-info {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 1000;
    color: white;
    background-color: #19561B; /* Arka plan rengi */
    padding: 10px;
    border-radius: 10px; /* Yuvarlatılmış köşeler */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Gölge */
    border: 2px solid #fff; /* Kenarlık */
    transition: background-color 0.3s ease; /* Renk değişimine geçiş */
}



        .user-info a {
            color: #A1D648;
        }


        .wrapper {
            position: relative;
        }

        a{
         
         text-decoration: none;
     
             }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #142F10;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Ekranın üst kısmına yakın hizalamak için */
        min-height: 100vh;
    }

    .container {
        background-color: #d7e2d6;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 50%; /* Sayfanın genişliğini özelleştirebilirsiniz */
        margin-top: 20px; /* Tablo ile üst kısım arasında boşluk bırakmak için */
    }

    h1 {
        text-align: center;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px; /* Tablonun üst kısmında daha az boşluk bırakmak için */
    }

    th, td {
        border-bottom: 1px solid #ddd; /* Sadece alt çizgileri ekleyin */
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
        border-bottom: none; /* Başlık hücrelerinde alt çizgi olmasın */
    }

    .rank {
        display: inline-block;
        width: 20px;
        height: 20px;
        background-color: #4CAF50;
        border-radius: 50%;
        color: #fff;
        text-align: center;
        line-height: 20px;
        font-weight: bold;
    }
</style>



    <title><?php echo ucfirst($table_name); ?> Table</title>
</head>
<body>

<div class="user-info">
    <p><a href="oyun.php">Oyuna dön</a></p>
            <p><a href="logout.php">Çıkış Yap</a></p>
    </div>

    <div class="container">
        <h1>Lider Tablosu</h1>

        <table>
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>ID</th>
                    <th>İsim</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                while ($row = mysqli_fetch_assoc($response)) : 
                ?>
                    <tr>
                        <td><div class="rank"><?php echo $rank; ?></div></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['highest_score']; ?></td>
                    </tr>
                <?php 
                    $rank++;
                endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>




