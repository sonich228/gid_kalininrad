<?php
header('Content-Type: application/json');

// Подключаемся к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guidekaliningrad_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера']);
    exit();
}

$conn->set_charset("utf8");

// Получаем данные из запроса
$user_username = $_POST['username'] ?? '';
$user_password = $_POST['password'] ?? '';

if (empty($user_username) || empty($user_password)) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit();
}

// Ищем пользователя в базе
$sql = "SELECT id, name, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Проверяем пароль (он хранится в хэшированном виде)
    if (password_verify($user_password, $row['password'])) {
        echo json_encode([
            'success' => true,
            'user_id' => $row['id'],
            'name' => $row['name'],
            'username' => $row['username']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
}

$stmt->close();
$conn->close();
?>