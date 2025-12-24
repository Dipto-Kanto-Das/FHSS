<?php
session_start();
include 'includes/db.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username'";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            $_SESSION['admin'] = $row['username'];
            header("Location: dashboard/index.php");
            exit();
        } else {
            $error = "Password ভুল হয়েছে।";
        }
    } else {
        $error = "Username ভুল হয়েছে।";
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
<div class="col-md-4">
<form method="POST" class="border p-3 rounded">
    <h3>Admin Login</h3>
    <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
    <input type="submit" name="login" class="btn btn-primary w-100" value="Login">
</form>
<?php if(isset($error)) echo "<p class='text-danger mt-2'>$error</p>"; ?>
</div>
</div>
<?php include 'includes/footer.php'; ?>
