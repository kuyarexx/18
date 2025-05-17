<?php
$connection = new mysqli("localhost", "admin", "password", "ems");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$serialNumber = $_POST['serialNumber'];

$query = "DELETE FROM equipment WHERE serialNumber = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $serialNumber);

if ($stmt->execute()) {
    echo "Deleted successfully";
} else {
    echo "Error deleting record: " . $connection->error;
}

$stmt->close();
$connection->close();
?>
