<?php
include 'database/db_connect.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute
    $stmt = $conn->prepare("SELECT password FROM userdata WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if ($password === $db_password) {
            $message = "Login successful";
            $toastClass = "bg-success";
            
            session_start();
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password";
            $toastClass = "bg-danger";
        }
    } else {
        $message = "Email not found";
        $toastClass = "bg-warning";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64'%3E%3Ctext x='0' y='50' font-size='50' fill='black'%3ESachin%3C/text%3E%3C/svg%3E">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/login.css">
    <title>Login Page</title>
    <style>
        body {
            background-image: url('https://www.pixelstalk.net/wp-content/uploads/image12/A-cool-green-leaf-wallpaper-HD-featuring-a-close-up-view-of-dew-covered-leaves-with-intricate-details.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .container {
            margin-top: 100px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            transition: border 0.3s, box-shadow 0.3s;
            border: 1px solid yellow; 
            border-radius: 5px; /* Rounded corners */
            padding: 10px; /* Padding inside input */
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none; /* Remove default outline */
        }

        .btn-success {
            transition: background-color 0.3s;
        }

        .btn-success:hover {
            background-color: #0056b3;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <form action="" method="post" class="form-control mt-5 p-4" style="width:380px;">
            <div class="row">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="text-align: center; color: green;"></i>
                <h5 class="text-center p-4" style="font-weight: 700;">Login Into Your Account</h5>
            </div>
            <div class="col mb-3">
                <label for="email"><i class="fa fa-envelope"></i> Email</label>
                <input type="text" name="email" id="email" class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <label for="password"><i class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <button type="submit" class="btn btn-success bg-success" style="font-weight: 600;">Login</button>
            </div>
            <div class="col mb-2 mt-4">
                <p class="text-center" style="font-weight: 600; color: navy;">
                    <a href="./register.php" style="text-decoration: none;">Create Account</a> OR 
                    <a href="./resetpassword.php" style="text-decoration: none;">Forgot Password</a>
                </p>
            </div>
        </form>
    </div>
    <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>

</html>
