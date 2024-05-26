<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="js/login.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="/websiteimages/login-splash.jpg" alt="" class="splash-image">
        <div class="form_container">
            <img src="/websiteimages/sargento_logo.png" alt="" class="logo">
            <p>Sign in</p>
            <form method="post">
                <p>Email or mobile phone number</p>
                <input type="email" name="email" id="email" class="input" placeholder="Enter email">
                 <!-- New wrapper for input and show/hide button -->
                <div class="password">
                    <p>Your password</p>
                    <div class="showhide">
                        <button type="button" id="togglePassword">
                            <img src="/websiteimages/icons/hide.svg" alt="Show Password" id="eyeClosed">
                            <img src="/websiteimages/icons/show.svg" alt="Hide Password" id="eyeOpen" style="display: none;">
                        </button>
                        <span id="showText">Show</span>
                        <span id="hideText" style="display: none;">Hide</span>
                    </div>
                </div>
                <input type="password" id="password" name="password" class="input" placeholder="Enter Password">
                <a href="forgot_password.php" class="forgotpass">Forgot password?</a> <!-- Moved the "Forgot password" link after the "Log in" button -->
                <input type="submit" value="Log in" class="login_button">
            </form>
            <a href="register.php" class="create_button">Create an Account</a>
            <a href="index.php" class="back-button">Back to Homepage</a>
        </div>
    </div>
</body>
</html>

<?php
    include_once('database_connection.php');
    include_once("alert.php");

    function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = sanitizeInput($_POST['email']);
        $password = sanitizeInput($_POST['password']);

        $query = "
            SELECT 
                * 
            FROM 
                users 
            WHERE 
                email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        $query = "
            SELECT 
                address 
            FROM 
                addresses
            WHERE 
                user_id = :user_id
            LIMIT 1
        ";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user['user_id']);
        $stmt->execute();
        $address = $stmt->fetch();

        if($user) {
            if(password_verify($password, $user['password'])) {
                echo "Login Successful";
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['access_type'] = $user['user_type'];
                $_SESSION['contact_number'] = $user['contact_number'];
                $_SESSION['user_address'] = $address['address'];
                // Redirect user to dashboard or any other page
                if($user['user_type'] == 'admin') {
                    header("Location: ./admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                sendAlert("error", "Incorrect password. Please try again.");
            }
        } else {
            sendAlert("error", "Email unknown. Please enter a registered email or sign up.");
        }
    }
?>