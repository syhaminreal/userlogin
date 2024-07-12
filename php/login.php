<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    header("Location: home.php");
    exit;
}

include "../db_conn.php";

if (isset($_POST['uname']) && isset($_POST['pass'])) {
    $uname = trim($_POST['uname']);
    $pass = $_POST['pass'];

    // URL-encode the username to prevent issues with special characters in the redirect
    $data = "uname=" . urlencode($uname);

    if (empty($uname)) {
        $em = "User name is required";
        header("Location: ../login.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($pass)) {
        $em = "Password is required";
        header("Location: ../login.php?error=" . urlencode($em) . "&$data");
        exit;
    } else {
        try {
            $sql = "SELECT * FROM users WHERE uname = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$uname]);

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $password = $user['pass']; // Make sure the column name is correct
                $fname = $user['fname'];
                $id = $user['id'];
                $pp = $user['pp'];

                if (password_verify($pass, $password)) {
                    $_SESSION['id'] = $id;
                    $_SESSION['fname'] = $fname;
                    $_SESSION['pp'] = $pp;

                    header("Location: ../home.php");
                    exit;
                } else {
                    $em = "Incorrect User name or password";
                    header("Location: ../login.php?error=" . urlencode($em) . "&$data");
                    exit;
                }
            } else {
                $em = "Incorrect User name or password";
                header("Location: ../login.php?error=" . urlencode($em) . "&$data");
                exit;
            }
        } catch (PDOException $e) {
            $em = "Database error: " . $e->getMessage();
            header("Location: ../login.php?error=" . urlencode($em) . "&$data");
            exit;
        }
    }
} else {
    header("Location: ../login.php?error=" . urlencode("Invalid request"));
    exit;
}
?>
