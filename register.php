<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    
    <!-- <form action="register_process.php" method="post">
        Username: <input type="text" name="username" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        Occupation: <input type="text" name="occupation" required><br>
        Contact No: <input type="tel" name="contact_no" required><br>
        <div class="submit"><input type="submit" value="Register"></div>
        
    </form> -->

    
    <div class="container">
        
        <div class="right-section">
            <div class="login-container">
                <form class="login-form" action="register_process.php" method="post">
                <h2 class="login-title"><img src="image\geopulse logo.png" class="logo" alt="" style=""> </br> Geopulse</h2>
                    <div class="form-control">
                        <i class="fas fa-user icon"></i>
                        <input type="text" name="username" placeholder="UserName" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    
                    <div class="form-control">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" placeholder="Password" id="password" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-briefcase icon"></i>
                        <input type="text" name="occupation" placeholder="Occupation" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-phone icon"></i>
                        <input type="tel" name="contact_no" placeholder="contact number" required>
                    </div>
                   
                    <button type="submit" value="Register">Register</button>
                    <p class="endline"><a href="login.php">I am already a member</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
