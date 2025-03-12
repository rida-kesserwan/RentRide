<?php
session_start();
include 'PHP/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT rb.booking_id, c.image, c.name, c.license_plate, rb.start_datetime, rb.end_datetime, rb.total_cost, rb.booking_status
        FROM rental_bookings rb
        JOIN cars c ON rb.car_id = c.car_id
        WHERE rb.user_id = ?
        ORDER BY rb.start_datetime DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>RentRide - My Rentals</title>
    <?php include "./elements/header.php"?>
  </head>
  <body>
    
	  <?php include "./elements/nav.php" ?>

    
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
          <div class="col-md-9 ftco-animate pb-5">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home ></a></span> <span>My Rentals ></span></p>
            <h1 class="mb-3 bread">My Rentals</h1>
          </div>
        </div>
      </div>
    </section>
    
    <div class="container mt-5">
        <h2 class="mb-4">Rented Cars</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Car Image</th>
                        <th>Car Name</th>
                        <th>License Plate</th>
                        <th>Rent Date</th>
                        <th>Rent End Date</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-thumbnail" width="100"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['license_plate']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['start_datetime']))); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['end_datetime']))); ?></td>
                            <td><?php echo htmlspecialchars($row['total_cost']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_status']); ?></td>
                            <td>
                                <?php if ($row['booking_status'] === 'active'): ?>
                                    <form action="PHP/cancel_booking.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                        <button type="submit" class="btn btn-danger">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-danger" disabled>Cancel</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include "./elements/footer.php" ?>
  <?php include "./elements/scriptloader.php"?> 
  </body>
</html>