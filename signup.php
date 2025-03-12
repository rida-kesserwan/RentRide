<?php 

include 'PHP/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 global $conn;

 $email = $_POST["email"];
 $username = $_POST["username"];
 $password = $_POST["password"];

 $user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
 if (mysqli_num_rows($user) > 0) {
     echo "Username already exists.";
     exit;
 }

 $query = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')";
 if (mysqli_query($conn, $query)) {
     echo "Registration successful.";
     header("Location: login.php");
     exit;
 } else {
     echo "Error: " . mysqli_error($conn);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>RentRide - Sign Up</title>
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
        .btn:disabled {
            background-color: #ccc;
            border: 2px solid #ccc;
            cursor: not-allowed;
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
                <div class="title">Sign Up</div>
                <form id="signupForm" method="POST">
                    <div class="field-container">
                        <input type="text" id="username" name="username" class="field" placeholder="User Name" required>
                    </div>
                    <div class="field-container">
                        <input type="email" id="email" name="email" class="field" placeholder="Email" required>
                    </div>
                    <div class="field-container">
                        <input type="password" id="password" name="password" class="field" placeholder="Password" required>
                        <i class="bi bi-eye toggle-password" onclick="togglePassword('password', this)"></i>
                    </div>
                    <div class="field-container">
                        <input type="password" id="confirmPassword" class="field" placeholder="Repeat Password" required>
                        <i class="bi bi-eye toggle-password" onclick="togglePassword('confirmPassword', this)"></i>
                    </div>
                    <div id="passwordError" class="error"></div>
                    <input type="submit" id="signUpButton" class="btn" value="Sign Up" disabled>
                </form>
                <span>Already a member? <a href="login.php">login</a></span>
            </div>
        </div>
    </div>

    <script>
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirmPassword');
        const signUpButton = document.getElementById('signUpButton');
        const passwordError = document.getElementById('passwordError');

        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        function validateForm() {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirmPassword').value.trim();

            if (
                username !== "" &&
                email !== "" &&
                password !== "" &&
                confirmPassword !== "" &&
                password === confirmPassword
            ) {
                passwordError.textContent = "";
                signUpButton.disabled = false;
            } else {
                passwordError.textContent = password !== confirmPassword && confirmPassword !== "" 
                    ? "Passwords do not match" 
                    : "";
                signUpButton.disabled = true;
            }
        }

        passwordField.addEventListener('input', validateForm);
        confirmPasswordField.addEventListener('input', validateForm);
        document.getElementById('username').addEventListener('input', validateForm);
        document.getElementById('email').addEventListener('input', validateForm);
    </script>
</body>
</html>
