<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

// Assuming you've already connected to the database
require 'db_config.php';

// Fetch the user profile data
$username = $_SESSION["username"];

// Initialize variables to hold user data
$firstname = $middlename = $lastname = $age = $email = $homeaddress = "";

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get new values from the form
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $age = trim($_POST['age']);
    $email = trim($_POST['email']);
    $homeaddress = trim($_POST['homeaddress']);

    // Update user profile data in the database
    $sql = "UPDATE customers_tbl SET firstname=?, middlename=?, lastname=?, age=?, email=?, homeaddress=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) { // Check if statement preparation was successful
        $stmt->bind_param("ssissss", $firstname, $middlename, $lastname, $age, $email, $homeaddress, $username);
        
        if ($stmt->execute()) {
            // Successfully updated; update session variables
            $_SESSION["firstname"] = $firstname;
            $_SESSION["middlename"] = $middlename;
            $_SESSION["lastname"] = $lastname;
            $_SESSION["age"] = $age;
            $_SESSION["email"] = $email;
            $_SESSION["homeaddress"] = $homeaddress;
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
        
        // Close the statement if it was created
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Set variables from session if not set from POST
$firstname = $_SESSION["firstname"] ?? '';
$middlename = $_SESSION["middlename"] ?? '';
$lastname = $_SESSION["lastname"] ?? '';
$age = $_SESSION["age"] ?? '';
$email = $_SESSION["email"] ?? '';
$homeaddress = $_SESSION["homeaddress"] ?? '';

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supreme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script> 
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
                    <a style="color: #CE1126;" class="nav-link active" aria-current="page" href="dashboard.php">Products</a>
                </li>
                <li class="nav-item">
                    <a style="color: #CE1126;" class="nav-link active" aria-current="page" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a style="color: #CE1126;" class="nav-link active" aria-current="page" href="#">Contact Us</a>
                </li>
            </ul>
            <!-- Display the username as a clickable link -->
            <span class="navbar-text" style="margin-right: 20px;">
                <a href="profile.php" style="color: #CE1126; text-decoration: none;">
                    <?php echo htmlspecialchars($_SESSION["username"]); ?>
                </a>
            </span>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2>User Profile</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlspecialchars($firstname); ?></li>
        <li class="list-group-item"><strong>Middle Name:</strong> <?php echo htmlspecialchars($middlename); ?></li>
        <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlspecialchars($lastname); ?></li>
        <li class="list-group-item"><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></li>
        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
        <li class="list-group-item"><strong>Home Address:</strong> <?php echo htmlspecialchars($homeaddress); ?></li>
    </ul>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit profile form -->
                <form id="editProfileForm" method="POST" action="">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="middlename" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middlename" id="middlename" value="<?php echo htmlspecialchars($middlename); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" name="age" id="age" value="<?php echo htmlspecialchars($age); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="homeaddress" class="form-label">Home Address</label>
                        <input type="text" class="form-control" name="homeaddress" id="homeaddress" value="<?php echo htmlspecialchars($homeaddress); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
