<?php 
session_start();

if(isset($_POST['uname']) && isset($_POST['pass'])){

    include "../db_conn.php";

    $uname = trim($_POST['uname']);
    $pass = $_POST['pass'];

    $data = "uname=" . urlencode($uname);

    if(empty($uname)){
        $em = "User name is required";
        header("Location: ../login.php?error=$em&$data");
        exit;
    } else if(empty($pass)){
        $em = "Password is required";
        header("Location: ../login.php?error=$em&$data");
        exit;
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$uname]);

        if($stmt->rowCount() === 1){
            $user = $stmt->fetch();
            $password = $user['password'];
            $fname = $user['fname'];
            $id = $user['id'];
            $pp = $user['pp'];

            if(password_verify($pass, $password)){
                $_SESSION['id'] = $id;
                $_SESSION['fname'] = $fname;
                $_SESSION['pp'] = $pp;

                header("Location: ../home.php");
                exit;
            } else {
                $em = "Incorrect User name or password";
                header("Location: ../login.php?error=$em&$data");
                exit;
            }
        } else {
            $em = "Incorrect User name or password";
            header("Location: ../login.php?error=$em&$data");
            exit;
        }
    }
} else {
    header("Location: ../login.php?error=error");
    exit;
}
?>
