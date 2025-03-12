<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingType = $_POST['booking_type'];
    $userId = $_SESSION['user_id'] ?? null;
    $carId = $_POST['car_id'] ?? null;
    $rentalMonth = isset($_POST['rentalMonth']) ? $_POST['rentalMonth'] : '';
    
    if (!$userId || !$carId) {
        die("Invalid user or car ID");
    }

   
    $rateStmt = $conn->prepare("SELECT daily_rate, monthly_rate FROM cars WHERE car_id = ?");
    $rateStmt->bind_param("i", $carId);
    $rateStmt->execute();
    $result = $rateStmt->get_result();
    $carRates = $result->fetch_assoc();
    
    if ($bookingType === 'daily') {
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);
        
        $interval = $startDateTime->diff($endDateTime);
        $durationInDays = $interval->days;
        $totalCost = $durationInDays * $carRates['daily_rate'];
    } elseif ($bookingType === 'monthly') {
        $totalCost = $carRates['monthly_rate'];
        $selectedMonth = new DateTime($rentalMonth);
        $startDateTime = clone $selectedMonth;
        $startDateTime->modify('first day of this month');
        $endDateTime = clone $selectedMonth;
        $endDateTime->modify('last day of this month');
    }

    $stmt = $conn->prepare("INSERT INTO rental_bookings (user_id, car_id, start_datetime, end_datetime, booking_type, total_cost, booking_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $status = 'active';
    $createdAt = (new DateTime())->format('Y-m-d H:i:s');
    $startDateStr = $startDateTime->format('Y-m-d H:i:s');
    $endDateStr = $endDateTime->format('Y-m-d H:i:s');
    
    $stmt->bind_param("iisssdss", $userId, $carId, $startDateStr, $endDateStr, $bookingType, $totalCost, $status, $createdAt);
    $stmt->execute();

    header("Location: ../myrental.php");
    exit;
}
?>