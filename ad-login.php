<?php
include 'PHP/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
global $conn;

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result();

if ($user && mysqli_num_rows($user) > 0) {
    $row = mysqli_fetch_assoc($user);

    
    if ($password === $row['password']) {
        session_start();
        $_SESSION["login"] = true;
        $_SESSION["admin_id"] = $row["admin_id"];
        header("Location: admin.php");
        exit;
    } else {
        echo "Wrong password. <a href='ad-login.php'>Try again</a>";
        exit;
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
    <title>Admin Login</title>
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
                <div class="title">Admin Login</div>
                <form id="signupForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="email" id="email" name="email" class="field" placeholder="Email" required>
                    <input type="password" id="password" name="password" class="field" placeholder="Password" required>
                    <?php if(isset($error_message)): ?>
                        <div class="error"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <input type="submit" class="btn" value="Login">
                </form>
            </div>
        </div>
    </div>
</body>
</html>