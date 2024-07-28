<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_classroom_manager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

function validateRole($role) {
    return $role === 'teacher' || $role === 'student';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];

        if (validateRole($role)) {
            $stmt = $conn->prepare("INSERT INTO users (id, username, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id, $username, $password, $role);
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Invalid role!";
        }

    } else if ($action === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $hash, $role);
        if ($stmt->fetch() && password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            echo json_encode(['success' => true, 'role' => $role]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials!']);
        }
        $stmt->close();

    } else if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($action === 'add_classroom') {
            $classroom_name = $_POST['classroom_name'];
            $stmt = $conn->prepare("INSERT INTO classrooms (name, user_id) VALUES (?, ?)");
            $stmt->bind_param("si", $classroom_name, $user_id);
            if ($stmt->execute()) {
                echo "Classroom added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();

        } else if ($action === 'add_student') {
            $student_id = $_POST['student_id'];
            $class_name = $_POST['class_name'];
            $stmt = $conn->prepare("SELECT id FROM classrooms WHERE name = ? AND user_id = ?");
            $stmt->bind_param("si", $class_name, $user_id);
            $stmt->execute();
            $stmt->bind_result($classroom_id);
            if ($stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO classroom_students (classroom_id, student_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $classroom_id, $student_id);
                if ($stmt->execute()) {
                    echo "Student added successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Classroom not found!";
            }
            $stmt->close();

        } else if ($action === 'create_assignment') {
            $class_name = $_POST['class_name'];
            $details = $_POST['details'];
            $stmt = $conn->prepare("SELECT id FROM classrooms WHERE name = ? AND user_id = ?");
            $stmt->bind_param("si", $class_name, $user_id);
            $stmt->execute();
            $stmt->bind_result($classroom_id);
            if ($stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO assignments (classroom_id, details, user_id) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $classroom_id, $details, $user_id);
                if ($stmt->execute()) {
                    echo "Assignment created successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Classroom not found!";
            }
            $stmt->close();

        } else if ($action === 'get_teacher_data') {
            $response = ['classrooms' => [], 'assignments' => [], 'students' => []];
        
            // Get classrooms
            $stmt = $conn->prepare("SELECT id, name FROM classrooms WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) {
                $response['classrooms'][] = ['id' => $id, 'name' => $name];
            }
            $stmt->close();
        
            // Get assignments
            $stmt = $conn->prepare("SELECT a.id, a.details, c.name FROM assignments a JOIN classrooms c ON a.classroom_id = c.id WHERE a.user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($id, $details, $class_name);
            while ($stmt->fetch()) {
                $response['assignments'][] = ['id' => $id, 'details' => $details, 'class_name' => $class_name];
            }
            $stmt->close();
        
            // Get students in each classroom
            foreach ($response['classrooms'] as $classroom) {
                $classroom_id = $classroom['id'];
                $classroom_name = $classroom['name'];
                $stmt = $conn->prepare("SELECT u.username FROM classroom_students cs JOIN users u ON cs.student_id = u.id WHERE cs.classroom_id = ?");
                $stmt->bind_param("i", $classroom_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $students = [];
                while ($row = $result->fetch_assoc()) {
                    $students[] = $row['username'];
                }
                $stmt->close();
                $response['students'][$classroom_name] = $students;
            }
        
            echo json_encode($response);
        } else if ($action === 'get_student_data') {
            $response = ['assignments' => [], 'submissions' => []];
            $stmt = $conn->prepare("SELECT a.id, a.details, c.name FROM assignments a JOIN classroom_students cs ON a.classroom_id = cs.classroom_id JOIN classrooms c ON a.classroom_id = c.id WHERE cs.student_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($id, $details, $class_name);
            while ($stmt->fetch()) {
                $response['assignments'][] = ['id' => $id, 'details' => $details, 'class_name' => $class_name];
            }
            $stmt->close();

            $stmt = $conn->prepare("SELECT s.id, s.details, c.name FROM submissions s JOIN classrooms c ON s.classroom_id = c.id WHERE s.student_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($id, $details, $class_name);
            while ($stmt->fetch()) {
                $response['submissions'][] = ['id' => $id, 'details' => $details, 'class_name' => $class_name];
            }
            $stmt->close();
            echo json_encode($response);

        } else if ($action === 'submit_assignment') {
            $class_name = $_POST['class_name'];
            $details = $_POST['details'];
            $stmt = $conn->prepare("SELECT id FROM classrooms WHERE name = ?");
            $stmt->bind_param("s", $class_name);
            $stmt->execute();
            $stmt->bind_result($classroom_id);
            if ($stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO submissions (classroom_id, details, student_id) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $classroom_id, $details, $user_id);
                if ($stmt->execute()) {
                    echo "Assignment submitted successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Classroom not found!";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
