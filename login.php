<?php
$connect = mysqli_connect(
    'db',       // service name
    'php_docker', // username
    'password', // password
    'php_docker' // db table
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = $connect->real_escape_string($_POST["email"]);
    
    $query = "SELECT * FROM user2 WHERE email = '$email'";
    $result = mysqli_query($connect, $query);
    
    if ($result) {
        $user2 = $result->fetch_assoc();
        
        if ($user2 && password_verify($_POST["password"], $user2["password_hash"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user2["id"];
            
            header("Location: oyun.php");
            exit;
        }
    }
    
    $is_invalid = true;
}

?>


<!DOCTYPE html>
<html>
<head>
   
<title>Giris Yap - Kayıt Ol</title>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <script src="main.js" defer type="module"></script>


    <style>
        body {
            background-image: url('snake-game.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-color: #f0f0f0;
            background-position: center center;
            margin: 0;
        }

    </style>

    

</head>
<body>
<section class="wrapper">
        <div class="form signup">
            <header>Kayıt Ol</header>
            <form action="signup.php" method="post" id="signup" novalidate>
                <input type="text" id="name" name="name" placeholder="İsim">
                <input type="email" id="email" name="email" placeholder="Email">
                <input type="password" id="password" name="password" placeholder="Şifre">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Şifre Tekrar">

                <input type="submit" id="signup" value="Kayıt Ol" />
            </form>
        </div>

        <?php if ($is_invalid): ?>
        <em>Giris basarisiz</em>
    <?php endif; ?>

        <div class="form login">
            <header>Giriş Yap</header>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" id="email"
                       value="<?= htmlspecialchars($_POST[" email"] ?? "" ) ?>">

                <input type="password" name="password" placeholder="Şifre" id="password">
                <input type="submit" id="login" value="Giriş Yap" />
            </form>
        </div>
        <script>
            const wrapper = document.querySelector(".wrapper"),
                signupHeader = document.querySelector(".signup header"),
                loginHeader = document.querySelector(".login header");
            loginHeader.addEventListener("click", () => {
                wrapper.classList.add("active");
            });
            signupHeader.addEventListener("click", () => {
                wrapper.classList.remove("active");
            });
        </script>
    </section>
    
</body>
</html>