<?php
include 'PHP/connection.php';

session_start();
include 'PHP/connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ad-login.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $category_description = $_POST['category_description'];

    $query = "INSERT INTO car_categories (category_name, description) VALUES ('$category_name', '$category_description')";
    if (mysqli_query($conn, $query)) {
        header("Location: admin.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['add_car'])) {
    $car_name = $_POST['car_name'];
    $car_model = $_POST['car_model'];
    $daily_rate = $_POST['daily_rate'];
    $monthly_rate = $_POST['monthly_rate'];
    $mileage = $_POST['mileage'];
    $transmission = $_POST['transmission'];
    $seats = $_POST['seats'];
    $fuel_type = $_POST['fuel_type'];
    $license_plate = $_POST['license_plate'];
    $category_id = $_POST['category_id'];

    $car_image = null;
    if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] == 0) {
        $target_dir = "uploads/";
        $car_image = $target_dir . basename($_FILES["car_image"]["name"]);
        move_uploaded_file($_FILES["car_image"]["tmp_name"], $car_image);
    }

    $query = "INSERT INTO cars (name, model, daily_rate, monthly_rate, license_plate, availability_status, image, category_id, mileage, transmission, seats, fuel_type)
              VALUES ('$car_name', '$car_model', '$daily_rate', $monthly_rate,'$license_plate', 'available', '$car_image', '$category_id', '$mileage', '$transmission', '$seats', '$fuel_type')";
    if (mysqli_query($conn, $query)) {
        header("Location: admin.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['add_fdb'])) {
    $feedback_name = $_POST['feedback_name'];
    $feedback_text = $_POST['feedback_text'];

    $profile_photo = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $target_dir = "uploads/";
        $profile_photo = $target_dir . basename($_FILES["profile_photo"]["name"]);
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $profile_photo);
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO feedback (fd_name, feedback_text, profile_photo) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $feedback_name, $feedback_text, $profile_photo);
    mysqli_stmt_execute($stmt);
}

