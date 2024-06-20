<?php require "include/header.php"; ?>
<?php require "config.php"; ?>

<?php
// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["register"])) {
        // Registration process
        if (empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password'])) {
            echo "Please fill all fields.";
        } else {
            try {
                // Retrieve and sanitize user inputs
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $password = $_POST['password']; // Password will be hashed, no need to sanitize

                // Prepare SQL statement
                $insert = $conn->prepare("INSERT INTO users (email, username, mypassword) VALUES (:email, :username, :mypassword)");

                // Execute SQL statement with parameters
                $insert->execute([
                    ':email' => $email,
                    ':username' => $username,
                    ':mypassword' => password_hash($password, PASSWORD_DEFAULT),
                ]);

                echo "Registration successful!";
            } catch (PDOException $e) {
                echo "Registration failed: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST["login"])) {
        // Login process
        if (empty($_POST['email']) || empty($_POST['password'])) {
            echo "Please fill all fields.";
        } else {
            try {
                // Retrieve and sanitize user inputs
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                // Prepare SQL statement
                $login = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $login->execute([':email' => $email]);
                $user = $login->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    // Password is correct, start a session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    echo "Login successful! Welcome, " . htmlspecialchars($user['username']) . ".";
                } else {
                    echo "Incorrect email or password.";
                }
            } catch (PDOException $e) {
                echo "Login failed: " . $e->getMessage();
            }
        }
    }
}
?>

<input type="checkbox" id="chk" aria-hidden="true">

<div class="signup">
    <form method="post" action="index.php">
        <label for="chk" aria-hidden="true">Sign up</label>
        <input type="text" name="username" placeholder="User name" required="">
        <input type="email" name="email" placeholder="Email" required="">
        <input type="password" name="password" placeholder="Password" required="">
        <button type="submit" name="register">Sign up</button>
    </form>
</div>

<div class="login">
    <form method="post" action="index.php">
        <label for="chk" aria-hidden="true">Login</label>
        <input type="email" name="email" placeholder="Email" required="">
        <input type="password" name="password" placeholder="Password" required="">
        <button type="submit" name="login">Login</button>
    </form>
</div>

<?php require "include/footer.php"; ?>
