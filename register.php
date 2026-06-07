<?php

include 'config.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_name = mysqli_real_escape_string($conn, $_POST['name']);
    $user_username = mysqli_real_escape_string($conn, $_POST['username']);
    $user_password = mysqli_real_escape_string($conn, $_POST['password']);

    
    if (empty($user_name) || empty($user_username) || empty($user_password)) {
        $error = "Пожалуйста, заполните все поля!";
    } else {
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, username, password) VALUES ('$user_name', '$user_username', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Регистрация прошла успешно! Теперь вы можете войти.";

        } else {
 
            if ($conn->errno == 1062) {
                $error = "Ошибка: Этот логин уже занят. Придумайте другой.";
            } else {
                $error = "Ошибка: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация на ГидКалининград</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #1a73e8; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #1a73e8; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background: #1557b0; }
        .error { color: red; text-align: center; margin-top: 10px; }
        .success { color: green; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <form action="register.php" method="post">
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <p style="text-align: center; margin-top: 15px;">Уже есть аккаунт? <a href="#">Войти</a></p>
    </div>
</body>
</html>