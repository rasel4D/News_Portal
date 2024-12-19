<?php
session_start();
include('includes/config.php');
require_once 'includes/header.php';

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Simple query to check credentials
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['logged_in'] = true;
        
        // Redirect based on role
        if($row['role'] == 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: admin/dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NewsPortal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="flex justify-center items-center h-screen">
        <div class="login-form bg-indigo-500 shadow-2xl shadow-indigo-500/50  p-8 rounded-lg w-full max-w-md">
            <h2 class="text-gray-100 text-2xl font-bold mb-4">Login</h2>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group mb-4">
                    <label class="text-gray-100 block font-bold mb-2">Email</label>
                    <input type="email" name="email" class="form-control border rounded-lg py-2 px-3 w-full" required>
                </div>
                
                <div class="form-group mb-4">
                    <label class="text-gray-100 block font-bold mb-2">Password</label>
                    <input type="password" name="password" class="form-control border rounded-lg py-2 px-3 w-full" required>
                </div>
                
                <button type="submit" name="login" class="bg-blue-500 hover:bg-green-700 text-back font-bold py-2 px-5 rounded w-full">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php require_once 'includes/footer.php'; ?> 