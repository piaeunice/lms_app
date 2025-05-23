<?php
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $genrename = $_POST['genrename'];

  $genreID = $con->addGenre($genrename); // Correct variable name here

  if ($genreID) {
    $sweetAlertConfig = "
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'success',
          title: 'Genre Added Successfully',
          text: 'A genre has been successfully added.',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = 'add_genres.php';
        });
      });
    </script>";
  } else {
    $sweetAlertConfig = "
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'An error occurred while adding genre. Please try again.',
          confirmButtonText: 'OK'
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
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Genres</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto" href="add_authors.html">Add Authors</a>
      <a class="btn btn-outline-light ms-2 active" href="add_genres.php">Add Genres</a>
      <a class="btn btn-outline-light ms-2" href="add_books.html">Add Books</a>
      <div class="dropdown ms-2">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="profile.html"><i class="bi bi-person-circle me-2"></i> See Profile Information</a></li>
          <li><button class="dropdown-item" onclick="updatePersonalInfo()"><i class="bi bi-pencil-square me-2"></i> Update Personal Information</button></li>
          <li><button class="dropdown-item" onclick="updatePassword()"><i class="bi bi-key me-2"></i> Update Password</button></li>
          <li><button class="dropdown-item text-danger" onclick="logout()"><i class="bi bi-box-arrow-right me-2"></i> Logout</button></li>
        </ul>
      </div>
    </div>
  </nav>

<div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">
  <h4 class="mt-5">Add New Genre</h4>
  <form method="POST">
    <div class="mb-3">
      <label for="genreName" class="form-label">Genre Name</label>
      <input type="text" class="form-control" id="genreName" name="genrename" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Genre</button>
  </form>
  <?php echo $sweetAlertConfig; ?>
</div>

<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
