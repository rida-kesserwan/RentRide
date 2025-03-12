<?php
session_start();
include 'PHP/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>RentRide - View Cars</title>
    <?php include "./elements/header.php" ?>
  </head>
  <body>
    
	  <?php include "./elements/nav.php"?>
    
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
          <div class="col-md-9 ftco-animate pb-5">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home ></a></span> <span>Cars ></span></p>
            <h1 class="mb-3 bread">Choose Your Car</h1>
          </div>
        </div>
      </div>
    </section>
		

		<section class="ftco-section bg-light" >
    	<div class="container">
    		<div class="row">
			<?php
				$query = "SELECT * FROM cars";
				$result = mysqli_query($conn, $query);

				while ($car = mysqli_fetch_assoc($result)) {
				echo '<div class="col-md-4">';
				echo '<div class="car-wrap rounded ftco-animate">';
				echo '<div class="img rounded d-flex align-items-end" style="background-image: url(' . $car['image'] . ');">';
				echo '</div>';
				echo '<div class="text">';
				echo '<h2 class="mb-0"><a href="car-single.php?id=' . $car['car_id'] . '">' . $car['name'] . '</a></h2>';
				echo '<div class="d-flex mb-3">';
				echo '<span class="cat">' . $car['model'] . '</span>';
				echo '<p class="price ml-auto">$' . $car['daily_rate'] . ' <span>/day</span></p>';
				echo '</div>';
				echo '<a href="car-single.php?id=' . $car['car_id'] . '" class="btn btn-secondary py-2 ml-1">Details</a>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				}
			?>
    		</div>
    </section>
    

    <?php include "./elements/footer.php"?>
    
  


	<?php include "./elements/scriptloader.php"?> 
  </body>
</html>