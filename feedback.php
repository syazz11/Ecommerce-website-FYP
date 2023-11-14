<!DOCTYPE html>
<html>
<head>
    <title>Feedback Form</title>
    <link rel="stylesheet" type="text/css" href="feedback.css">
</head>
<body>
    <h1>Give Us your Feedback</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve user inputs
        $name = $_POST["name"];
        $email = $_POST["email"];
        $message = $_POST["message"];

        // Connect to the MySQL database
        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "utopia";

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert feedback into the database
        $sql = "INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $message);
            $stmt->execute();
            $stmt->close();

            echo '<div style="text-align: center;">Feedback Submitted Successfully</div>';
        } else {
            echo "Error: " . $conn->error;
        }

        $conn->close();
    } else {
    ?>
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="message">Message:</label>
        <textarea name="message" required></textarea><br>

        <input type="submit" value="Submit">
    </form>
    <?php
    }
    ?>
</body>
</html>
