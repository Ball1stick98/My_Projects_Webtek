<?php
  include('dbconn.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Bracu-Travels [Reservation]</title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-woox-travel.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
  </head>
<body>
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.html" class="logo">
                        <img src="D:\xampp\htdocs\Project\assets\images\Logo_travel.png" alt="">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about.html">About</a></li>
                        <li><a href="deals.html">Deals</a></li>
                        <li><a href="reservation.html" class="active">Reservation</a></li>
                        <li><a href="reservation.html">Book Yours</a></li>
                    </ul>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->

  <div class="second-page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h4>Book Prefered Deal Here</h4>
          <h2>Make Your Reservation</h2>
          <p>Pocket friendly destinations!!!</p>
          <div class="main-button"><a href="about.html">Discover More</a></div>
        </div>
      </div>
    </div>
  </div>

  <div class="more-info reservation-info">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-sm-6">
          <div class="info-item">
            <i class="fa fa-phone"></i>
            <h4>Call us</h4>
            <a href="#">+880 12345667</a>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="info-item">
            <i class="fa fa-envelope"></i>
            <h4>Contact Us via Email</h4>
            <a href="#">brac-travel@bracu.ac.bd</a>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="info-item">
            <i class="fa fa-map-marker"></i>
            <h4>Visit Our Offices</h4>
            <a href="#">Shongibon 2, Birulia, Birulia-Akran Rd, Dhaka 1340, Bangladesh</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="reservation-form">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div id="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4014.4193512918673!2d90.30680755054654!3d23.868037090113173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c21ad0f00d4f%3A0x844c271c882dbbaa!2sBRAC%20University%20Residential%20Campus!5e1!3m2!1sen!2sbd!4v1666603545907!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
        <div class="col-lg-12">
          <form id="reservation-form" method="post" action="D:\xampp\htdocs\Project\check_reservation.php">
            <div class="row">
              <div class="col-lg-12">
                <h4>Make Your <em>Reservation</em> Through This <em>Form</em></h4>
              </div>
              <div class="col-lg-6">
                  <fieldset>
                      <label for="Name" class="form-label">Your Name</label>
                      <input type="text" name="Name" class="Name" placeholder="Full name" autocomplete="on" required>
                  </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                    <label for="Number" class="form-label">Your Phone Number</label>
                    <input type="text" name="Number" class="Number" placeholder="Ex. +880 12 3456 7890" autocomplete="on" required>
                </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                    <label for="amount" class="form-label">Number of guests</label>
                    <input type="number" name="amount" placeholder="Number of Guests" autocomplete="on" required>
                </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="email" required>
                </fieldset>
              </div>
              <div class="col-lg-12">
              <fieldset>
                  <label for="choosePackage" class="form-label">Choose Your Travel Package</label>
                  <select name="package_name" class="form-select" aria-label="Default select example" id="choosePackage">
                      <option value="">Select a Travel Package</option>
                      <?php
                      include('dbconn.php');
                      $sql = "SELECT package_id, package_name FROM travel_packages";
                      $result = mysqli_query($conn, $sql);
                      while ($row = mysqli_fetch_assoc($result)) {
                          echo "<option value='" . $row['package_id'] . "'>" . $row['package_name'] . "</option>";
                      }
                      ?>
                  </select>
              </fieldset>
          </div>
              </fieldset>
          </div>

            <div class="col-lg-12">
              <fieldset>
                <label for="choosePaymentMethod" class="form-label">Choose how you want to pay</label>
                <select name="Destination" class="form-select" aria-label="Default select example" id="chooseCategory" onChange="this.form.click()">
                    <option selected>Bkash</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Nagad">Nagad</option>
                    <option value="Bank">Bank Transfer</option>
                    <option value="AmericanExpress">American Express</option>
                    <option value="Visa">Visa</option>
                </select>
            </fieldset>


            </div>
              <div class="col-lg-12">
                  <fieldset>
                      <button class="main-button">Make Your Reservation Now</button>
                  </fieldset>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <button onclick="redirectToPaypal()">Make Your Reservation Now</button>

<script>
function redirectToPaypal() {
  // Replace the URL below with your PayPal payment page URL
  window.location.href = "C:\Users\minha\Desktop\Travel\Bracu_Travels\Payment.html";
}
</script>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>All rights reserved by<a href="https://www.facebook.com/shahidulhaque.mahi" target="blank" >@Ballistic</a>

        </div>
      </div>
    </div>
  </footer>


  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/wow.js"></script>
  <script src="assets/js/tabs.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>

  <script>
    $(".option").click(function(){
      $(".option").removeClass("active");
      $(this).addClass("active");
    });
  </script>

  </body>

</html>
