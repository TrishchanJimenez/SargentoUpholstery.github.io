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
    <img src="websiteimages/sargento_logo.png" alt="" class="business-logo">
    <div class="registration">
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

            <label for="password">Password</label><br>
            <input type="password" name="password" id="pw" placeholder="Enter your password" required><br><br>

            <label for="confirmPassword">Confirm Password</label><br>
            <input type="password" name="confirmPassword" placeholder="Re enter your password" required>
            <p>Use 8 or more characters with a mix of letters, numbers & symbols</p>
            <input type="submit" value="Sign Up" class="signUpBtn">
        </form>
        </div>
        <br>
        <p style="text-align: center;" >Already have an account? <a href="login.php">Log In</a></p>
    </div>
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
            $password = sanitizeInput($_POST['password']);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("SELECT * FROM usertable WHERE email = ?");
            $stmt->execute([$email]);
            if($stmt->rowCount() > 0) {
                echo "There is already an account associated with that email";
            } else {
                $stmt = $conn->prepare("INSERT INTO usertable(name, email, password, user_type) VALUES(?,?,?,?)");
                $stmt->execute([$name, $email, $hashedPassword, "user"]);
                echo "Registration Succesful";
                session_start();
            }
        }
    ?>
</body>
</html>