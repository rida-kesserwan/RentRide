<?php
include 'PHP/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
global $conn;

$email = mysqli_real_escape_string($conn, $_POST["email"]);
$password = mysqli_real_escape_string($conn, $_POST["password"]);
$user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

if ($user && mysqli_num_rows($user) > 0) {
    $row = mysqli_fetch_assoc($user);

    
    if ($password === $row['password']) {
        session_start();
        $_SESSION["login"] = true;
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["username"] = $row["username"];
        header("Location: index.php");
        exit;
    } else {
        echo "Wrong password. <a href='login.php'>Try again</a>";
        exit;
    }
} else {
    echo "User not registered. <a href='signup.php'>Sign up here</a>";
    exit;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
    <title>RentRide - Login</title>
    <style>
        body{
            margin:0px;
            font-family: "Poppins", Arial, sans-serif;
        }
        .background{
            width:100%;
            background:url("./images/bg_2.jpg");
            background-repeat: no-repeat;
            background-position: 50% 50%;
            background-size:cover;
        }
        .login-ctn{
            width:100vw;
            height:100vh;
            backdrop-filter:blur(5px);
            display:flex;
        }
        .login-form{
            margin:auto;
            width:40%;
            display:flex;
            flex-direction:column;
            justify-content:center;
            background-color:#1089ff;
            border-radius:12px;
            padding:2% 0;
        }
        form{
            display:flex;
            flex-direction:column;
            width:80%;
            margin:0 auto;
        }
        .title{
            width:100%;
            margin:2% 0;
            text-align:center;
            color:white;
            font-size:2em;
            font-weight:600;
        }
        .field,.btn{
            padding:1% 2%;
            border-radius:8px;
        }
        .field{
            width:90%;
            margin:5% auto;
        }
        .btn{
            width:40%;
            margin:4% auto 2% auto;
            background-color: #01d28e;
            color:white;
            font-size:1.3em;
            border:2px solid #01d28e;
            text-align:center;
            padding-bottom:2%;
            transition:200ms ease;
        }
        .btn:hover{
            cursor:pointer;
            background:transparent;
            border:2px solid white;
        }
        .error {
            color: red;
            font-size: 0.8em;
            margin: -4% auto 4% auto;
            text-align: center;
        }
        span{
            width:100%;
            text-align:center;
            margin-top:3%;
        }
        @media screen and (max-width:1000px){
            .login-form{
                width:90%;
            }
            #signupForm{
                width:90%;
            }
            .field{
                padding:3% 2%;
            }
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="login-ctn">
            <div class="login-form">
                <div class="title">Login</div>
                <form id="signupForm" method="POST">
                    <input type="email" id="email" name="email" class="field" placeholder="Email" required>
                    <input type="password" id="password" name="password" class="field" placeholder="Password" required>
                    <div id="passwordError" class="error"></div>
                    <div id="emailError" class="error"></div>
                    <input type="submit" class="btn" value="Login">
                </form>
                <span>Not a member? <a href="signup.php">Sign Up</a></span>
            </div>
        </div>
    </div>
</body>
</html>