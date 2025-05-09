

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Booking Section</title>
    <link rel="icon" href="images/rel-icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Pure Rental Group Webpage" name="description">
    <meta content="" name="keywords">
    <meta content="" name="author">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="css/mdb.min.css" rel="stylesheet" type="text/css" id="mdb">
    <link href="css/plugins.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/coloring.css" rel="stylesheet" type="text/css">
    <!-- color scheme -->
    <link id="colors" href="css/colors/scheme-07.css" rel="stylesheet" type="text/css">
</head>

<body onload="initialize()">
    <div id="wrapper">

        <!-- page preloader begin -->
        <div id="de-preloader"></div>
        <!-- page preloader close -->

        <?php include 'header.php'; ?>

        <!-- content begin -->
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>

            <!-- section begin -->
            <section id="subheader" class="jarallax text-light">
                <img src="images/background/subheader.jpg" class="jarallax-img" alt="">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1>Quick Booking</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->

            <section id="section-hero" aria-label="section" class="no-top" data-bgcolor="#121212">
                <div class="container">
                    <div class="row align-items-center">

                        <div class="col-lg-12 mt-80 sm-mt-0">
                            <div class="spacer-single sm-hide"></div>
                            <div id="booking_form_wrap" class="padding40 rounded-5 shadow-soft" data-bgcolor="#ffffff">


                                <form name="contactForm" id='booking_form' class="form-s2 row g-4 on-submit-hide" method="post" action="booking.php">

                                    <div class="col-lg-6 d-light">
                                        <h4>Booking a Car</h4>
                                        <select name='vehicle_type' id="vehicle_type" class="form-control">
                                            <option value='Jeep Renegade' data-src="images/cars-alt/jeep-renegade.png">Jeep Renegade - $265</option>
                                            <option value='BMW M5' data-src="images/cars-alt/bmw-m5.png">BMW M5 - $544</option>
                                            <option value='Ferrari Enzo' data-src="images/cars-alt/ferrari-enzo.png">Ferrari Enzo - $167</option>
                                            <option value='Ford Raptor' data-src="images/cars-alt/ford-raptor.png">Ford Raptor - $147</option>
                                            <option value='Mini Cooper' data-src="images/cars-alt/mini-cooper.png">Mini Cooper - $238</option>
                                            <option value='Cheverolet Camaro' data-src="images/cars-alt/vw-polo.png">Cheverolet Camaro - $245</option>
                                            <option value='Hyundai Staria' data-src="images/cars-alt/hyundai-staria.png">Hyundai Staria - $191</option>
                                            <option value='Toyota Rav 4' data-src="images/cars-alt/toyota-rav.png">Toyota Rav 4 - $114</option>
                                            <option value='Bentley' data-src="images/cars-alt/bentley.png">Bentley - $299</option>
                                            <option value='Lexus' data-src="images/cars-alt/lexus.png">Lexus - $131</option>
                                            <option value='Range Rover' data-src="images/cars-alt/range-rover.png">Range Rover - $228</option>
                                        </select>

                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <h5>Upload Driver's License</h5>
                                                <?php if (!empty($car['display_image'])): ?>
                                                    <img src="<?php echo $car['display_image']; ?>" alt="Current Display Image" class="current-img">
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="drivers_license" id="drivers_license" accept=".jpg, .jpeg, .png, .pdf" required>
                                            </div>

                                            <div class="col-lg-6">
                                                <h5>Upload Insurance Card</h5>
                                                <?php if (!empty($car['display_image'])): ?>
                                                    <img src="<?php echo $car['display_image']; ?>" alt="Current Display Image" class="current-img">
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="insurance_card" id="insurance_card" accept=".jpg, .jpeg, .png, .pdf" required>
                                            </div>

                                            <div class="col-lg-6">
                                                <h5>Pick Up Date & Time</h5>
                                                <div class="date-time-field">
                                                    <input type="text" id="date-picker" name="pickup_date" value="">
                                                    <select name="pickup_time" id="pickup_time">
                                                        <option value="00:00">00:00</option>
                                                        <option value="00:30">00:30</option>
                                                        <option value="01:00">01:00</option>
                                                        <option value="01:30">01:30</option>
                                                        <option value="02:00">02:00</option>
                                                        <option value="02:30">02:30</option>
                                                        <option value="03:00">03:00</option>
                                                        <option value="03:30">03:30</option>
                                                        <option value="04:00">04:00</option>
                                                        <option value="04:30">04:30</option>
                                                        <option value="05:00">05:00</option>
                                                        <option value="05:30">05:30</option>
                                                        <option value="06:00">06:00</option>
                                                        <option value="06:30">06:30</option>
                                                        <option value="07:00">07:00</option>
                                                        <option value="07:30">07:30</option>
                                                        <option value="08:00">08:00</option>
                                                        <option value="08:30">08:30</option>
                                                        <option value="09:00">09:00</option>
                                                        <option value="09:30">09:30</option>
                                                        <option value="10:00">10:00</option>
                                                        <option value="10:30">10:30</option>
                                                        <option value="11:00">11:00</option>
                                                        <option value="11:30">11:30</option>
                                                        <option value="12:00">12:00</option>
                                                        <option value="12:30">12:30</option>
                                                        <option value="13:00">13:00</option>
                                                        <option value="13:30">13:30</option>
                                                        <option value="14:00">14:00</option>
                                                        <option value="14:30">14:30</option>
                                                        <option value="15:00">15:00</option>
                                                        <option value="15:30">15:30</option>
                                                        <option value="16:00">16:00</option>
                                                        <option value="16:30">16:30</option>
                                                        <option value="17:00">17:00</option>
                                                        <option value="17:30">17:30</option>
                                                        <option value="18:00">18:00</option>
                                                        <option value="18:30">18:30</option>
                                                        <option value="19:00">19:00</option>
                                                        <option value="19:30">19:30</option>
                                                        <option value="20:00">20:00</option>
                                                        <option value="20:30">20:30</option>
                                                        <option value="21:00">21:00</option>
                                                        <option value="21:30">21:30</option>
                                                        <option value="22:00">22:00</option>
                                                        <option value="22:30">22:30</option>
                                                        <option value="23:00">23:00</option>
                                                        <option value="23:30">23:30</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <h5>Return Date & Time</h5>
                                                <div class="date-time-field">
                                                    <input type="text" id="date-picker-2" name="return_date" value="">
                                                    <select name="return_time" id="return_time">
                                                        <option value="00:00">00:00</option>
                                                        <option value="00:30">00:30</option>
                                                        <option value="01:00">01:00</option>
                                                        <option value="01:30">01:30</option>
                                                        <option value="02:00">02:00</option>
                                                        <option value="02:30">02:30</option>
                                                        <option value="03:00">03:00</option>
                                                        <option value="03:30">03:30</option>
                                                        <option value="04:00">04:00</option>
                                                        <option value="04:30">04:30</option>
                                                        <option value="05:00">05:00</option>
                                                        <option value="05:30">05:30</option>
                                                        <option value="06:00">06:00</option>
                                                        <option value="06:30">06:30</option>
                                                        <option value="07:00">07:00</option>
                                                        <option value="07:30">07:30</option>
                                                        <option value="08:00">08:00</option>
                                                        <option value="08:30">08:30</option>
                                                        <option value="09:00">09:00</option>
                                                        <option value="09:30">09:30</option>
                                                        <option value="10:00">10:00</option>
                                                        <option value="10:30">10:30</option>
                                                        <option value="11:00">11:00</option>
                                                        <option value="11:30">11:30</option>
                                                        <option value="12:00">12:00</option>
                                                        <option value="12:30">12:30</option>
                                                        <option value="13:00">13:00</option>
                                                        <option value="13:30">13:30</option>
                                                        <option value="14:00">14:00</option>
                                                        <option value="14:30">14:30</option>
                                                        <option value="15:00">15:00</option>
                                                        <option value="15:30">15:30</option>
                                                        <option value="16:00">16:00</option>
                                                        <option value="16:30">16:30</option>
                                                        <option value="17:00">17:00</option>
                                                        <option value="17:30">17:30</option>
                                                        <option value="18:00">18:00</option>
                                                        <option value="18:30">18:30</option>
                                                        <option value="19:00">19:00</option>
                                                        <option value="19:30">19:30</option>
                                                        <option value="20:00">20:00</option>
                                                        <option value="20:30">20:30</option>
                                                        <option value="21:00">21:00</option>
                                                        <option value="21:30">21:30</option>
                                                        <option value="22:00">22:00</option>
                                                        <option value="22:30">22:30</option>
                                                        <option value="23:00">23:00</option>
                                                        <option value="23:30">23:30</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!-- customer details -->

                                    <div class="col-lg-6">
                                        <h4>Enter Your Details</h4>
                                        <div class="row g-4">
                                            <div class="col-lg-12">
                                                <div class="field-set">
                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Full Name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="field-set">
                                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="field-set">
                                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone Number" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="field-set">
                                                    <textarea name="message" id="message" class="form-control" placeholder="Note"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-lg-3">
                                        <p id='submit'>
                                            <input type='submit' id='send_message' value='Submit' class="btn-main btn-fullwidth">
                                        </p>
                                        <div id='mail_success' class='success'>Your message has been sent successfully.</div>
                                        <div id='mail_fail' class='error'>Sorry, error occured this time sending your message.</div>
                                    </div>
                                </form>

                                <div id="success_message" class='success s2'>
                                    <div class="row">
                                        <div class="col-lg-8 offset-lg-2 text-light text-center">
                                            <h3 class="mb-2">Congratulations! Your booking has been sent successfully. We will contact you shortly.</h3>
                                            Refresh this page if you want to booking again.
                                            <div class="spacer-20"></div>
                                            <a class="btn-main btn-black" href="quick-booking.html">Reload this page</a>
                                        </div>
                                    </div>
                                </div>
                                <div id="error_message" class='error'>
                                    Sorry there was an error sending your form.
                                </div>
                            </div>

                            <div id="success_message" class="bg-color text-light rounded-5">
                                <div class="p-5 text-center">
                                    <h3 class="mb-2">Congratulations! Your booking has been sent successfully. We will contact you shortly.</h3>
                                    <p>Refresh this page if you want to booking again.</p>
                                    <a class="btn-main bg-dark" href="">Reload this page</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="spacer-double"></div>

                    <div class="row text-light">
                        <div class="col-lg-12">
                            <div class="container-timeline">
                                <ul>
                                    <li>
                                        <h4>Choose a vehicle</h4>
                                        <p>Unlock unparalleled adventures and memorable journeys with our vast fleet of vehicles tailored to suit every need, taste, and destination.</p>
                                    </li>
                                    <li>
                                        <h4>Pick location &amp; date</h4>
                                        <p>Pick your ideal location and date, and let us take you on a journey filled with convenience, flexibility, and unforgettable experiences.</p>
                                    </li>
                                    <li>
                                        <h4>Make a booking</h4>
                                        <p>Secure your reservation with ease, unlocking a world of possibilities and embarking on your next adventure with confidence.</p>
                                    </li>
                                    <li>
                                        <h4>Sit back &amp; relax</h4>
                                        <p>Hassle-free convenience as we take care of every detail, allowing you to unwind and embrace a journey filled comfort.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
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
    <script src="js/validation-booking.js"></script>

</body>

</html>