<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'saveToMySQL') {
        saveToMySQLFunction();
    }
}

function saveToMySQLFunction() {
    $connection = new mysqli('0.0.0.0:3306', 'root', 'root', 'test');
    if ($connection->connect_error) {
        die('Connection failed: ' . $connection->connect_error);
    }
    $secret_id = $_POST['secretId'];
    $data = $_POST['data'];
    
    $checkQuery = "SELECT * FROM Passwords WHERE secret_id = ?";
    $checkStmt = $connection->prepare($checkQuery);
    $checkStmt->bind_param("s", $secret_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // The element already exists
        echo "Secret ID : '$secret_id' already occupied";
    } else {
        // The element doesn't exist, proceed with the insertion
        $insertQuery = "INSERT INTO Passwords (secret_id, data) VALUES (?, ?)";
        $insertStmt = $connection->prepare($insertQuery);
        $insertStmt->bind_param("ss", $secret_id, $data);

        if ($insertStmt->execute()) {
            echo "Data saved successfully!";
        } else {
            echo "Error: " . $insertStmt->error;
        }
        
        $insertStmt->close();
    }

    $checkStmt->close();
    $connection->close();

}
?>