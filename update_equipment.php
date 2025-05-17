<?php
header('Content-Type: application/json');

$connection = new mysqli("localhost", "admin", "password", "ems");

if ($connection->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $connection->connect_error]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->serialNumber)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing data"]);
    exit();
}

$query = "UPDATE equipment SET 
    equipmentName = ?, 
    brand = ?, 
    serialNo = ?, 
    propertyNumber = ?, 
    modelNumber = ?, 
    acquisitionDate = ?, 
    acquisitionCost = ?, 
    personAccountable = ?, 
    location = ?
    WHERE serialNumber = ?";

$stmt = $connection->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed: " . $connection->error]);
    exit();
}

$stmt->bind_param("ssssssssss", 
    $data->equipmentName,
    $data->brand,
    $data->serialNo, // Changed from yearPurchased
    $data->propertyNumber,
    $data->modelNumber,
    $data->acquisitionDate,
    $data->acquisitionCost,
    $data->personAccountable,
    $data->location,
    $data->serialNumber
);

if ($stmt->execute()) {
    echo json_encode(["message" => "Updated"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error updating: " . $stmt->error]);
}

$stmt->close();
$connection->close();
?>
