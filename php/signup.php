<?php 

if (isset($_POST['fname']) && isset($_POST['uname']) && isset($_POST['pass'])) {

    include "../db_conn.php";

    $fname = trim($_POST['fname']);
    $uname = trim($_POST['uname']);
    $pass = trim($_POST['pass']);

    // URL-encode the form data to prevent issues with special characters
    $data = "fname=" . urlencode($fname) . "&uname=" . urlencode($uname);

    if (empty($fname)) {
        $em = "Full name is required";
        header("Location: ../index.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($uname)) {
        $em = "User name is required";
        header("Location: ../index.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($pass)) {
        $em = "Password is required";
        header("Location: ../index.php?error=" . urlencode($em) . "&$data");
        exit;
    } else {
        try {
            // Hashing the password
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Handling the profile picture upload
            if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];

                if ($error === 0) {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_to_lc = strtolower($img_ex);

                    $allowed_exs = array('jpg', 'jpeg', 'png');
                    if (in_array($img_ex_to_lc, $allowed_exs)) {
                        $new_img_name = uniqid($uname, true) . '.' . $img_ex_to_lc;
                        $img_upload_path = '../uploads/' . $new_img_name;

                        // Move the uploaded file to the designated path
                        if (move_uploaded_file($tmp_name, $img_upload_path)) {
                            // Insert into Database
                            $sql = "INSERT INTO users(fname, uname, pass, pp) VALUES(?,?,?,?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$fname, $uname, $hashed_pass, $new_img_name]);

                            header("Location: ../index.php?success=" . urlencode("Your account has been created successfully"));
                            exit;
                        } else {
                            $em = "Failed to upload the profile picture";
                            header("Location: ../index.php?error=" . urlencode($em) . "&$data");
                            exit;
                        }
                    } else {
                        $em = "You can't upload files of this type";
                        header("Location: ../index.php?error=" . urlencode($em) . "&$data");
                        exit;
                    }
                } else {
                    $em = "Unknown error occurred!";
                    header("Location: ../index.php?error=" . urlencode($em) . "&$data");
                    exit;
                }
            } else {
                // Insert without profile picture
                $sql = "INSERT INTO users(fname, uname, pass, pp) VALUES(?,?,?,'default-pp.png')";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$fname, $uname, $hashed_pass]);

                header("Location: ../index.php?success=" . urlencode("Your account has been created successfully"));
                exit;
            }
        } catch (PDOException $e) {
            $em = "Database error: " . $e->getMessage();
            header("Location: ../index.php?error=" . urlencode($em) . "&$data");
            exit;
        }
    }
} else {
    header("Location: ../index.php?error=" . urlencode("Invalid request"));
    exit;
}
?>
