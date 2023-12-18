<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'getData') {
        if (isset($_POST['secret_id'])) {
            $secretId = $_POST['secret_id'];

            $connection = new mysqli('0.0.0.0:3306', 'root', 'root', 'test');
            if ($connection->connect_error) {
                die('Connection failed: ' . $connection->connect_error);
            }

            $stmt = $connection->prepare("SELECT data FROM Passwords WHERE secret_id = ?");
            $stmt->bind_param("s", $secretId);
            $stmt->execute();
            $stmt->bind_result($data);

            if ($stmt->fetch()) {
                // Return the data
                echo "$data";
            } else {
                echo "No data found for secret_id";
            }
            $stmt->close();
            $connection->close();
        } else {
            echo "Missing 'secret_id' parameter in the POST request.";
        }
    }
} else {
    echo "This script only accepts POST requests.";
}
?>