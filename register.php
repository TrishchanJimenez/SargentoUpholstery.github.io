<?php include 'database_connection.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="registration-container">
        <img src="/websiteimages/login-splash.jpg" alt="" class="splash-image">
        <div class="registration">
            <img src="/websiteimages/sargento_logo.png" alt="" class="business-logo">
            <div class="regTitle">
                <h1>Sign Up</h1>
                <h3>Sign up with your Email Address </h3>
            </div> 
            <div class="regForm">
            <form action="" method="post" id="signUpForm">
                <label for="name">Name</label><br>
                <input type="text" name="name" id="name" placeholder="Enter your name" required><br><br>
    
                <label for="Email">Email</label><br>
                <input type="email" name="email" placeholder="Enter your email address" required><br><br>
    
                <label for="contactno">Phone</label><br>
                <input type="tel" name="contactno" placeholder="Enter your contact no." required><br><br>
    
                <label for="password">
                    <span>Password</span>
                    <span class="toggle-password">
                        <img src="/websiteimages/icons/show.svg" alt="" class="toggle-img">
                        <span class="toggle-text">Show</span>
                    </span>
                </label>
                <input type="password" name="password" id="pw" placeholder="Enter your password" required><br><br>
                <p class="error_message email"></p>
    
                <label for="confirmPassword">Confirm Password</label><br>
                <input type="password" name="confirmPassword" placeholder="Re enter your password" required>
                <p class="error_message password hide">Use 8 or more characters with a mix of letters, numbers & symbols</p>
                <input type="submit" value="Sign Up" class="signUpBtn">
            </form>
            </div>
            <br>
            <p style="text-align: center;" >Already have an account? <a href="login.php" class="login-link">Log In</a></p>
        </div>
    </div>
    <script src="js/register.js"></script>
    <?php
        function sanitizeInput($input) {
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
            return $input;
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = sanitizeInput($_POST['name']);
            $email = sanitizeInput($_POST['email']);
            $contactno = sanitizeInput($_POST['contactno']);
            $password = sanitizeInput($_POST['password']);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if($stmt->rowCount() > 0) {
                echo "<script> alert('There is already an account associated with that email') </script>";
            } else {
                $stmt = $conn->prepare("INSERT INTO users(name, email, password, contact_number, user_type) VALUES(?,?,?,?,?)");
                $stmt->execute([$name, $email, $hashedPassword, $contactno, "customer"]);
                echo "Registration Succesful";
               /*  session_start();
                $_SESSION['access_type'] = 'customer';
                $_SESSION['user_id'] = $conn->lastInsertId(); */
                header("Location: login.php");
            }
        }
    ?>
</body>
</html>