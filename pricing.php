<?php
session_start();
include 'PHP/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>RentRide - Pricing</title>
    <?php include "./elements/header.php"?>
  </head>
  <body>
    
	  <?php include "./elements/nav.php"?>
    
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
          <div class="col-md-9 ftco-animate pb-5">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home ></a></span> <span>Pricing ></span></p>
            <h1 class="mb-3 bread">Pricing</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section ftco-cart">
			<div class="container">
				<div class="row">
    			<div class="col-md-12 ftco-animate">
    				<div class="car-list">
	    				<table class="table">
						    <thead class="thead-primary">
						      <tr class="text-center">
						        <th>&nbsp;</th>
						        <th>&nbsp;</th>
						        <th class="bg-primary heading">Per Day Rate</th>
						        <th class="bg-dark heading">Per Month Rate</th>
						      </tr>
						    </thead>
						    <tbody>
							<?php
								$query = "SELECT * FROM cars";
								$result = mysqli_query($conn, $query);

								while ($car = mysqli_fetch_assoc($result)) {
								echo '<tr class="">';
								echo '<td class="car-image"><div class="img" style="background-image:url(' . $car['image'] . ');"></div></td>';
								echo '<td class="product-name">';
								echo '<h3>' . $car['name'] . '</h3>';
								echo '</td>';
								
								echo '<td class="price">';
								echo '<p class="btn-custom"><a href="car-single.php?id=' . $car['car_id'] . '">Rent a car</a></p>';
								echo '<div class="price-rate">';
								echo '<h3>';
								echo '<span class="num"><small class="currency">$</small> ' . $car['daily_rate'] . '</span>';
								echo '<span class="per">/per day</span>';
								echo '</h3>';
								echo '</div>';
								echo '</td>';
								
								echo '<td class="price">';
								echo '<p class="btn-custom"><a href="car-single.php?id=' . $car['car_id'] . '">Rent a car</a></p>';
								echo '<div class="price-rate">';
								echo '<h3>';
								echo '<span class="num"><small class="currency">$</small> ' . $car['monthly_rate'] . '</span>';
								echo '<span class="per">/per month</span>';
								echo '</h3>';
								echo '</div>';
								echo '</td>';
								
								echo '</tr>';
								}
							?>


						      
						    </tbody>
						  </table>
					  </div>
    			</div>
    		</div>
			</div>
		</section>


    <?php include "./elements/footer.php"?>
    
  

	<?php include "./elements/scriptloader.php"?>
  </body>
</html>