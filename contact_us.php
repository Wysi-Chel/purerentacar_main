<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Us</title>
    <?php include 'head.php'; ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div id="wrapper">
        <!-- Page Preloader -->
        <div id="de-preloader"></div>
        <?php include 'header.php'; ?>

        <div class="no-bottom no-top" id="content">
            <div id="top"></div>

            <!-- Subheader Section -->
            <section id="subheader" class="jarallax text-light">
                <img src="images/background/subheader.jpg" class="jarallax-img" alt="">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1>Contact Us</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Subheader -->

            <!-- Main Section -->
            <section aria-label="section">
                <div class="container">
                    <div class="row g-custom-x">
                        <div class="col-lg-8 mb-sm-30">
                            <h3>Do you have any question?</h3>
                            <!-- Contact Form -->
                            <form name="contactForm" id="contact_form" class="form-border" method="post" action="send_email.php">
                                <div class="row">
                                    <div class="col-md-4 mb10">
                                        <div class="field-set">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Your Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb10">
                                        <div class="field-set">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb10">
                                        <div class="field-set">
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Your Phone" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="field-set mb20">
                                    <textarea name="message" id="message" class="form-control" placeholder="Your Message" required></textarea>
                                </div>
                                <div id="submit" class="mt20">
                                    <input type="submit" id="send_message" value="Send Message" class="btn-main">
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-4">
                            <div class="de-box mb30">
                                <h4>US Office</h4>
                                <address class="s1">        
                                    <span><i class="id-color fa fa-map-marker fa-lg"></i>9521 Lumley Rd, Morrisville, NC 27560, United States</span>
                                    <span><i class="id-color fa fa-phone fa-lg"></i>+984-849-4867</span>
                                    <span><i class="id-color fa fa-envelope-o fa-lg"></i><a href="mailto:chello@purerentacar.com">hello@purerentacar.com</a></span>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Main Section -->
        </div>
        <!-- End Content -->

        <a href="#" id="back-to-top"></a>
        <?php include 'footer.php'; ?>
    </div>

    <div id="contactModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Contact Form Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>


    <!-- AJAX Form Submission Script -->
    <script>
        $(document).ready(function() {
            $('#contact_form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Disable the send button and change text to "Sending..."
                var $sendBtn = $('#send_message');
                $sendBtn.prop('disabled', true);
                var originalText = $sendBtn.val();
                $sendBtn.val('Sending...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalMessage').html(response);
                        $('#contactModal').modal('show');
                    },
                    error: function() {
                        $('#modalMessage').html('An error occurred. Please try again.');
                        $('#contactModal').modal('show');
                    },
                    complete: function() {
                        // Re-enable the button and restore its text
                        $sendBtn.prop('disabled', false);
                        $sendBtn.val(originalText);
                    }
                });
            });
        });
    </script>
</body>

</html>