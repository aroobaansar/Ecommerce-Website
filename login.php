<?php
// Start session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Connect to the database
        $db = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=users.accdb");

        // Retrieve username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute the query
        $query = $db->prepare("SELECT * FROM users WHERE username = ?");
        $query->execute([$username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Set session variable
            $_SESSION['username'] = $username;
            header("Location: dashboard.html");
            exit();
        } else {
            header("Location: login.html?error=Invalid credentials");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login.html?error=Database error");
        exit();
    }
}
?>
