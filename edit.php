<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    include "db_conn.php";
    include 'php/User.php';

    $user = getUserById($_SESSION['id'], $conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php if ($user) { ?>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <form class="shadow w-450 p-3" 
              action="php/edit.php" 
              method="post"
              enctype="multipart/form-data">

            <h4 class="display-4 fs-1">Edit Profile</h4><br>

            <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
              <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php } ?>
            
            <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success" role="alert">
              <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
            <?php } ?>

            <div class="mb-3">
                <label class="form-label" for="fname">Full Name</label>
                <input type="text" 
                       class="form-control"
                       id="fname"
                       name="fname"
                       value="<?php echo htmlspecialchars($user['fname']); ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="uname">User Name</label>
                <input type="text" 
                       class="form-control"
                       id="uname"
                       name="uname"
                       value="<?php echo htmlspecialchars($user['uname']); ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="pp">Profile Picture</label>
                <input type="file" 
                       class="form-control"
                       id="pp"
                       name="pp">
                <img src="upload/<?php echo htmlspecialchars($user['pp']); ?>"
                     class="rounded-circle mt-2"
                     style="width: 70px">
                <input type="hidden" 
                       name="old_pp"
                       value="<?php echo htmlspecialchars($user['pp']); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="home.php" class="link-secondary">Home</a>
        </form>
    </div>
    <?php } else {
        header("Location: home.php");
        exit;
    } ?>
</body>
</html>

<?php } else {
    header("Location: login.php");
    exit;
} ?>
