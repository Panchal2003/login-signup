<?php
include 'database/db_connect.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM userdata WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $message = "Email ID already exists";
        $toastClass = "#dc3545"; // Danger color
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO userdata (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "#28a745"; // Success color
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "#dc3545"; // Danger color
        }

        $stmt->close();
    }

    $checkEmailStmt->close();
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
    <title>Registration</title>
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
            border: 2px solid yellow; /* Border color */
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
            <div class="toast align-items-center text-white border-0" 
                 role="alert" aria-live="assertive" aria-atomic="true"
                 style="background-color: <?php echo $toastClass; ?>;">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" class="form-control mt-5 p-4"
              style="height:auto; width:380px;">
            <div class="row text-center">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="color: green;"></i>
                <h5 class="p-4" style="font-weight: 700;">Create Your Account</h5>
            </div>
            <div class="mb-2">
                <label for="username"><i class="fa fa-user"></i> User Name</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-2 mt-2">
                <label for="email"><i class="fa fa-envelope"></i> Email</label>
                <input type="text" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-2 mt-2">
                <label for="password"><i class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-2 mt-3">
                <button type="submit" class="btn btn-success bg-success" style="font-weight: 600;">Create Account</button>
            </div>
            <div class="mb-2 mt-4">
                <p class="text-center" style="font-weight: 600; color: navy;">I have an Account <a href="./index.php" style="text-decoration: none;">Login</a></p>
            </div>
        </form>
    </div>
    <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'));
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>

</html>
