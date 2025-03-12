<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;

if ($booking_id <= 0) {
    header("Location: ../rented_cars.php");
    exit();
}

$sql = "UPDATE rental_bookings SET booking_status = 'cancelled' WHERE booking_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: ../myrental.php");
    exit();
} else {
    echo "Failed to cancel the booking. Please try again.";
}

$stmt->close();
$conn->close();
?>
