<?php
session_start();
include 'PHP/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM cars WHERE car_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    die("No car found with ID: " . $car_id);
}

$bookings_sql = "SELECT start_datetime, end_datetime FROM rental_bookings WHERE car_id = ? AND booking_status != 'active'";
$bookings_stmt = $conn->prepare($bookings_sql);
$bookings_stmt->bind_param("i", $car_id);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();

$booked_dates = array();
while($booking = $bookings_result->fetch_assoc()) {
    $start = new DateTime($booking['start_datetime']);
    $end = new DateTime($booking['end_datetime']);
    $interval = new DateInterval('P1D');
    $date_range = new DatePeriod($start, $interval, $end);
    
    foreach($date_range as $date) {
        $booked_dates[] = $date->format('Y-m-d');
    }
}

$rates_sql = "SELECT daily_rate, monthly_rate FROM cars WHERE car_id = ?";
$rates_stmt = $conn->prepare($rates_sql);
$rates_stmt->bind_param("i", $car_id);
$rates_stmt->execute();
$rates_result = $rates_stmt->get_result();
$rates = $rates_result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['car_id'])) {
    $sql = "SELECT DATE(start_datetime) AS start_date, DATE(end_datetime) AS end_date
            FROM rental_bookings WHERE car_id = ? AND booking_status = 'active'";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Database error: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $unavailable_dates = [];
    while ($row = $result->fetch_assoc()) {
        $start = new DateTime($row['start_date']);
        $end = new DateTime($row['end_date']);
        while ($start <= $end) {
            $unavailable_dates[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }
    }


    echo json_encode($unavailable_dates);
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>RentRide - Car Details</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
</head>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<?php include './elements/header.php' ?>
<body>
    <?php include "./elements/nav.php" ?>

    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home ></a></span> <span>Car details ></span></p>
                    <h1 class="mb-3 bread">Car Details</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-car-details">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="car-details">
                        <div class="img rounded" style="background-image: url(<?php echo $car['image'] ?>);"></div>
                        <div class="text text-center">
                            <span class="subheading"><?php echo $car['name'] ?></span>
                            <h2><?php echo $car['model'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services">
                        <div class="media-body py-md-4">
                            <div class="d-flex mb-3 align-items-center">
                                <div class="icon d-flex align-items-center justify-content-center"><span class="flaticon-dashboard"></span></div>
                                <div class="text">
                                    <h3 class="heading mb-0 pl-3">
                                        Mileage
                                        <span><?php echo $car['mileage']?></span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services">
                        <div class="media-body py-md-4">
                            <div class="d-flex mb-3 align-items-center">
                                <div class="icon d-flex align-items-center justify-content-center"><span class="flaticon-pistons"></span></div>
                                <div class="text">
                                    <h3 class="heading mb-0 pl-3">
                                        Transmission
                                        <span><?php echo $car['transmission']  ?></span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services">
                        <div class="media-body py-md-4">
                            <div class="d-flex mb-3 align-items-center">
                                <div class="icon d-flex align-items-center justify-content-center"><span class="flaticon-car-seat"></span></div>
                                <div class="text">
                                    <h3 class="heading mb-0 pl-3">
                                        Seats
                                        <span><?php echo $car['seats'] ?> Adults</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services">
                        <div class="media-body py-md-4">
                            <div class="d-flex mb-3 align-items-center">
                                <div class="icon d-flex align-items-center justify-content-center"><span class="flaticon-diesel"></span></div>
                                <div class="text">
                                    <h3 class="heading mb-0 pl-3">
                                        Fuel
                                        <span><?php  echo $car['fuel_type'] ?></span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 pills">
                    <div class="bd-example bd-example-tabs">
                        <div class="d-flex justify-content-center heading-section">
                            <span class="subheading">Description</span>
                        </div>
                        <p><?php echo $car['description'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="form-group">
                                <label for="bookingType" class="font-weight-bold">Rental Duration</label>
                                <select class="form-control" id="bookingType" name="booking_type" required  onchange="getType()">
                                    <option value="">Select Duration Type</option>
                                    <option value="daily">Daily Rental</option>
                                    <option value="monthly">Monthly Rental</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <form id="daily" action="PHP/book.php" method="POST" style="display:none">
                    <input type="hidden" name="booking_type" value="daily">
                        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="startDate">Start Date</label>
                                            <input type="date" class="form-control" id="startDate" name="start_date" required
                                                   min="<?php echo date('Y-m-d'); ?>" placeholder = "Select Start Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="endDate">End Date</label>
                                            <input type="date" class="form-control" id="endDate" name="end_date" required placeholder = "Select End Date">
                                        </div>
                                    </div>
                                </div>

                                <div class="rental-summary mt-4">
                                    <h5>Rental Summary</h5>
                                    <div class="d-flex justify-content-between">
                                        <span>Duration:</span>
                                        <span id="duration">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Rate:</span>
                                        <span id="d-rate"><?php echo $car['daily_rate']; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total Cost:</span>
                                        <span id="totalCost">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-block">Confirm Booking</button>
                            </div>
                        </div>
                    </form>

                    <form id="monthly" action="PHP/book.php" method="POST" style="display:none">
                        <input type="hidden" name="booking_type" value="monthly">
                        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rentalMonth">Choose Rental Month</label>
                                            <select class="form-control" id="rentalMonth" name="rentalMonth" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="rental-summary mt-4">
                                    <h5>Rental Summary</h5>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total Cost:</span>
                                        <span id="totalCost"><span id="m-rate"><?php echo $car['monthly_rate'] ?></span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-block">Confirm Booking</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-no-pt">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 heading-section text-center ftco-animate mb-5">
                    <span class="subheading">Choose Car</span>
                    <h2 class="mb-2">Related Cars</h2>
                </div>
            </div>
            <div class="row">
                <?php
                $query = "SELECT * FROM cars WHERE availability_status = 'available' ORDER BY RAND() LIMIT 3";
                $result = mysqli_query($conn, $query);

                while ($car = mysqli_fetch_assoc($result)) {
                    echo "<div class='col-md-4'>";
                    echo "<div class='car-wrap rounded ftco-animate'>";
                    echo "<div class='img rounded d-flex align-items-end' style='background-image: url(" . $car['image'] . ")'></div>";
                    echo "<div class='text'>";
                    echo "<h2 class='mb-0'><a href='car-single.php?id=" . $car['car_id'] . "'>" . $car['name'] . "</a></h2>";
                    echo "<div class='d-flex mb-3'>";
                    echo "<span class='cat'>" . $car['model'] . "</span>";
                    echo "<p class='price ml-auto'>" . $car['daily_rate'] . " <span>/day</span></p>";
                    echo "</div>";
                    echo "<a href='car-single.php?id=" . $car['car_id'] . "' class='btn btn-secondary py-2 ml-1'>Details</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {

            $('#startDate').timepicker();
            $('#endDate').timepicker();
        });

        function getType() {
            const bookingType = document.getElementById('bookingType').value;
            
            const totalCostElement = document.getElementById('totalCost');
            
           

            if (bookingType === 'daily') {

                document.getElementById('daily').style.display = 'block';
                document.getElementById('monthly').style.display = 'none';
                

                updateDuration();
            } else if (bookingType === 'monthly') {

                rate = document.getElementById('m-rate').textContent;
                document.getElementById('daily').style.display = 'none';
                document.getElementById('monthly').style.display = 'block';


            } else {

                document.getElementById('daily').style.display = 'none';
                document.getElementById('monthly').style.display = 'none';
            }
        }

        function updateDuration() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const durationElement = document.getElementById('duration');
            const totalCostElement = document.getElementById('totalCost');
            var rate;

            if (startDate && endDate) {
                let durationInDays = 0;

                if (startDate.getTime() === endDate.getTime()) {
     
                    durationInDays = 1;
                } else {
           
                    const timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
                    durationInDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; 
                }

                durationElement.textContent = `${durationInDays} day(s)`;

   
                rate = document.getElementById('d-rate').textContent; 
                const totalCost = durationInDays * rate;
                totalCostElement.textContent = `$${totalCost}`;
            } else {
                durationElement.textContent = '0';
                totalCostElement.textContent = '$0.00'; 
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
 
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');

            startDateInput.addEventListener('change', function () {
                endDateInput.min = startDateInput.value;
                updateDuration();
            });

            endDateInput.addEventListener('change', function () {
                updateDuration();
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const carId = document.querySelector('input[name="car_id"]').value;
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const rentalMonthSelect = document.getElementById('rentalMonth');


            fetch('', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `car_id=${encodeURIComponent(carId)}`,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                return response.json();
            })
            .then(unavailableDates => {
                console.log('Unavailable dates:', unavailableDates);

  
                const unavailableSet = new Set(unavailableDates.map(date => {
   
                    const dateObj = new Date(date); 
                    return dateObj.toISOString().split('T')[0]; 
                }));

                
                const startDateCalendar = flatpickr(startDateInput, {
                    disable: Array.from(unavailableSet),
                    minDate: "today", 
                });

                const endDateCalendar = flatpickr(endDateInput, {
                    disable: Array.from(unavailableSet),
                    minDate: "today", 
                });

               
                const availableMonths = new Set();
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth() + 1; 

                for (let year = currentYear; year <= currentYear + 1; year++) {
                    for (let month = 1; month <= 12; month++) {
                        if (year === currentYear && month < currentMonth) continue;

                        const firstDayOfMonth = new Date(year, month - 1, 1);
                        const lastDayOfMonth = new Date(year, month, 0);
                        let isMonthAvailable = true;

                        for (let date = firstDayOfMonth; date <= lastDayOfMonth; date.setDate(date.getDate() + 1)) {
                            const formattedDate = date.toISOString().split('T')[0];
                            if (unavailableSet.has(formattedDate)) {
                                isMonthAvailable = false;
                                break;
                            }
                        }

                        if (isMonthAvailable) {
                            availableMonths.add(`${year}-${month < 10 ? '0' : ''}${month}`);
                        }
                    }
                }

                
                availableMonths.forEach(month => {
                    const option = document.createElement('option');
                    option.value = month;
                    option.textContent = new Date(month).toLocaleString('default', { month: 'long', year: 'numeric' });
                    rentalMonthSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        });
    </script>

    <?php include "./elements/footer.php"; ?>
    <?php include "./elements/scriptloader.php"?>
</body>
</html>
