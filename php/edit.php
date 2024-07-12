<?php  
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    if (isset($_POST['fname']) && isset($_POST['uname'])) {
        include "../db_conn.php";

        $fname = trim($_POST['fname']);
        $uname = trim($_POST['uname']);
        $old_pp = $_POST['old_pp'];
        $id = $_SESSION['id'];

        if (empty($fname)) {
            $em = "Full name is required";
            header("Location: ../edit.php?error=" . urlencode($em));
            exit;
        } else if (empty($uname)) {
            $em = "User name is required";
            header("Location: ../edit.php?error=" . urlencode($em));
            exit;
        }

        try {
            if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];

                if ($error === 0) {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_to_lc = strtolower($img_ex);

                    $allowed_exs = ['jpg', 'jpeg', 'png'];
                    if (in_array($img_ex_to_lc, $allowed_exs)) {
                        $new_img_name = uniqid($uname, true) . '.' . $img_ex_to_lc;
                        $img_upload_path = '../upload/' . $new_img_name;

                        // Only delete old profile pic if a new one is uploaded
                        $old_pp_des = "../upload/$old_pp";
                        if (file_exists($old_pp_des)) {
                            unlink($old_pp_des);
                        }

                        move_uploaded_file($tmp_name, $img_upload_path);

                        // Update the Database
                        $sql = "UPDATE users SET fname=?, uname=?, pp=? WHERE id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$fname, $uname, $new_img_name, $id]);
                    } else {
                        $em = "You can't upload files of this type";
                        header("Location: ../edit.php?error=" . urlencode($em) . "&fname=" . urlencode($fname) . "&uname=" . urlencode($uname));
                        exit;
                    }
                } else {
                    $em = "Unknown error occurred!";
                    header("Location: ../edit.php?error=" . urlencode($em) . "&fname=" . urlencode($fname) . "&uname=" . urlencode($uname));
                    exit;
                }
            } else {
                // If no new profile picture is uploaded, just update the name and username
                $sql = "UPDATE users SET fname=?, uname=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$fname, $uname, $id]);
            }

            $_SESSION['fname'] = $fname;
            header("Location: ../edit.php?success=" . urlencode("Your account has been updated successfully"));
            exit;

        } catch (Exception $e) {
            $em = "An error occurred: " . $e->getMessage();
            header("Location: ../edit.php?error=" . urlencode($em) . "&fname=" . urlencode($fname) . "&uname=" . urlencode($uname));
            exit;
        }
    } else {
        header("Location: ../edit.php?error=" . urlencode("Invalid request"));
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