if (isset($_GET['delete_category'])) {
    $category_id = $_GET['delete_category'];
    $query = "DELETE FROM car_categories WHERE category_id = '$category_id'";
    if (mysqli_query($conn, $query)) {
        header("Location: admin.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_GET['delete_car'])) {
    $car_id = $_GET['delete_car'];
    $query = "DELETE FROM cars WHERE car_id = '$car_id'";
    if (mysqli_query($conn, $query)) {
        header("Location: admin.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_GET['delete_feedback'])) {
    $feedback_id = $_GET['delete_feedback'];
    $query = "DELETE FROM feedback WHERE feedback_id = '$feedback_id'";
    if (mysqli_query($conn, $query)) {
        header("Location: admin.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['update-car'])) {
    $car_id = $_POST['car_id'];
    $daily_rate = $_POST['daily_rate'];
    $monthly_rate = $_POST['monthly_rate'];
    $mileage = $_POST['mileage'];
    $availability_status = $_POST['availability_status'];

    $query = "UPDATE cars SET
              daily_rate = '$daily_rate',
              monthly_rate = '$monthly_rate',
              mileage = '$mileage',
              availability_status = '$availability_status'
              WHERE car_id = '$car_id'";

    mysqli_query($conn, $query);

    header("Location: admin.php");
    exit();
}

if (isset($_POST['update-cat'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    $query = "UPDATE car_categories SET
              category_name = '$category_name'
              WHERE category_id = '$category_id'";

    mysqli_query($conn, $query);

    header("Location: admin.php");
    exit();
}

$categories_query = "SELECT * FROM car_categories";
$categories_result = mysqli_query($conn, $categories_query);

$cars_query = "SELECT * FROM cars";
$cars_result = mysqli_query($conn, $cars_query);

$feedback_query = "SELECT * FROM feedback";
$feedback_result = mysqli_query($conn, $feedback_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Entries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin Panel - Manage Cars, Categories, and Feedback</h2>
        <div class="text-end mb-4">
            <a href="PHP/logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Car Category</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="category_description" name="category_description" required></textarea>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Existing Car Categories</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Category ID</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $categories_query1 = "SELECT * FROM car_categories";
                        $categories_result1 = mysqli_query($conn, $categories_query1);
                        while ($category = mysqli_fetch_assoc($categories_result1)) { ?>
                            <tr>
                                <form method="POST">
                                    <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                    <td><?php echo $category['category_id']; ?></td>
                                    <td>
                                        <input type="text" name="category_name" value="<?php echo $category['category_name']; ?>"
                                               class="form-control watch-changes" data-original="<?php echo $category['category_name']; ?>">
                                    </td>
                                    <td><?php echo $category['description']; ?></td>
                                    <td>
                                        <button type="submit" name="update-cat" class="btn btn-primary btn-sm">Update</button>
                                    </td>
                                </form>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Car</h4>
            </div>
            <div class="card-body">
                <form action="admin.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="car_name" class="form-label">Car Name</label>
                        <input type="text" class="form-control" id="car_name" name="car_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="car_model" class="form-label">Car Model</label>
                        <input type="text" class="form-control" id="car_model" name="car_model" required>
                    </div>
                    <div class="mb-3">
                        <label for="daily_rate" class="form-label">Daily Rate</label>
                        <input type="number" step="0.01" class="form-control" id="daily_rate" name="daily_rate" required>
                    </div>
                    <div class="mb-3">
                        <label for="monthly_rate" class="form-label">Monthly Rate</label>
                        <input type="number" step="0.01" class="form-control" id="monthly_rate" name="monthly_rate" required>
                    </div>
                    <div class="mb-3">
                        <label for="license_plate" class="form-label">License Plate</label>
                        <input type="text" class="form-control" id="license_plate" name="license_plate" required>
                    </div>
                    <div class="mb-3">
                        <label for="seats" class="form-label">Seats</label>
                        <input type="number" step="0.01" class="form-control" id="seats" name="seats" required>
                    </div>
                    <div class="mb-3">
                        <label for="mileage" class="form-label">Mileage</label>
                        <input type="text" class="form-control" id="mileage" name="mileage" required>
                    </div>
                    <div class="mb-3">
                        <label for="fuel_type" class="form-label">Fuel Type</label>
                        <select class="form-select" id="fuel_type" name="fuel_type" required>
                            <option value="diesel">Diesel</option>
                            <option value="petrol">Petrol</option>
                            <option value="electric">Electric</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="transmission" class="form-label">Transmission Type</label>
                        <select class="form-select" id="transmission" name="transmission" required>
                            <option value="automatic">Automatic</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="car_image" class="form-label">Car Image</label>
                        <input type="file" class="form-control" id="car_image" name="car_image">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <?php
                            while ($category = mysqli_fetch_assoc($categories_result)) {
                                echo "<option value='" . $category['category_id'] . "'>" . $category['category_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="add_car" class="btn btn-primary">Add Car</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Existing Cars</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Car Name</th>
                            <th>Model</th>
                            <th>Daily Rate</th>
                            <th>Monthly Rate</th>
                            <th>Mileage</th>
                            <th>Availability</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($car = mysqli_fetch_assoc($cars_result)) { ?>
                            <form method="POST">
                                <tr>
                                    <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                    <td><?php echo $car['name']; ?></td>
                                    <td><?php echo $car['model']; ?></td>
                                    <td><input type="number" name="daily_rate" value="<?php echo $car['daily_rate']; ?>" class="form-control"></td>
                                    <td><input type="number" name="monthly_rate" value="<?php echo $car['monthly_rate']; ?>" class="form-control"></td>
                                    <td><input type="number" name="mileage" value="<?php echo $car['mileage']; ?>" class="form-control"></td>
                                    <td>
                                        <select name="availability_status" class="form-control">
                                            <option value="available" <?php echo $car['availability_status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                                            <option value="maintenance" <?php echo $car['availability_status'] == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                        </select>
                                    </td>
                                    <td>
                                        <?php if ($car['image']) { ?>
                                            <img src="<?php echo $car['image']; ?>" width="100" alt="Car Image">
                                        <?php } else { ?>
                                            No Image
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <button type="submit" name="update-car" class="btn btn-primary btn-sm">Update</button>
                                        <a href="admin.php?delete_car=<?php echo $car['car_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            </form>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Feedback</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="feedback_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="feedback_name" name="feedback_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                    </div>
                    <div class="mb-3">
                        <label for="feedback_text" class="form-label">Feedback</label>
                        <textarea class="form-control" id="feedback_text" name="feedback_text" required></textarea>
                    </div>
                    <button type="submit" name="add_fdb" class="btn btn-primary">Add Feedback</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Customer Feedback</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Feedback ID</th>
                            <th>Name</th>
                            <th>Feedback</th>
                            <th>Profile Photo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($feedback = mysqli_fetch_assoc($feedback_result)) { ?>
                            <tr>
                                <td><?php echo $feedback['feedback_id']; ?></td>
                                <td><?php echo $feedback['fd_name']; ?></td>
                                <td><?php echo $feedback['feedback_text']; ?></td>
                                <td>
                                    <?php if ($feedback['profile_photo']) { ?>
                                        <img src="<?php echo $feedback['profile_photo']; ?>" width="100" alt="Profile Photo">
                                    <?php } else { ?>
                                        No Photo
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="admin.php?delete_feedback=<?php echo $feedback['feedback_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

   
        <div class="card mb-4">
            <div class="card-header">
                <h4>View Active Rentals</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="car_id" class="form-label">Select Car</label>
                        <select class="form-select" id="car_id" name="car_id" required>
                            <option value="">Select a car</option>
                            <?php
                            $cars_query = "SELECT car_id, name FROM cars";
                            $cars_result = mysqli_query($conn, $cars_query);
                            while ($car = mysqli_fetch_assoc($cars_result)) {
                                echo "<option value='" . $car['car_id'] . "'>" . $car['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="view_rentals" class="btn btn-primary">View Rentals</button>
                </form>

                <?php if (isset($_POST['view_rentals'])): ?>
                    <?php
                    $car_id = $_POST['car_id'];
                    $rentals_query = "SELECT rb.booking_id, c.image, c.name, c.license_plate, rb.start_datetime, rb.end_datetime, rb.total_cost, rb.booking_status
                                      FROM rental_bookings rb
                                      JOIN cars c ON rb.car_id = c.car_id
                                      WHERE rb.car_id = ? AND rb.booking_status = 'active'
                                      ORDER BY rb.start_datetime DESC";
                    $stmt = $conn->prepare($rentals_query);
                    if ($stmt === false) {
                        die("Prepare failed: " . $conn->error);
                    }
                    $stmt->bind_param("i", $car_id);
                    $stmt->execute();
                    $rentals_result = $stmt->get_result();
                    ?>

                    <table class="table table-bordered table-hover mt-4">
                        <thead class="thead-dark">
                            <tr>
                                <th>Car Image</th>
                                <th>Car Name</th>
                                <th>License Plate</th>
                                <th>Rent Date</th>
                                <th>Rent End Date</th>
                                <th>Cost</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $rentals_result->fetch_assoc()): ?>
                                <tr>
                                    <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-thumbnail" width="100"></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['license_plate']); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['start_datetime']))); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['end_datetime']))); ?></td>
                                    <td><?php echo htmlspecialchars($row['total_cost']); ?></td>
                                    <td><?php echo htmlspecialchars($row['booking_status']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
