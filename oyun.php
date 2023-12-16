<?php
// Veritabanı bağlantısı
$connect = mysqli_connect(
    'db',        // service name
    'php_docker', // username
    'password',   // password
    'php_docker'  // db table
);

// Oturumu başlat
session_start();

// Oturumda kullanıcı bilgileri var mı kontrol et
if (isset($_SESSION['user_id'])) {
    // Kullanıcının oturum bilgilerini al
    $userId = $_SESSION['user_id'];
    echo '<script>const userId = ' . $userId . ';</script>';


    // Kullanıcının "game" tablosunda kaydı var mı kontrol et
    $checkQuery = "SELECT user_id FROM game WHERE user_id = $userId";
    $checkResult = mysqli_query($connect, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // Eğer kayıt yoksa, yeni bir kayıt ekle
        $insertQuery = "INSERT INTO game (user_id, highest_score) VALUES ($userId, 0)";
        $insertResult = mysqli_query($connect, $insertQuery);

        if (!$insertResult) {
            echo "Oyun tablosuna kayıt eklenirken bir hata oluştu: " . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Oyun</title>
   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Snake Game</title>
    <link rel="stylesheet" href="style-game.css" />

    <style>
        body {

            font-family: Arial, sans-serif;
            background-color: #142F10;
         
            background-size: cover;
            background-repeat: no-repeat;
          
      
            margin: 0;
        }

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

h3{
  text-align: center;
}

.user-info:hover {
    background-color: #45a049; /* Hover durumunda renk değişimi */
}


        .user-info a {
            color: #A1D648;
        }


        .wrapper {
            position: relative;
        }

        
        
        .controls {
            margin-top: 50px; 
        }

        h3{
          text-align: center;
        }

        .text{
          font-size:28px;
          font-weight: 700;
          
        }

        a{
         
    text-decoration: none;

        }

    </style>





</head>
<body>
    <div class="user-info">
        <?php
        // Oturumda kullanıcı bilgileri var mı kontrol et
        if (isset($_SESSION['user_id'])) {
            // Kullanıcının oturum bilgilerini al
            $userId = $_SESSION['user_id'];

            // Kullanıcı bilgilerini sorgula
            $query = "SELECT id, name, highest_score FROM user2 u
                        INNER JOIN game g on g.user_id=u.id
                        WHERE id = $userId";
            $result = mysqli_query($connect, $query);

            // Sorgu sonuçlarını kontrol et
            if ($result) {
                // Veritabanından alınan verileri kullanarak HTML çıktısı oluştur
                $row = mysqli_fetch_assoc($result);
                echo "İsim: " . $row['name'] . "<br>";
                echo "ID: " . $row['id'] . "<br><br>";
                echo "En yüksek: " . $row['highest_score'] . "<br><br>";

                
                echo '<p><a href="logout.php">Çıkış Yap</a></p>';
            } else {
                echo "Sorgu hatası: " . mysqli_error($connect);
            }
        } else {
            // Oturumda kullanıcı bilgileri yoksa hata mesajı göster
            echo "Oturum açılmamış";
            echo '<p><a href="login.php">Oturum Aç</a></p>';
        }
        ?>
    </div>
    
    <canvas id="canvas" class="ccanvas"></canvas>
    <div id="high-score" ></div>
    <div id="score"></div>
    <div id="keys">
        <div><h3>Nasıl Oynanır</h3></div>
        <hr>
        <div>Sag : D veya ➡️</div>
        <div>Sol : A veya ⬅️</div>
        <div>Yukari : W veya ⬆️</div>
        <div>Asagi : S veya ⬇️</div>
    </div>
    <div id="controls">
        <button id="show-grid">Çizgili Alan</button>
        <button id="pause" class="pause-active">Durdur</button>
        <button id="reset">Bastan basla</button>
        <button id="leaderboard">Lider Tablosu</button>
      
    </div>
    <div id="play"><p class="text">Başlamak için kontrol tuşlarına basın<span style="font-size: 3em;"></p></span></div>
    
</body>

<script>


   (function () {
  const canvas = document.getElementById("canvas");
  const ctx = canvas.getContext("2d");

  // canvas size
  const canvasSize = 680;
  const w = (canvas.width = canvasSize);
  const h = (canvas.height = canvasSize);
  const canvasFillColor = "#000000";
  const canvasStrokeColor = "rgba(211, 211, 211, 0.19)";

  const scoreEl = document.getElementById("score");
  const resetEl = document.getElementById("reset");
  const showGridEl = document.getElementById("show-grid");
  const highScoreEl = document.getElementById("high-score");
  const pauseEl = document.getElementById("pause");
  const playEl = document.getElementById("play");

  let score = 0;

  const Score = () => {
    scoreEl.innerHTML = `Skor : ${score}`;
    
  };


  function saveScore(skor) {
    
    const uid = userId;
    const xhttp = new XMLHttpRequest();
    const url = 'score.php';
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            console.log(xhttp.responseText);
        }
    };

    const data = {
        uid: uid,
        skor: skor
    };

    xhttp.send(JSON.stringify(data));
}

  

  // frame rate
  const frameRate = 9.5;

  // grid padding
  const pGrid = 4;
  // grid width
  const grid_line_len = canvasSize - 2 * pGrid;
  //  cell count
  const cellCount = 44;
  // cell size
  const cellSize = grid_line_len / cellCount;

  let gameActive;

  // this will generate random color for head
  const randomColor = () => {
    let color;
    let colorArr = ["#42f57e", "#42f57e"];
    color = colorArr[Math.floor(Math.random() * 2)];
    return color;
  };

  const head = {
    x: 2,
    y: 1,
    color: randomColor(),
    vX: 0,
    vY: 0,
    draw: () => {
      ctx.fillStyle = head.color;
      ctx.shadowColor = head.color;
      ctx.shadowBlur = 2.5;
      ctx.fillRect(
        head.x * cellSize + pGrid,
        head.y * cellSize + pGrid,
        cellSize,
        cellSize
      );
    },
  };

  let tailLength = 4;
  let snakeParts = [];
  class Tail {
    color = "#42f57e";
    constructor(x, y) {
      this.x = x;
      this.y = y;
    }
    draw() {
      ctx.fillStyle = this.color;
      ctx.shadowColor = this.color;
      ctx.shadowBlur = 2.5;
      ctx.fillRect(
        this.x * cellSize + pGrid,
        this.y * cellSize + pGrid,
        cellSize,
        cellSize
      );
    }
  }

  const food = {
    x: 5,
    y: 5,
    color: "#FF3131",
    draw: () => {
      ctx.fillStyle = food.color;
      ctx.shadowColor = food.color;
      ctx.shadowBlur = 5;
      ctx.fillRect(
        food.x * cellSize + pGrid,
        food.y * cellSize + pGrid,
        cellSize,
        cellSize
      );
    },
  };

  // this will set canvas style
  const setCanvas = () => {
    // canvas fill
    ctx.fillStyle = canvasFillColor;
    ctx.fillRect(0, 0, w, h);

    // canvas stroke
    ctx.strokeStyle = canvasStrokeColor;
    ctx.strokeRect(0, 0, w, h);
  };

  //   this will draw the grid
  const drawGrid = () => {
    ctx.beginPath();
    for (let i = 0; i <= grid_line_len; i += cellSize) {
      ctx.moveTo(i + pGrid, pGrid);
      ctx.lineTo(i + pGrid, grid_line_len + pGrid);
    }
    for (let i = 0; i <= grid_line_len; i += cellSize) {
      ctx.moveTo(pGrid, i + pGrid);
      ctx.lineTo(grid_line_len + pGrid, i + pGrid);
    }
    ctx.closePath();
    ctx.strokeStyle = canvasStrokeColor;
    ctx.stroke();
  };

  const drawSnake = () => {
    //loop through our snakeparts array
    snakeParts.forEach((part) => {
      part.draw();
    });

    snakeParts.push(new Tail(head.x, head.y));

    if (snakeParts.length > tailLength) {
      snakeParts.shift(); //remove furthest item from  snake part if we have more than our tail size
    }
    head.color = randomColor();
    head.draw();
  };

  const updateSnakePosition = () => {
    head.x += head.vX;
    head.y += head.vY;
  };

  const changeDir = (e) => {
    let key = e.keyCode;

    if (key == 68 || key == 39) {
      if (head.vX === -1) return;
      head.vX = 1;
      head.vY = 0;
      gameActive = true;
      return;
    }
    if (key == 65 || key == 37) {
      if (head.vX === 1) return;
      head.vX = -1;
      head.vY = 0;
      gameActive = true;
      return;
    }
    if (key == 87 || key == 38) {
      if (head.vY === 1) return;
      head.vX = 0;
      head.vY = -1;
      gameActive = true;
      return;
    }
    if (key == 83 || key == 40) {
      if (head.vY === -1) return;
      head.vX = 0;
      head.vY = 1;
      gameActive = true;
      return;
    }
  };

  const foodCollision = () => {
    let foodCollision = false;
    snakeParts.forEach((part) => {
      if (part.x == food.x && part.y == food.y) {
        foodCollision = true;
      }
    });
    if (foodCollision) {
      food.x = Math.floor(Math.random() * cellCount);
      food.y = Math.floor(Math.random() * cellCount);
      score++;
      tailLength++;
    }
  };

  const isGameOver = () => {
    let gameOver = false;

    snakeParts.forEach((part) => {
      if (part.x == head.x && part.y == head.y) {
        gameOver = true;
      }
    });

    if (
      head.x < 0 ||
      head.y < 0 ||
      head.x > cellCount - 1 ||
      head.y > cellCount - 1
    ) {
      gameOver = true;
    }

    return gameOver;

  };

  const showGameOver = () => {
    const text = document.createElement("div");
    text.setAttribute("id", "game_over");
    text.innerHTML = "game over !";
    const body = document.querySelector("body");
    body.appendChild(text);
   
    saveScore(score);

  };

  addEventListener("keydown", changeDir);

  const PlayButton = (show) => {
    if (!show) {
      playEl.style.display = "none";
    } else {
      playEl.style.display = "block";
    }
  };

  

  const pauseGame = () => {
    gameActive = false;
    if(!gameActive) {
      pauseEl.removeAttribute('class');
      pauseEl.setAttribute('class', 'pause-not-active')
    }
    if (!isGameOver()) PlayButton(true);


}

  pauseEl.addEventListener("click", pauseGame);

  let showGrid = false;

  // this will initiate all
  const animate = () => {
    setCanvas();
    if (showGrid) drawGrid();
    drawSnake();
    food.draw();
    if (gameActive) {
      PlayButton(false);




      pauseEl.removeAttribute('class');
      pauseEl.setAttribute('class','pause-active');
      updateSnakePosition();
      if (isGameOver()) {
        showGameOver();
        PlayButton(false);
        return;
      }
    }
    Score();
    foodCollision();
    setTimeout(animate, 1000 / frameRate);
  };

  const resetGame = () => {
    location.reload();
    
  };

  document.getElementById('leaderboard').addEventListener('click', function() {
            window.open('leaderboard.php', '_self');
        });

  resetEl.addEventListener("click", resetGame);

  

  const toggleGrid = () => {
    if (!showGrid) {
      showGrid = true;
      showGridEl.innerHTML = `Cizgisiz Alan`
      return;
    }
    showGrid = false;
    showGridEl.innerHTML=`Cizgili Alan`
  };

  showGridEl.addEventListener("click", toggleGrid);
  animate();
})();

</script>

</html>


