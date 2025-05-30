<?php
session_start();
require_once('classes/database.php');
$con = new database();
$sweetAlertConfig = "";

if (empty($_POST['id'])) {
    header("Location: admin_homepage.php");
    exit();
}

$id = $_POST['id'];
$data = $con->viewAuthorsID($id);

if (isset($_POST['updateAuthor'])) {
    $id = $_POST['id'];
    $authorfirstname = $_POST['authorfirstname'];
    $authorlastname = $_POST['authorlastname'];
    $authorbirthyear = $_POST['authorbirthyear'];
    $authornationality = $_POST['authornationality'];

    $result = $con->updateAuthor($id, $authorfirstname, $authorlastname, $authorbirthyear, $authornationality);

    if ($result) {
        $sweetAlertConfig = "
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                  icon: 'success',
                  title: 'Author Updated Successfully',
                  text: 'An author has been successfully updated.',
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
                  text: 'An error occurred while updating author. Please try again.',
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
  <title>Authors</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto active" href="add_authors.php">Add Authors</a>
      <a class="btn btn-outline-light ms-2" href="add_genres.php">Add Genres</a>
      <a class="btn btn-outline-light ms-2" href="add_books.php">Add Books</a>
      <div class="dropdown ms-2">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li>
            <a class="dropdown-item" href="profile.html">
              <i class="bi bi-person-circle me-2"></i> See Profile Information
            </a>
          </li>
          <li>
            <button class="dropdown-item" onclick="updatePersonalInfo()">
              <i class="bi bi-pencil-square me-2"></i> Update Personal Information
            </button>
          </li>
          <li>
            <button class="dropdown-item" onclick="updatePassword()">
              <i class="bi bi-key me-2"></i> Update Password
            </button>
          </li>
          <li>
            <button class="dropdown-item text-danger" onclick="logout()">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">
    <h4 class="mt-5">Update Existing Author</h4>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $data['author_id']?>">
      <div class="mb-3">
        <label for="authorFirstName" class="form-label">First Name</label>
        <input type="text" value="<?php echo $data['author_FN']?>" class="form-control" id="authorFirstName" name="authorfirstname" required>
      </div>
      <div class="mb-3">
        <label for="authorLastName" class="form-label">Last Name</label>
        <input type="text" value="<?php echo $data['author_LN']?>" class="form-control" id="authorLastName" name="authorlastname" required>
      </div>
      <div class="mb-3">
        <label for="authorBirthYear" class="form-label">Birth Date</label>
        <input type="date" value="<?php echo isset($data['author_birthday']) ? date('Y-m-d', strtotime($data['author_birthday'])) : ''; ?>" class="form-control" id="authorBirthYear" name="authorbirthyear" max="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="mb-3">
        <label for="authorNationality" class="form-label">Nationality</label>
        <select class="form-select" id="authorNationality" name="authornationality" required>
          <?php
            $nationalities = [
            "Filipino", "American", "British", "Canadian", "Chinese", "French",
            "German", "Indian", "Japanese", "Mexican", "Russian", "South African", "Spanish", "Other"
            ];

            foreach ($nationalities as $nat) {
                $selected = ($data['author_nat'] === $nat) ? 'selected' : '';
                echo "<option value=\"$nat\" $selected>$nat</option>";
            }
            ?>

        </select>
      </div>
      <button type="submit" name="updateAuthor" class="btn btn-primary">Add Author</button>
    </form>
    <?php echo $sweetAlertConfig; ?>
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
