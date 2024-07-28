# Ei-2024-25 Coding Exercises

## Educational Initiatives

### Exercise 1: Problem Statement on Design Patterns

Design patterns are **solutions to common problems**, **best practices**, or **code standards** in software design.

#### Table of Contents
1. [Creational Design Pattern](#creational-design-pattern)
2. [Structural Design Pattern](#structural-design-pattern)
3. [Behavioural Design Pattern](#behavioural-design-pattern)

---

#### 1. Creational Design Pattern
Creational design patterns deal with object creation mechanisms.

- **Factory Method**: Creates an instance of several derived classes.
- **Prototype**: A fully initialized instance to be copied or cloned.

#### 2. Structural Design Pattern
Structural design patterns deal with the organization of classes and objects.

- **Facade**: A single class that represents an entire subsystem.
- **Proxy**: An object representing another object.

#### 3. Behavioural Design Pattern
Behavioural design patterns deal with communication between objects, how they interact, and distribute responsibility.

- **Strategy**: Encapsulates an algorithm inside a class.
- **Mediator**: Defines simplified communication between classes.

---

### Exercise 2: Problem Statements for Mini-projects - *Virtual Classroom Manager*

A web application to manage virtual classrooms for teachers and students.

#### Features
- User registration and login for teachers and students.
- Teachers can create classrooms, add students, and create assignments for their classrooms.
- Students can view their classrooms, assignments, and submit assignments.

#### Tech Stack

##### Frontend
- **HTML5**: Markup language used for structuring and presenting content on the web.
- **CSS3**: Style sheet language used for describing the presentation of a document written in HTML.
- **JavaScript**: Programming language used to create dynamic and interactive effects within web browsers.

##### Backend
- **PHP**: Server-side scripting language designed for web development.
- **MySQL**: Open-source relational database management system.

##### Server
- **Apache**: Web server used to serve the application.

#### Prerequisites
- PHP 7.x or higher
- MySQL 5.x or higher
- A web server like Apache or Nginx

#### Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/yourusername/virtual-classroom-manager.git
    ```

2. **Set up the database:**
    - Open your MySQL client and create the database:
    ```sql
    CREATE DATABASE virtual_classroom_manager;
    ```
    - Use the `virtual_classroom_manager` database and create the necessary tables:
    ```sql
    USE virtual_classroom_manager;
    ```

3. **Configure the database connection:**
    - Open `database.php` and update the database connection details:
    ```php
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "virtual_classroom_manager";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
    ```

4. **Start your web server** and navigate to the project directory:
    - For Apache, place the project in the `htdocs` directory.

5. **Open your web browser** and navigate to `http://localhost/virtual-classroom-manager/index.html`.

#### Screenshots
![image](https://github.com/user-attachments/assets/595563be-8c0a-4e1b-81a1-08999f70588c )
![image](https://github.com/user-attachments/assets/06a97d8f-e0e2-4f23-b575-492ca8aac4fb)
![image](https://github.com/user-attachments/assets/64eee10e-ed01-456d-8a6f-246875c713ed)



