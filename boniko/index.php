<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supreme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script> 
    <script>
        window.onload = function() {
            // Check if the cookie 'user' exists
            if (document.cookie.split(';').some((item) => item.trim().startsWith('user='))) {
                window.location.href = 'dashboard.php'; // Redirect to dashboard if cookie exists
            }
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <img style="width: 100px; cursor: pointer;" src="Images/logo.jpg" class="logo">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a style="color: #CE1126;" class="nav-link active" aria-current="page" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a style="color: #CE1126;" class="nav-link active" aria-current="page" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a style="color: #CE1126;" class="nav-link active" aria-current="page" href="#">Contact Us</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">

                    <button type="button" class="btn btn-outline-danger" style="white-space: nowrap; padding: 10px 20px; margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#loginModal">Sign in</button>
                    <button type="button" class="btn btn-danger" style="padding: 10px 20px;" data-bs-toggle="modal" data-bs-target="#signupModal">Register</button>


                </form>
            </div>
        </div>
    </nav>
    <div class="landing">
        <h1 style="color: #CE1126; line-height: 1;" >
            SUPREME <br>
            <small style="color: black;">Jump Higher Act Cooler</small>
        </h1>
        <button style="font-size: 20px; padding: 15px 30px; width: auto; height: auto;" type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#signupModal">Register Now</button>
        
        <!-- SIGN-IN MODAL -->
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="loginForm" action="login.php" method="POST">
                            <div class="form-group">
                                <label for="login-email" class="col-form-label">Email:</label>
                                <input type="email" name="email" class="form-control" id="login-email" 
                                    value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="login-password" class="col-form-label">Password:</label>
                                <input type="password" name="password" class="form-control" id="login-password" 
                                    value="<?php echo isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="remember">Remember Me:</label> 
                                <input type="checkbox" name="remember" id="remember" 
                                    <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                            </div>
                            <button type="submit" class="btn btn-danger">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- REGISTER MODAL -->
        <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="signupForm" action="register.php" method="POST">
                            <div class="form-group">
                                <label for="signup-email" class="col-form-label">Email:</label>
                                <input type="email" class="form-control" id="signup-email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="signup-username" class="col-form-label">Username:</label>
                                <input type="text" class="form-control" id="signup-username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="signup-password" class="col-form-label">Password:</label>
                                <input type="password" class="form-control" id="signup-password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-danger" style="margin-top: 20px;">Register</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>