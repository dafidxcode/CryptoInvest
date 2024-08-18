<?php 
include('config/db_connection.php');

$phone = $password = '';
$errors = array('phone' => '', 'password' => '');

// Check if the form is submitted
if (isset($_POST['Login'])) {

    // Check phone
    if (empty($_POST['phone'])) {
        $errors['phone'] = 'Phone is required <br>';
    } else {
        $phone = $_POST['phone'];
        if (!preg_match('/^[0-9]*$/', $phone)) {
            $errors['phone'] = 'Phone must be numbers only! <br>';
        }
    }

    // Check password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required! <br>';
    } else {
        $password = $_POST['password'];
    }

    if (!array_filter($errors)) {
        $phone = mysqli_real_escape_string($conn, $phone);

        // Query the database to find a matching user
        $query = "SELECT * FROM cmi_account WHERE phone = '$phone'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                // User is authenticated
                session_start();
                $_SESSION['phone'] = $phone;
                header("Location: main.php");
                exit();
            } else {
                $errors['password'] = 'Incorrect password!';
            }
        } else {
            $errors['phone'] = 'Phone number not registered!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="stylesheet/log.css">
    <link rel="icon" href="images/Crypto.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <img src="images/Crypto.png" alt="logo" class="logo">
        <h1>Log in</h1>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="phone" placeholder="Please enter your mobile phone number" required value="<?php echo htmlspecialchars($phone); ?>">
                <div class="red-text"><?php echo $errors['phone']; ?></div>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Please enter your password" required>
                <div class="red-text incorrect-password"><?php echo $errors['password']; ?></div>
            </div>
            <button type="submit" name="Login">Log in now</button>
        </form>
        <a href="index.php">To register ></a>
    </div>
</body>
</html>
