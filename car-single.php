<?php
include 'php/dbconfig.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No car ID provided.");
}
$car_id = intval($_GET['id']);

// Fetch main car record (which now includes daily_rate)
$sql = "SELECT * FROM cars WHERE id = $car_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Car not found.");
}
$car = $result->fetch_assoc();

// Fetch additional images for this car using the correct foreign key "car_id"
$sql_addimage = "SELECT * FROM car_images WHERE car_id = $car_id";
$result_addImage = $conn->query($sql_addimage);
$additional_images = [];
if ($result_addImage && $result_addImage->num_rows > 0) {
    while ($row = $result_addImage->fetch_assoc()) {
        $additional_images[] = $row;
    }
}

// Retrieve the daily rate directly from the car record.
$daily_rate = (!empty($car['daily_rate'])) ? $car['daily_rate'] : "N/A";

// Function to generate options for half‑hour increments
function generateTimeOptions() {
    $options = "";
    for ($hour = 0; $hour < 24; $hour++) {
        for ($minute = 0; $minute < 60; $minute += 30) {
            $time = sprintf("%02d:%02d", $hour, $minute);
            $options .= "<option value=\"$time\">$time</option>";
        }
    }
    return $options;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/calendar.css">
    <title>Car Details - <?php echo $car['make'] . " " . $car['model']; ?></title>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include 'header.php'; ?>
        <!-- Subheader Section Begin -->
        <section id="subheader" class="jarallax text-light">
            <img src="images/background/2.jpg" class="jarallax-img" alt="">
            <div class="center-y relative text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1>Book Your Car</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Subheader Section End -->

        <!-- Car Details Section Begin -->
        <section id="section-car-details">
            <div class="container">
                <div class="row g-5">
                    <!-- Slider Column -->
                    <div class="col-lg-6">
                        <div id="slider-carousel" class="owl-carousel">
                            <div class="item">
                                <img src="<?php echo $car['display_image']; ?>" alt="">
                            </div>
                            <?php if (!empty($additional_images)): ?>
                                <?php foreach ($additional_images as $img): ?>
                                    <div class="item">
                                        <img src="<?php echo $img['image_path']; ?>" alt="">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="item">
                                    <img src="images/default-car.jpg" alt="Default Image">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Car Info & Specifications Column -->
                    <div class="col-lg-3">
                        <h3><?php echo $car['make'] . " " . $car['model'] . " " . $car['year']; ?></h3>
                        <p><?php echo "Experience the excellence of " . $car['make'] . " " . $car['model'] . ". "; ?></p>
                        <div class="spacer-10"></div>
                        <h4>Specifications</h4>
                        <div class="de-spec">
                            <div class="d-row">
                                <span class="d-title">Category</span>
                                <span class="d-value"><?php echo $car['category']; ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Seat</span>
                                <span class="d-value"><?php echo $car['seaters']; ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Door</span>
                                <span class="d-value"><?php echo $car['num_doors']; ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Fuel Type</span>
                                <span class="d-value"><?php echo ucfirst($car['runs_on_gas']); ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Year</span>
                                <span class="d-value"><?php echo $car['year']; ?></span>
                            </div>
                            <?php if (trim(strtolower($car['runs_on_gas'])) !== 'battery'): ?>
                            <div class="d-row">
                                <span class="d-title">MPG</span>
                                <span class="d-value"><?php echo sprintf('%g', $car['mpg']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="spacer-single"></div>
                    </div>

                    <!-- Price & Booking Form Column -->
                    <div class="col-lg-3">
                        <div class="de-price text-center">
                            Daily rate
                            <h3><?php echo ($daily_rate !== "N/A" ? "$" . number_format($daily_rate, 2) : "N/A"); ?></h3>
                        </div>

                        <div>
                            <div id="calendar" class="calendar">
                                <div class="calendar__opts">
                                    <select name="calendar__month" id="calendar__month">
                                    <option selected>Jan</option>
                                    <option>Feb</option>
                                    <option>Mar</option>
                                    <option>Apr</option>
                                    <option>May</option>
                                    <option>Jun</option>
                                    <option>Jul</option>
                                    <option>Aug</option>
                                    <option>Sep</option>
                                    <option>Oct</option>
                                    <option>Nov</option>
                                    <option>Dec</option>
                                    </select>

                                    <select name="calendar__year" id="calendar__year">
                                    <option selected>2025</option>
                                    <option>2026</option>
                                    <option>2027</option>
                                    <option>2028</option>
                                    </select>
                                </div>

                                <div class="calendar__body">
                                    <div class="calendar__days">
                                    <div>S</div>
                                    <div>M</div>
                                    <div>T</div>
                                    <div>W</div>
                                    <div>T</div>
                                    <div>F</div>
                                    <div>S</div>

                                    </div>

                                    <div class="calendar__dates" id="calendar__dates">
                                    </div>
                                </div>

                                    <div class="calendar__buttons">
                                        <button class="calendar__button calendar__button--grey" id="calendar__back">Back</button>
                                        <button class="calendar__button calendar__button--primary" id="calendar__apply">Book Now</button>
                                    </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
        <!-- Car Details Section End -->

        <!-- Content close -->
        <a href="#" id="back-to-top"></a>
    </div>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="process_booking.php" method="post" id="bookingModalForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bookingModalLabel">Enter Your Booking Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Hidden fields for car and user id -->
                            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                            <input type="hidden" name="user_id" value="1">
                            <input type="hidden" name="pickup_date" id="pickup_date">
                            <input type="hidden" name="return_date" id="return_date">
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="PickUpDateModal" class="form-label">Pick Up Date</label>
                                <input type="date" class="form-control" name="PickUpDate" id="PickUpDateModal" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="pickup_time_modal" class="form-label">Pick Up Time</label>
                                <div class="input-group">
                                    <select class="form-select" id="pickup_time_modal" name="pickup_time" required>
                                    </select>
                                </div>
                            </div>
                            

                            <div class="mb-3">
                                <label for="ReturnDateModal" class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="ReturnDate" id="ReturnDateModal" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="return_time_modal" class="form-label">Return Time</label>
                                <div class="input-group">
                                    <select class="form-select" id="return_time_modal" name="return_time" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    <!-- Javascript Files -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.0/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const car_id = <?php echo $car_id; ?>; 
            const calendarDates = document.getElementById('calendar__dates');
            const monthSelect = document.getElementById('calendar__month');
            const yearSelect = document.getElementById('calendar__year');
            
            let currentMonth = new Date().getMonth(); 
            let currentYear = new Date().getFullYear();
            let startDate = null;
            let endDate = null;
            let pickupTime = null;
            let returnTime = null;

            // Generate time options dynamically
            function generateTimeOptions() {
                const options = [];
                for (let hour = 0; hour < 24; hour++) {
                    for (let minute = 0; minute < 60; minute += 30) {
                        const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                        options.push(`<option value="${time}">${time}</option>`);
                    }
                }
                return options.join('');
            }

            // Populate time dropdowns
            document.getElementById('pickup_time_modal').innerHTML = generateTimeOptions();
            document.getElementById('return_time_modal').innerHTML = generateTimeOptions();

            // Fetch booked dates for the car
            fetch('get_bookings.php?car_id=' + car_id)
                .then(response => response.json())
                .then(data => {
                    renderCalendar(data);
                })
                .catch(error => {
                    console.error('Error fetching booked dates:', error);
                });

            // Function to render the calendar with available and booked dates
            function renderCalendar(bookedDates) {
                const firstDay = new Date(currentYear, currentMonth, 1); 
                const lastDay = new Date(currentYear, currentMonth + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDay = firstDay.getDay();

                let calendarHTML = '';

                for (let i = 0; i < startingDay; i++) {
                    calendarHTML += `<div class="calendar__date empty"></div>`;
                }

                // Generate each day of the month
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(currentYear, currentMonth, day);
                    const dateStr = date.toISOString().split('T')[0]; // Format date as 'YYYY-MM-DD'
                    const isBooked = bookedDates.some(booking => {
                        const bookingStart = new Date(booking.start);
                        const bookingEnd = new Date(booking.end);
                        return date >= bookingStart && date <= bookingEnd;
                    });

                    calendarHTML += `
                        <div class="calendar__date ${isBooked ? 'calendar__date--disabled' : ''}" data-date="${dateStr}">
                            <span>${day}</span>
                        </div>
                    `;
                }
                calendarDates.innerHTML = calendarHTML;

                // Add event listeners for date selection
                const dateElements = document.querySelectorAll('.calendar__date');
                dateElements.forEach(dateEl => {
                    dateEl.addEventListener('click', function () {
                        if (!dateEl.classList.contains('calendar__date--disabled')) {
                            if (!startDate) {
                                startDate = dateEl.dataset.date;
                                dateEl.classList.add('calendar__date--selected');
                                dateEl.style.backgroundColor = '#ccc';
                                disablePreviousDates(startDate);
                            } else if (!endDate && dateEl.dataset.date > startDate) {
                                endDate = dateEl.dataset.date;
                                dateEl.classList.add('calendar__date--selected');
                                dateEl.style.backgroundColor = '#ccc'; 
                                autoSelectDatesInRange(startDate, endDate); 
                            } else if (endDate && dateEl.dataset.date === endDate) {
                                endDate = null;
                                dateEl.classList.remove('calendar__date--selected');
                                dateEl.style.backgroundColor = ''; 
                            } else if (!endDate && dateEl.dataset.date < startDate) {
                                
                                startDate = null;
                                dateEl.classList.remove('calendar__date--selected');
                                dateEl.style.backgroundColor = ''; 
                            }
                        }
                    });
                });
            }

            // Function to disable previous dates before the selected start date (pickup date)
            function disablePreviousDates(startDate) {
                const dateElements = document.querySelectorAll('.calendar__date');
                dateElements.forEach(dateEl => {
                    const currentDate = new Date(dateEl.dataset.date);
                    const pickupDate = new Date(startDate);
                    if (currentDate < pickupDate) {
                        dateEl.classList.add('calendar__date--disabled');
                        dateEl.style.pointerEvents = 'none';
                    }
                });
            }

            // Function to auto-select all dates between pickup and return dates
            function autoSelectDatesInRange(startDate, endDate) {
                const dateElements = document.querySelectorAll('.calendar__date');
                dateElements.forEach(dateEl => {
                    const currentDate = new Date(dateEl.dataset.date);
                    const pickupDate = new Date(startDate);
                    const returnDate = new Date(endDate);

                    // If the date is between the pickup and return date, mark it as selected
                    if (currentDate >= pickupDate && currentDate <= returnDate) {
                        dateEl.classList.add('calendar__date--selected');
                        dateEl.style.backgroundColor = '#ccc';
                    }
                });
            }

            // Update the calendar when the month or year is changed
            monthSelect.addEventListener('change', function () {
                currentMonth = monthSelect.selectedIndex;
                renderCalendar([]);
            });

            yearSelect.addEventListener('change', function () {
                currentYear = parseInt(yearSelect.value, 10);
                renderCalendar([]);
            });

            // Populate the months and years dynamically
            const months = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
            const currentYearOptions = [2025, 2026, 2027, 2028];

            // Populate the month dropdown
            months.forEach((month, index) => {
                const option = document.createElement("option");
                option.textContent = month;
                monthSelect.appendChild(option);
            });
            monthSelect.selectedIndex = currentMonth;

            // Populate the year dropdown
            currentYearOptions.forEach(year => {
                const option = document.createElement("option");
                option.textContent = year;
                yearSelect.appendChild(option);
            });
            yearSelect.value = currentYear;

            // Function to apply the selected dates and times to the booking form when "Book Now" is clicked
            document.querySelector('#calendar__apply').addEventListener('click', function () {
                if (startDate && endDate) {
                    document.getElementById('pickup_date').value = startDate;
                    document.getElementById('return_date').value = endDate;

                    document.getElementById('PickUpDateModal').value = startDate.split('T')[0];
                    document.getElementById('ReturnDateModal').value = endDate.split('T')[0];

                    var myModal = new bootstrap.Modal(document.getElementById('bookingModal'));
                    myModal.show();
                } else {
                    alert('Please select a valid date range.');
                }
            });

            // Function to go back to car fleet page
            document.getElementById('calendar__back').addEventListener('click', function () {
                window.location.href = "cars.php"; 
            });

        });
    </script>

</body>
</html>
