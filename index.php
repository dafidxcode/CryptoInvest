<?php
include('config/db_connection.php');

$phone = $password = $confirmpass = '';
$errors = array('phone' => '', 'password' => '', 'confirmpass' => '');

// Check if the form is submitted
if (isset($_POST['CreateAccount'])) {

    // Check phone
    if (empty($_POST['phone'])) {
        $errors['phone'] = 'Phone is required! <br>';
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

    // Check confirmation password
    if (empty($_POST['confirmpass'])) {
        $errors['confirmpass'] = 'Confirmation password is required! <br>';
    } else {
        $confirmpass = $_POST['confirmpass'];
        if ($confirmpass !== $password) {
            $errors['confirmpass'] = 'Confirmation must match the password! <br>';
        }
    }

    if (!array_filter($errors)) {
        $phone = mysqli_real_escape_string($conn, $phone);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

        // Create SQL
        $sql = "INSERT INTO cmi_account(phone, password) VALUES ('$phone', '$hashed_password')";

        // Save to the database and check
        if (mysqli_query($conn, $sql)) {
            // Success
            header('Location: log.php');
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register an account</title>
    <link rel="icon" href="images/Crypto.png" type="image/x-icon">
    <link rel="stylesheet" href="stylesheet/style.css">
</head>
<body>
    <div class="container">
        <img src="images/Crypto.png" alt="logo" class="logo">
        <h1>Account registration</h1>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="phone" placeholder="Please enter phone number" value="<?php echo htmlspecialchars($phone) ?>" required>
                <div class="red-text"><?php echo $errors['phone']; ?></div>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Log in password" value="<?php echo htmlspecialchars($password) ?>" required>
                <div class="red-text"><?php echo $errors['password']; ?></div>
            </div>
            <div class="input-group">
                <input type="password" name="confirmpass" placeholder="Confirm password to log in" value="<?php echo htmlspecialchars($confirmpass) ?>" required>
                <div class="red-text"><?php echo $errors['confirmpass']; ?></div>
            </div>
            <button type="submit" name="CreateAccount">Register now</button>
        </form>
        <a href="log.php">Do you already have an account? Log in now</a>
    </div>
</body>
</html>
