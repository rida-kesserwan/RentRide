<?php
session_start();
include 'PHP/connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>RentRide - Home</title>
    <?php include "./elements/header.php"; ?>
</head>

<body>

    <?php include "./elements/nav.php"?>

    <div class="hero-wrap ftco-degree-bg" style="background-image: url('images/bg_1.jpg');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                <div class="col-lg-8 ftco-animate">
                    <div class="text w-100 text-center mb-md-5 pb-md-5">
                        <h1 class="mb-4">Fast &amp; Easy Way To Rent A Car</h1>
                        <p style="font-size: 18px;">A small river named Duden flows by their place and supplies it with
                            the necessary regelialia. It is a paradisematic country, in which roasted parts</p>

                    </div>
                </div>
            </div>
        </div>
    </div>




    <section class="ftco-section ftco-no-pt bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 heading-section text-center ftco-animate mb-5">
                    <span class="subheading">What we offer</span>
                    <h2 class="mb-2">Feeatured Vehicles</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="carousel-car owl-carousel">
                        <?php
							$query = "SELECT * FROM cars WHERE availability_status = 'available' ORDER BY RAND() LIMIT 5";
							$result = mysqli_query($conn, $query);

							while ($car = mysqli_fetch_assoc($result)) {
  								 echo '<div class="item">';
								 echo '<div class="car-wrap rounded ftco-animate">';
								 echo '<div class="img rounded d-flex align-items-end" style="background-image: url(' . $car['image'] . ');">';
								 echo '</div>';
								 echo '<div class="text">';
								 echo '<h2 class="mb-0"><a href="#">' . $car['name'] . '</a></h2>';
								 echo '<div class="d-flex mb-3">';
								 echo '<span class="cat">' . $car['model'] . '</span>';
								 echo '<p class="price ml-auto">$' . $car['daily_rate'] . ' <span>/day</span></p>';
								 echo '</div>';
								 echo '<p class="d-flex mb-0 d-block"><a href="car-single.php?id=' . $car['car_id'] . '" class="btn btn-secondary py-2 ml-1">Details</a></p>';
								 echo '</div>';
								 echo '</div>';
								 echo '</div>';
							}
						?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-about">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-6 p-md-5 img img-2 d-flex justify-content-center align-items-center"
                    style="background-image: url(images/about.jpg);">
                </div>
                <div class="col-md-6 wrap-about ftco-animate">
                    <div class="heading-section heading-section-white pl-md-5">
                        <span class="subheading">About us</span>
                        <h2 class="mb-4">Welcome to RentRide</h2>

                        <p>RentRide is a leading online car renting company that revolutionizes the way you travel. With
                            a user-friendly platform, RentRide offers a wide range of vehicles to suit every need, from
                            compact cars for city driving to luxurious SUVs for family vacations. Our seamless booking
                            process allows you to reserve a car in just a few clicks, ensuring convenience and
                            flexibility. Whether you need a car for a day, a week, or longer, RentRide provides
                            competitive rates and exceptional customer service. Experience the freedom of the open road
                            with RentRide—your trusted partner for all your car rental needs.</p>
                        <p><a href="#" class="btn btn-primary py-3 px-4">Search Vehicle</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Services</span>
                    <h2 class="mb-3">Our Latest Services</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-wedding-car"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">Wedding Ceremony</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary
                                regelialia.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-transportation"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">City Transfer</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary
                                regelialia.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-car"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">Airport Transfer</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary
                                regelialia.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-transportation"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">Whole City Tour</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary
                                regelialia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section testimony-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Testimonial</span>
                    <h2 class="mb-3">Happy Clients</h2>
                </div>
            </div>
            <div class="row ftco-animate">
                <div class="col-md-12">
                    <div class="carousel-testimony owl-carousel ftco-owl">
						<?php
							$query = "SELECT * FROM feedback";
							$result = mysqli_query($conn, $query);

							while ($feedback = mysqli_fetch_assoc($result)) {
							echo '<div class="item">';
							echo '<div class="testimony-wrap rounded text-center py-4 pb-5">';
							echo '<div class="user-img mb-2" style="background-image: url(' . $feedback['profile_photo'] . ')">';
							echo '</div>';
							echo '<div class="text pt-4">';
							echo '<p class="mb-4">' . $feedback['feedback_text'] . '</p>';
							echo '<p class="name">' . $feedback['fd_name'] . '</p>';
							echo '</div>';
							echo '</div>';
							echo '</div>';  
							}
						?>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <?php include "./elements/footer.php"?>
    <?php include "./elements/scriptloader.php"?>
</body>

</html>