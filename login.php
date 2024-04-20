<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/Loginstyles.css">
    <script src="js/login.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="Form_Container">
        <img src="websiteimages/sargento_logo.png" alt="" class="logo">
        <p>Sign in</p>
        <form method="post">
            <p>Email or mobile phone number</p>
            <input type="text" name="email" id="email" class="input">
            <div class="input_wrapper"> <!-- New wrapper for input and show/hide button -->
                <div class="password">
                    <p>Your password</p>
                    <div class="showhide">
                        <button type="button" id="togglePassword">
                            <img src="websiteimages/hide.svg" alt="Show Password" id="eyeClosed">
                            <img src="websiteimages/show.svg" alt="Hide Password" id="eyeOpen" style="display: none;">
                        </button>
                        <span id="showText">Show</span>
                        <span id="hideText" style="display: none;">Hide</span>
                    </div>
                </div>
            </div>
            <input type="password" id="password" name="password" class="input">
            <a href="forgetpass.html">Forgot password</a> <!-- Moved the "Forgot password" link after the "Log in" button -->
            <input type="submit" value="Log in" class="Login_button">
            
        </form>
        <a href="register.php"><button class="Create_button">Create an account</button></a>
    </div>
</body>
</html>

<?php
    include "database_connection.php";
    session_start();

    function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = sanitizeInput($_POST['email']);
        $password = sanitizeInput($_POST['password']);

        $stmt = $conn->prepare("SELECT * FROM usertable WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if($user) {
            if(password_verify($password, $user['password'])) {
                echo "Login Successful";
                $_SESSION['access_type'] = $user['user_type'];
                $_SESSION['id'] = $user['user_id'];
                // Redirect user to dashboard or any other page
                header("Location: index.php");
                exit();
            } else {
                echo "<script> alert('Incorrect password'); </script>";
            }
        } else {
            echo "<script> alert('User not found'); </script>";
        }
    }
?>