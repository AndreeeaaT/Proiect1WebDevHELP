<?php
include "db_conn.php";
session_start();

// Include functiile
include "functions.php";

if (isset($_COOKIE['username']) && isset($_COOKIE['user_type']) && isset($_COOKIE['password']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['user_type'] = $_COOKIE['user_type'];
    
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];
    $user_type = $_COOKIE['user_type'];

    $sql = "SELECT * FROM utilizatori WHERE user_name='$username' AND parola='$password' AND tip_utilizator='$user_type'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        if ($_SESSION['user_type'] == 'administrator') {
            header("Location: admin_page.php");
            exit();
        } elseif ($_SESSION['user_type'] == 'pacient') {
            header("Location: pacient_acount.php");
            exit();
        } elseif ($_SESSION['user_type'] == 'doctor') {
            header("Location: doctor_account.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
}

// Restul codului din index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spitalul de urgenta Cardia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 
</head>
<body>
    <header class="bg-dark text-light">
        <div class="container">
            <h1>Spitalul de urgență "Cardia"</h1>
            <img src="imagini/Spital1.jpg" alt="spital1">
            <canvas id="canvas" width="200" height="200" style="background-color:#333">
Sorry, your browser does not support canvas.
</canvas>

<script>
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
let radius = canvas.height / 2;
ctx.translate(radius, radius);
radius = radius * 0.90
setInterval(drawClock, 1000);

function drawClock() {
  drawFace(ctx, radius);
  drawNumbers(ctx, radius);
  drawTime(ctx, radius);
}

function drawFace(ctx, radius) {
  const grad = ctx.createRadialGradient(0,0,radius*0.95, 0,0,radius*1.05);
  grad.addColorStop(0, '#333');
  grad.addColorStop(0.5, 'white');
  grad.addColorStop(1, '#333');
  ctx.beginPath();
  ctx.arc(0, 0, radius, 0, 2*Math.PI);
  ctx.fillStyle = 'white';
  ctx.fill();
  ctx.strokeStyle = grad;
  ctx.lineWidth = radius*0.1;
  ctx.stroke();
  ctx.beginPath();
  ctx.arc(0, 0, radius*0.1, 0, 2*Math.PI);
  ctx.fillStyle = '#333';
  ctx.fill();
}

function drawNumbers(ctx, radius) {
  ctx.font = radius*0.15 + "px arial";
  ctx.textBaseline="middle";
  ctx.textAlign="center";
  for(let num = 1; num < 13; num++){
    let ang = num * Math.PI / 6;
    ctx.rotate(ang);
    ctx.translate(0, -radius*0.85);
    ctx.rotate(-ang);
    ctx.fillText(num.toString(), 0, 0);
    ctx.rotate(ang);
    ctx.translate(0, radius*0.85);
    ctx.rotate(-ang);
  }
}

function drawTime(ctx, radius){
    const now = new Date();
    let hour = now.getHours();
    let minute = now.getMinutes();
    let second = now.getSeconds();
    //hour
    hour=hour%12;
    hour=(hour*Math.PI/6)+
    (minute*Math.PI/(6*60))+
    (second*Math.PI/(360*60));
    drawHand(ctx, hour, radius*0.5, radius*0.07);
    //minute
    minute=(minute*Math.PI/30)+(second*Math.PI/(30*60));
    drawHand(ctx, minute, radius*0.8, radius*0.07);
    // second
    second=(second*Math.PI/30);
    drawHand(ctx, second, radius*0.9, radius*0.02);
}

function drawHand(ctx, pos, length, width) {
    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.moveTo(0,0);
    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.rotate(-pos);
}
</script>
<br>


</body>
</html>

            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="about.php">Despre</a></li>
                    <li class="nav-item"><a class="nav-link" href="docs.php">Doctorii noștri</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>
  
    <section id="about" class="py-5">
        <div class="container">
            <h2>Spitalul drag inimii</h2>
            <p>Spitalul de urgență "Cardia" oferă servicii medicale excelente în toate domeniile medicinei, echipamente tehnologice de ultimă generație, alături de doctorii noștri implicați și devotați, gata să lupte împotriva bolii.</p>
            <h3>Secțiile spitalului nostru:</h3>
            <table class="table">
                <tr>
                    <td>
                        <strong>Cardiologie:</strong>
                        <br>Departamentul de cardiologie se ocupă de diagnosticul și tratamentul afecțiunilor inimii.
                        <br><img src="imagini/cardio.png" alt="Cardiologie" style="width: 150px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Radiologie:</strong>
                        <br>Departamentul de radiologie utilizează imagistică medicală pentru diagnosticul și tratamentul diferitelor boli.
                        <br><img src="imagini/radio.png" alt="Radiologie" style="width: 150px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Pediatrie:</strong>
                        <br>Departamentul de pediatrie se ocupă de îngrijirea și tratamentul copiilor și adolescenților.
                        <br><img src="imagini/pediatrie.jpg" alt="Pediatrie" style="width: 150px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Ginecologie:</strong>
                        <br>Departamentul de ginecologie se concentrează pe sănătatea femeilor, inclusiv diagnosticul și tratamentul afecțiunilor legate de sistemul reproducător feminin.
                        <br><img src="imagini/gineco.png" alt="Ginecologie" style="width: 150px;">
                    </td>
                </tr>
                <!-- Adaugă aici alte linii pentru sectiile spitalului -->
            </table>
        </div>
    </section>
    

    <footer class="bg-dark text-light py-4">
        <div class="container">
            <p>&copy; 2024 Spitalul de Urgență "Cardia". Toate drepturile rezervate.</p>
            <svg width="100" height="100">
            <circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
            <text x="50" y="55" font-size="10" text-anchor="middle" fill="black">5 ani experiență</text>
            Sorry, your browser does not support inline SVG.
            </svg>
        </div>
    </footer>
</body>
</html>