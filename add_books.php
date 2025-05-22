<?php
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booktitle = $_POST['booktitle'];
    $bookisbn = $_POST['bookisbn'];
    $bookpubyear = $_POST['bookpubyear'];
    $quantity = $_POST['quantity'];

    $bookID = $con->addBook($booktitle, $bookisbn, $bookpubyear, $quantity);

    if ($bookID) {
        $sweetAlertConfig = "
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
              icon: 'success',
              title: 'Book Added Successfully',
              text: 'A book has been successfully added.',
              confirmButtonText: 'OK'
            }).then(() => {
              window.location.href = 'admin_homepage.php';
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
              text: 'An error occurred while adding the book. Please try again.',
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
  <title>Add Book</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto" href="add_authors.html">Add Authors</a>
      <a class="btn btn-outline-light ms-2" href="add_genres.html">Add Genres</a>
      <a class="btn btn-outline-light ms-2 active" href="add_books.php">Add Books</a>
    </div>
  </nav>

  <div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">
    <h4 class="mb-4">Add New Book</h4>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="bookTitle" class="form-label">Book Title</label>
        <input type="text" class="form-control" name="booktitle" id="bookTitle" required>
      </div>
      <div class="mb-3">
        <label for="bookISBN" class="form-label">ISBN</label>
        <input type="text" class="form-control" name="bookisbn" id="bookISBN" required>
      </div>
      <div class="mb-3">
        <label for="bookYear" class="form-label">Publication Year</label>
        <input type="number" class="form-control" name="bookpubyear" id="bookYear" required>
      </div>
      <div class="mb-3">
        <label for="bookQuantity" class="form-label">Quantity Available</label>
        <input type="number" class="form-control" name="quantity" id="bookQuantity" required>
      </div>
      <button type="submit" class="btn btn-primary">Add Book</button>
    </form>
    <?php echo $sweetAlertConfig; ?>
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
