<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "todo_app_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $task = $_POST['task'];
    $stmt = $conn->prepare("INSERT INTO tasks (task) VALUES (?)");
    $stmt->bind_param("s", $task);
    if ($stmt->execute()) {
        $id = $stmt->insert_id;
        echo '<li data-id="' . $id . '">
                <span>' . $task . '</span>
                <button class="delete">Delete</button>
                <button class="complete">Complete</button>
              </li>';
    }
    $stmt->close();
    exit();
}

// Handle Delete Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    exit();
}

// Handle Update Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id']) && isset($_POST['status'])) {
    $id = $_POST['update_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $status, $id);
    $stmt->execute();
    $stmt->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h1>To-Do List</h1>
        <form id="task-form">
            <input type="text" id="task-input" name="task" placeholder="Enter a new task...">
            <button type="submit">Add Task</button>
        </form>
        <ul id="task-list">
            <?php
            $result = $conn->query("SELECT * FROM tasks ORDER BY id ASC");
            while ($row = $result->fetch_assoc()) :
            ?>
                <li data-id="<?php echo $row['id']; ?>" class="<?php echo $row['status'] ? 'completed' : ''; ?>">
                    <span><?php echo $row['task']; ?></span>
                    <button class="delete">Delete</button>
                    <button class="complete"><?php echo $row['status'] ? 'Undo' : 'Complete'; ?></button>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
