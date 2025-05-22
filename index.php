<?php
session_start();
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $user = $con->loginUser($email, $password);

  if ($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_FN'] = $user['user_FN'];
    $_SESSION['user_type'] = $user['user_type'];

    $redirectUrl = ($user['user_type'] == 1) ? 'admin_homepage.php' : 'homepage.php';

    // SweetAlert with fallback redirect
    $sweetAlertConfig = "<script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'success',
          title: 'Login Successful',
          text: 'Welcome, " . addslashes(htmlspecialchars($user['user_FN'])) . "!',
          confirmButtonText: 'Continue'
        }).then(() => {
          window.location.href = '$redirectUrl';
        });
        // fallback in case user doesn't click
        setTimeout(() => { window.location.href = '$redirectUrl'; }, 5000);
      });
    </script>";
  } else {
    $sweetAlertConfig = "<script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error',
          title: 'Login Failed',
          text: 'Invalid email or password.'
        });
      });
    </script>";
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library Management System</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container custom-container rounded-3 shadow p-4 bg-light mt-5">
  <h3 class="text-center mb-4">Login</h3>
  <form method="post" action="" novalidate>
    <div class="form-group mb-3">
      <label for="email">Email:</label>
      <input type="email" class="form-control" name="email" placeholder="Enter email" required>
    </div>
    <div class="form-group mb-3">
      <label for="password">Password:</label>
      <input type="password" class="form-control" name="password" placeholder="Enter password" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary w-100 py-2">Login</button>
    <div class="text-center mt-4">
      <a href="registration.php" class="text-decoration-none">Don't have an account? Register here</a>
    </div>
  </form>
</div>

<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>

<!-- SweetAlert Display -->
<?php echo $sweetAlertConfig; ?>

</body>
</html>
