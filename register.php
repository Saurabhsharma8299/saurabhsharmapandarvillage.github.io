<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'pandar_village';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create villagers table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS villagers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        father_name VARCHAR(100) NOT NULL,
        mother_name VARCHAR(100) NOT NULL,
        dob DATE NOT NULL,
        age INT NOT NULL,
        gender ENUM('male', 'female', 'other') NOT NULL,
        mobile VARCHAR(15) NOT NULL,
        address TEXT NOT NULL,
        city VARCHAR(50) NOT NULL,
        district VARCHAR(50) NOT NULL,
        pincode VARCHAR(6) NOT NULL,
        education VARCHAR(50) NOT NULL,
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and sanitize inputs
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $father_name = filter_var($_POST['father_name'], FILTER_SANITIZE_STRING);
        $mother_name = filter_var($_POST['mother_name'], FILTER_SANITIZE_STRING);
        $dob = $_POST['dob'];
        $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
        $mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
        $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
        $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
        $district = filter_var($_POST['district'], FILTER_SANITIZE_STRING);
        $pincode = filter_var($_POST['pincode'], FILTER_SANITIZE_STRING);
        $education = filter_var($_POST['education'], FILTER_SANITIZE_STRING);

        // Validate required fields
        if (empty($name) || empty($father_name) || empty($mother_name) || empty($dob) || 
            empty($age) || empty($gender) || empty($mobile) || empty($address) || 
            empty($city) || empty($district) || empty($pincode) || empty($education)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        // Validate mobile number
        if (!preg_match("/^[0-9]{10}$/", $mobile)) {
            echo json_encode(['success' => false, 'message' => 'Invalid mobile number']);
            exit;
        }

        // Validate pincode
        if (!preg_match("/^[0-9]{6}$/", $pincode)) {
            echo json_encode(['success' => false, 'message' => 'Invalid pincode']);
            exit;
        }

        // Insert into database
        $sql = "INSERT INTO villagers (name, father_name, mother_name, dob, age, gender, 
                mobile, address, city, district, pincode, education) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name, $father_name, $mother_name, $dob, $age, $gender,
            $mobile, $address, $city, $district, $pincode, $education
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Registration successful!'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
