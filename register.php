<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            <input type="text" name="name" id="name" placeholder="Enter your name"><br><br>
            <label for="Email">Email</label><br>
            <input type="text" name="email" placeholder="Enter your email address"><br><br>
            <label for="pw">Password</label><br>
            <input type="text" name="pw" id="pw" placeholder="Enter your password"><br><br>
            <label for="pwRep">Confirm Password</label><br>
            <input type="text" name="conPw" placeholder="Re enter your password">
            <p>Use 8 or more characters with a mix of letters, numbers & symbols</p>
            <input type="submit" value="Sign Up" class="signUpBtn">
        </form>
        </div>
        <br>
        <p style="text-align: center;" >Already have an account? <a href="login.php">Log In</a></p>
    </div>
</body>
</html>