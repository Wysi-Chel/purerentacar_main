    <?php
    // Include the database configuration file
    include 'php/dbconfig.php';

    $sql = "SELECT c.*
            FROM cars c";
    $result = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Homepage</title>
    </head>
    <?php include 'head.php'; ?>

    <body onload="initialize()" class="dark-scheme">
        <div id="wrapper">

            <div id="de-preloader"></div>
            <?php include 'header.php'; ?>

            <!-- content begin -->
            <div class="no-bottom no-top" id="content">
                <div id="top"></div>
                <section id="de-carousel" class="no-top no-bottom carousel slide carousel-fade" data-mdb-ride="carousel">

                    <!-- Inner -->
                    <div class="carousel-inner position-relative">
                        <!-- First item -->
                        <div class="carousel-item active jarallax" style="position: relative; overflow: hidden;">
                            <video class="jarallax-video" autoplay loop muted playsinline
                                style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                                <source src="images/slider/video.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="mask">
                                <div class="no-top no-bottom">
                                    <div class="h-100 v-center">
                                        <div class="container">
                                            <div class="row gx-5 align-items-center">
                                                <div class="col-lg-6 offset-lg-3 text-center mb-sm-30">
                                                    <h1 class="s3 mb-3 wow fadeInUp">Premium Cars</h1>
                                                    <p class="lead wow fadeInUp" data-wow-delay=".3s" style="color:#fff;">Top-Tier Vehicles for First-Class Adventures.</p>
                                                    <div class="spacer-10"></div>
                                                    <a class="btn-line mb10 wow fadeInUp" data-wow-delay=".6s" href="cars.php">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="de-gradient-edge-bottom"></div>
                </section>


                <section id="section-cars">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 offset-lg-3 text-center">
                                <h2>Our Fleet, Your Journey</h2>
                                <p>Bringing your driving dreams to life with a refined selection of vehicles for every adventure.</p>
                                <div class="spacer-20"></div>
                            </div>
                            <div id="items-carousel" class="owl-carousel wow fadeIn">
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <div class="col-lg-12">
                                            <div class="de-item mb30">
                                                <div class="d-img">
                                                    <!-- Display the car's official (profile) image -->
                                                    <?php if (!empty($row['display_image'])): ?>
                                                        <img src="<?php echo $row['display_image']; ?>" class="img-fluid" alt="<?php echo $row['make'] . ' ' . $row['model']; ?>">
                                                    <?php else: ?>
                                                        <img src="images/default-car.jpg" class="img-fluid" alt="No image available">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-info">
                                                    <div class="d-text">
                                                        <h4><?php echo $row['make'] . " " . $row['model']; ?></h4>
                                                        <div class="d-item_like">
                                                            <i class="fa fa-heart"></i><span><?php echo isset($row['likes']) ? $row['likes'] : '0'; ?></span>
                                                        </div>
                                                        <div class="d-atr-group">
                                                            <span class="d-atr">
                                                                <img src="images/icons/1-green.svg" alt="Seaters">
                                                                <?php echo $row['seaters']; ?>
                                                            </span>
                                                            <span class="d-atr">
                                                                <img src="images/icons/3-green.svg" alt="Doors">
                                                                <?php echo $row['num_doors']; ?>
                                                            </span>
                                                            <span class="d-atr">
                                                                <img src="images/icons/gauge.png" alt="MPG">
                                                                <?php echo sprintf("%g", $row['mpg']); ?> MPG
                                                            </span>
                                                        </div>
                                                        <div class="d-price">
                                                            Daily rate from <span>$<?php echo (!empty($row['daily_rate']) ? number_format($row['daily_rate'], 2) : "N/A"); ?></span>
                                                            <a class="btn-main" href="car-single.php?id=<?php echo $row['id']; ?>">Rent Now</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p>No cars available.</p>
                                <?php endif; ?>
                                <?php $conn->close(); ?>
                            </div>
                        </div>
                    </div>
                </section>


                <section class="text-light jarallax" aria-label="section">
                    <img src="images/background/3.jpg" alt="" class="jarallax-img">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-3">
                                <h1>Fuel Your Hustle. Handle the Unexpected.</h1>
                                <div class="spacer-20"></div>
                            </div>
                            <div class="col-md-3">
                                <i class="fa fa-trophy de-icon mb20"></i>
                                <h4>First-Class Services, Built for Real Life.</h4>
                                <p>Whether you're driving for work, picking up extra gigs, or dealing with an unexpected need,
                                    we've got your back with flexible, stress-free car rentals that move with your life.</p>
                            </div>
                            <div class="col-md-3">
                                <i class="fa fa-road de-icon mb20"></i>
                                <h4>Reliable Road Support</h4>
                                <p>If something comes up, our team is ready to assist during business hours—helping you stay on track and back to what matters.</p>
                            </div>
                            <div class="col-md-3">
                                <i class="fa fa-map-pin de-icon mb20"></i>
                                <h4>Quick Pick-Up & Easy Returns</h4>
                                <p>Pick up your ride when you're ready and drop it off when you're done—on your own time, without the wait.</p>
                            </div>
                        </div>


                    </div>

                </section>

                <section id="section-faq">
                    <div class="container">
                        <div class="row">
                            <div class="col text-center">
                                <h2>Frequenty Asked Questions</h2>
                                <div class="spacer-20"></div>
                            </div>
                        </div>
                        <div class="row g-custom-x">
                            <div class="col-md-6 wow fadeInUp">
                                <div class="accordion secondary">
                                    <div class="accordion-section">
                                        <div class="accordion-section-title" data-tab="#accordion-1">
                                            What is required for the car rental?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-1">
                                            <p>To rent a vehicle, you must be at least 21 years old and possess a valid United States driver's license.
                                            To book, simply click the <a href="index.php">Book Now</a> button or select the car you're interested in, then complete the contact form with your details. 
                                            A representative will get in touch with you promptly to confirm your reservation!
                                            </p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-2">
                                            Do I need insurance to rent a car?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-2">
                                            <p>To rent directly from us, <bold> full coverage </bold> insurance is required at no extra charge. Simply provide your insurance card for verification, and we'll quickly approve you to rent one of our rental vehicles. If you don't have full coverage insurance, we'll offer an alternative through a third-party provider, which may have higher age requirements and additional fees.</p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-3">
                                           Can I drive the car anywhere I want?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-3">
                                            <p>Vehicles rented from us cannot be driven outside the state of North Carolina without obtaining prior written approval.</p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-b-8">
                                            Do you accept cash for payment?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-b-8">
                                            <p>We accept major credit and debit cards, but cash payments are not accepted. </p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-b-10">
                                        What happens if I receive a ticket while driving the rental car?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-b-10">
                                            <p>The renter is accountable for any tickets or violations incurred during the rental period, along with any associated penalties, fees, towing charges, or impound costs. </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 wow fadeInUp">
                                <div class="accordion secondary">
                                    <div class="accordion-section">
                                        <div class="accordion-section-title" data-tab="#accordion-b-4">
                                            Can there be additional driver for the rental?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-b-4">
                                            <p>Yes, additional drivers are allowed as long as they are over 21 years old and provide a valid driver’s license along with full coverage insurance.</p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-b-5">
                                        Is there a limit to the amount of mileage I can drive?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-b-5">
                                            <p>Our rentals comes with a base rate of 100 miles per day included, however we do offer larger mileage packages at a discounted rate for those who intend to drive further distances. We also offer long-term rental packages with less included miles which allows us to provide super low pricing on extended rentals!</p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-b-6">
                                            How far can I travel with the rental?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-b-6">
                                            <p>You are limited to driving up to half of your included mileage to ensure you have enough to return to our location. If you plan to exceed the included mileage, please contact us before your trip to receive discounted rates for additional mileage. Under no circumstances should you take the vehicle outside the country. If this occurs, the vehicle will be remotely disabled, and your deposit will be fully forfeited with no refund. You will also be responsible for any costs associated with recovering the vehicle</p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-b-9">
                                        What are your business hours?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-b-9">
                                            <p>We are open Mondays to Sundays 9:00 AM to 9:00 PM</p>
                                        </div>
                                        <div class="accordion-section-title" data-tab="#accordion-b-7">
                                            Do you deliver the car to my location?
                                        </div>
                                        <div class="accordion-section-content" id="accordion-b-7">
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime at corporis reprehenderit saepe hic? Doloribus sed eligendi maiores magni tempora dicta quae facilis sit sunt quod. Natus aut ratione quo.</p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            </section>
        </div>

        <!-- content close -->
        <a href="#" id="back-to-top"></a>
        <?php include 'footer.php'; ?>
        </div>


        <!-- Javascript Files
        ================================================== -->
        <script src="js/plugins.js"></script>
        <script src="js/designesia.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgiM7ogCAA2Y5pgSk2KXZfxF5S_1jsptA&amp;libraries=places&amp;callback=initPlaces" async defer></script>

    </body>

    </html>