<?php
session_start();

if (isset($_SESSION['user_username'])) {
    // Retrieve the user's email from the session
    $username = $_SESSION['user_username'];

    // Database connection
    $host = 'localhost';
    $db = 'database';
    $user = 'root';
    $pass = '';

    // Create connection
    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("
    SELECT u.name, u.login_id, l.type, u.service_provider, u.area, u.contact_information
    FROM login l 
    JOIN agencies u ON l.id = u.login_id 
    WHERE l.username = ?
    ");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($name, $loginId, $category, $service_provider, $area, $contact_information);
        $stmt->fetch();
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Service Insurance Provider Dashboard</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap Icons -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.min.css" rel="stylesheet">
            <style>
                @import url(https://fonts.googleapis.com/css?family=Open+Sans:600);

                .EscrowButtonPrimary.EscrowButtonPrimary {
                    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG8AAAALCAMAAABGfiMeAAAAolBMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8ELnaCAAAANXRSTlMA95xuSysUU/vh2tWxlA4Z88Sselc1He/quriOCwbNiYFEMALm0Mm+taJnP6ZjIoRdTjp03P7kXRgAAAI+SURBVDjLhZKJkppQEEXvQ0BAZBEEHQFlUVzHZXL//9fSj1Ti1GRMTpWtJe/16faKt4EpEBqu6qoGOHpKuQbuS8GwABTJNveMDCYrAB3l8DvzFkLj/3CrA4D0zYs3BfDoDAiF5+F76CyEHtjJJ4cNjmSk6GFEjRLhnswVF2HgOAEscgJcuAdEG9F1IwVs5NsFdyVMKhvAjc4r3wQDIScBYJfYspZ3S3z7+3jJgxi6BoHPtYx01+PQxoEfgNZc9FW0kZuhPDBFyuFJTJb/9tncthBaJ4dGfFOIa4uxOIGTbLTmCmdWHMPlHILPAJqMhtQ5r7IYPX2NDF743FQoIHtNkgwIOHn6xPUmr1/NlkOAnUo5zRiVEGKFp08sa1k/5wkrqeEL38C7ZLElRWE/fa6/V7x98kmAc/Zy4iZjaHbRV9+IK57L3PX5/mo/UyggNElOM6D326e54pMPFX1usJWa4Pv9Eo53asTkQOtlfk/kYKv+5OfXE93X5AxATR9YkTxhKvUBzYw2NCHPUh8yy5XmhcoJDc7/4xs3bXHmETEvgW3VQ36WcuYIqcw266UZ7qQqZWEdXz0NkfADKBqgix76LzyWieoioi8znV74qIQd7IgOubClm8YbfLjQK6SHfuTagE32Q10CExkgXMhtKiAlO8W+Fc8D95GFNWuEyYf5l3U2sEYw6l3voEM2+x9efIU5uwC4zsay+t6L1zaE1fk21BQ4xrJXZsS7/UrHa8T+qAQ2lQXNsRJTfTK//qo/Aes/TdDnJ+8NAAAAAElFTkSuQmCC);
                    -moz-osx-font-smoothing: grayscale !important;
                    -webkit-font-smoothing: antialiased !important;
                    background-color: #0ecb6f !important;
                    background-repeat: no-repeat !important;
                    background-position: right 13px !important;
                    border-radius: 4px !important;
                    border: 1px solid #0ecb6f !important;
                    -webkit-box-shadow: 0 2px 4px 0 hsla(0, 12%, 54%, .1) !important;
                    box-shadow: 0 2px 4px 0 hsla(0, 12%, 54%, .1) !important;
                    -webkit-box-sizing: border-box !important;
                    box-sizing: border-box !important;
                    color: #fff !important;
                    cursor: pointer !important;
                    display: inline-block !important;
                    font-family: Open Sans, sans-serif !important;
                    font-size: 14px !important;
                    font-weight: 600 !important;
                    letter-spacing: .4px !important;
                    line-height: 1.2 !important;
                    min-height: 40px !important;
                    padding: 8px 118px 8px 21px !important;
                    text-align: left !important;
                    text-decoration: none !important;
                    text-shadow: none !important;
                    text-transform: none !important;
                    -webkit-transition: all .1s linear !important;
                    transition: all .1s linear !important;
                    vertical-align: middle !important
                }

                .EscrowButtonPrimary.EscrowButtonPrimary:focus,
                .EscrowButtonPrimary.EscrowButtonPrimary:hover {
                    color: #fff !important;
                    font-size: 14px !important;
                    font-weight: 600 !important;
                    outline: 0 !important;
                    text-decoration: none !important;
                    -webkit-transform: none !important;
                    transform: none !important
                }

                .EscrowButtonPrimary.EscrowButtonPrimary:hover {
                    background-color: #56da9a !important;
                    border-color: #56da9a !important
                }

                .EscrowButtonPrimary.EscrowButtonPrimary:focus {
                    background-color: #00b65a !important
                }

                /* General styles for hidden cards */
                .hidden {
                    display: none;
                }

                /* Payments & Billing Section */
                #paymentsBilling {
                    padding: 20px;
                    background-color: #f9f9f9;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }

                /* Headings */
                #paymentsBilling h2,
                #paymentsBilling h3 {
                    color: #333;
                    font-family: 'Roboto', sans-serif;
                    margin-bottom: 15px;
                }

                /* Payment forms and lists */
                #paymentsBilling ul {
                    list-style: none;
                    padding: 0;
                    margin-bottom: 20px;
                }

                #paymentsBilling ul li {
                    background-color: #fff;
                    padding: 10px;
                    border: 1px solid #ddd;
                    margin-bottom: 10px;
                    border-radius: 5px;
                }

                /* Form Styling */
                #paymentsBilling form {
                    margin-top: 20px;
                }

                #paymentsBilling label {
                    font-weight: bold;
                    color: #555;
                    display: block;
                    margin-bottom: 5px;
                }

                #paymentsBilling input[type="number"],
                #paymentsBilling input[type="date"] {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }

                #paymentsBilling button[type="submit"] {
                    background-color: #28a745;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: bold;
                }

                #paymentsBilling button[type="submit"]:hover {
                    background-color: #218838;
                }

                body {
                    font-family: 'Quicksand', sans-serif;
                    background-color: #FDF6E3;
                    color: #2C3E50;
                    margin: 0;
                    padding: 0;
                }

                #wrapper {
                    display: flex;
                    height: 100vh;
                }

                /* Updated left panel (sidebar) styling */
                #sidebar {
                    width: 250px;
                    background: linear-gradient(135deg, #FF6F61, #F9A825);
                    height: 100%;
                    color: white;
                    padding-top: 20px;
                    position: fixed;
                    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                }

                #sidebar a {
                    color: #FDF6E3;
                    text-decoration: none;
                    font-weight: 500;
                    display: block;
                    transition: background-color 0.3s ease, padding-left 0.3s ease;
                }

                #sidebar a:hover {
                    background-color: rgba(255, 255, 255, 0.1);
                    padding-left: 15px;
                }

                #sidebar .nav-link {
                    padding: 15px 20px;
                }

                #sidebar .sidebar-heading {
                    padding: 10px 20px;
                    font-size: 14px;
                    text-transform: uppercase;
                    font-weight: bold;
                    color: #FFE8D6;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
                }

                #content {
                    margin-left: 250px;
                    padding: 40px;
                    flex-grow: 1;
                    overflow-y: auto;
                    background-color: #FDF6E3;
                }

                h2 {
                    font-family: 'Playfair Display', serif;
                    color: #E07A5F;
                    margin-bottom: 20px;
                }

                .card {
                    background-color: #FFFFFF;
                    border-radius: 10px;
                    border: none;
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                    margin-bottom: 20px;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
                }

                .card-header {
                    background-color: #F4A261;
                    color: white;
                    border-radius: 10px 10px 0 0;
                    padding: 20px;
                    font-size: 18px;
                    font-weight: 600;
                }

                .card-body {
                    padding: 20px;
                    color: #2C3E50;
                    line-height: 1.6;
                }

                .btn-primary {
                    background-color: #E07A5F;
                    border-color: #E07A5F;
                    padding: 10px 20px;
                    border-radius: 5px;
                    transition: background-color 0.3s ease, box-shadow 0.3s ease;
                    font-weight: 600;
                }

                .btn-primary:hover {
                    background-color: #F2CC8F;
                    border-color: #F2CC8F;
                    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
                    color: #2C3E50;
                }

                .hidden {
                    display: none;
                }

                .nav-item .active {
                    background-color: #E07A5F;
                    color: white;
                }

                .form-label {
                    color: #2C3E50;
                    font-weight: 500;
                    margin-bottom: 10px;
                }

                .form-control {
                    background-color: #FFE8D6;
                    color: #2C3E50;
                    border: 1px solid #B5838D;
                    padding: 10px;
                    border-radius: 5px;
                    transition: border-color 0.3s ease, box-shadow 0.3s ease;
                }

                .form-control:focus {
                    background-color: #FFFFFF;
                    color: #2C3E50;
                    border-color: #E07A5F;
                    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                    outline: none;
                }

                /* Additional styles for transition effects */
                a,
                button {
                    transition: all 0.3s ease;
                }

                a:hover,
                button:hover {
                    opacity: 0.9;
                }

                .form-container {
                    background-color: #fff;
                    padding: 30px;
                    width: 100%;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    border-radius: 10px;
                }

                h2 {
                    text-align: center;
                    color: #333;
                    margin-bottom: 20px;
                }

                label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 600;
                    color: #333;
                }

                input,
                select {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 20px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                }

                input[type="submit"] {
                    background-color: #28a745;
                    color: white;
                    border: none;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }

                input[type="submit"]:hover {
                    background-color: #218838;
                }

                .form-group {
                    margin-bottom: 20px;
                }
            </style>

        </head>

        <body>

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <div id="wrapper">
                <!-- Sidebar Menu -->
                <nav id="sidebar">
                    <div class="sidebar-header text-center">
                        <h3><a href="index.html"><i class="bi bi-box-arrow-right"></i></a> Service Insurance Provider Dashboard
                        </h3>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="profileLink">
                                <i class="bi bi-person"></i> Profile Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="serviceRequestsLink">
                                <i class="bi bi-file-earmark"></i> Service Requests
                            </a>
                        </li>
                        <li class="nav-item"></li>
                        <a class="nav-link" href="#" id="addSchemeLink">
                            <i class="bi bi-clipboard-check"></i> Add Scheme
                        </a>
                        </li>
                        <li class="nav-item"></li>
                        <a class="nav-link" href="#" id="claimsManagementLink">
                            <i class="bi bi-clipboard-check"></i> Add Customer Scheme
                        </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#" id="paymentsBillingLink">
                                <i class="bi bi-credit-card"></i> Payments & Billing
                            </a>
                        </li>

                    </ul>
                </nav>

                <!-- Content Section -->
                <div id="content">
                    <div class="container">
                        <h2>Welcome, <span id="welcomeName">Provider</span>!</h2>

                        <!-- Profile Information -->
                        <div id="profileInfo" class="card">
                            <div class="card-header">Profile Information</div>
                            <div class="card-body">
                                <!-- Display current information -->
                                <h5>Current Information:</h5>
                                <p><strong>Name:</strong>
                                    <span id="displayName">
                                        <div><?php echo $name ?></div>
                                    </span>
                                </p>
                                <p>
                                    <strong>Services Provided:</strong>
                                    <span id="displayServices">
                                        <div><?php echo $service_provider ?></div>
                                    </span>
                                </p>
                                <p>
                                    <strong>Service Area:</strong>
                                    <span id="displayServiceArea">
                                        <div><?php echo $area ?></div>
                                    </span>
                                </p>
                                <p>
                                    <strong>Contact Information:</strong>
                                    <span id="displayContactInfo">
                                        <div><?php echo $contact_information ?></div>
                                    </span>
                                </p>
                                <p>
                                    <strong>Service Rates & Policies:</strong>
                                    <span id="displayRatesPolicies">
                                    </span>
                                </p>
                                <p>
                                    <strong>Client Reviews & Ratings:</strong>
                                    <span id="displayReviewsRatings">
                                    </span>
                                </p>

                                <!-- Update Details Button -->
                                <button id="updateDetailsBtn" class="btn btn-primary">Update Details</button>

                                <!-- Profile Form (Initially Hidden) -->
                                <form id="profileForm" class="hidden mt-3">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name of the Provider</label>
                                        <input type="text" class="form-control" id="name" value="<?php echo $name ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="servicesProvided" class="form-label">Types of Services Provided</label>
                                        <input type="text" class="form-control" id="servicesProvided"
                                            placeholder="e.g., Accident Coverage, Vehicle Damage"
                                            value="<?php echo $service_provider ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="serviceArea" class="form-label">Service Area (Regions or States
                                            Covered)</label>
                                        <input type="text" class="form-control" id="serviceArea" value="<?php echo $area ?>"
                                            required>
                                    </div>
                                    <div class=" mb-3">
                                        <label for="contactInfo" class="form-label">Contact Information</label>
                                        <input type="text" class="form-control" id="contactInfo"
                                            value="<?php echo $contact_information ?>" required>
                                    </div>
                                    <input type="hidden" value="<?php echo $loginId ?>" id="userId">
                                    <!-- <div class="mb-3">
                                <label for="ratesPolicies" class="form-label">Service Rates and Policies</label>
                                <textarea class="form-control" id="ratesPolicies" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="reviewsRatings" class="form-label">Client Reviews and Ratings</label>
                                <textarea class="form-control" id="reviewsRatings" rows="3"></textarea>
                            </div> -->
                                    <button type="submit" class="btn btn-primary">Save Information</button>
                                </form>
                            </div>
                        </div>

                        <?php
                        $request_query = "
                        SELECT c.*, u.name, u.email, s.name AS scheme_name
                        FROM complaints c 
                        JOIN users u ON c.login_id = u.login_id 
                        JOIN customer_scheme cs ON u.id = cs.user_id
                        JOIN scheme s ON s.id = cs.scheme_id 
                        WHERE s.login_id = ?";

                        $stmt = $conn->prepare($request_query);
                        $stmt->bind_param("i", $loginId); // Assuming login_id is an integer
                        $stmt->execute();
                        $request_result = $stmt->get_result();
                        ?>
                        <!-- Service Requests -->
                        <div id="serviceRequests" class="card hidden">
                            <div class="card-header">Service Requests</div>
                            <div class="card-body">
                                <ul id="serviceRequestsList">
                                    <?php
                                    if ($request_result->num_rows > 0) {
                                        while ($row = $request_result->fetch_assoc()) {
                                            echo '<li>
                            <div>Request ID: ' . htmlspecialchars($row['type']) . '</div>
                            <div>Description: ' . htmlspecialchars($row['description']) . '</div>
                            <div>Date: ' . htmlspecialchars($row['date']) . '</div>
                            <div>Name: ' . htmlspecialchars($row['name']) . '</div>
                            <div>Scheme Name: ' . htmlspecialchars($row['scheme_name']) . '</div>
                            <div>Price: ' . htmlspecialchars($row['price']) . '</div>
                            <div>Image: <img width="450" height="250" src="' . htmlspecialchars($row['image']) . '"/></div>
                          </li>';
                                        }
                                    } else {
                                        echo '<li>No service requests available.</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div id="addScheme" class="card hidden">
                            <div class="card-header">ADD Scheme</div>
                            <div class="card-body">
                                <div class="form-container">
                                    <form id="addschemeform" action="add_scheme.php" method="POST">
                                        <div class="form-group">
                                            <label for="schemeType">Scheme Type/Name:</label>
                                            <input type="text" name="schemeType" id="schemeType"
                                                placeholder="Enter scheme type or name" required>
                                        </div>

                                        <!-- Amount -->
                                        <div class="form-group">
                                            <label for="amount">Amount:</label>
                                            <input type="number" name="schemeamount" id="schemeamount"
                                                placeholder="Enter amount" required>
                                        </div>

                                        <!-- Time Period (Months) -->
                                        <div class="form-group">
                                            <label for="timePeriod">Time Period (Months):</label>
                                            <input type="number" name="timePeriod" id="timePeriod" placeholder="Enter amount"
                                                required>
                                        </div>
                                        <!-- Coverage -->
                                        <div class="form-group">
                                            <label for="coverage">Coverage (%):</label>
                                            <input type="number" name="coverage" id="schemecoverage"
                                                placeholder="Enter coverage percentage" required>
                                        </div>

                                        <input type="submit" value="Submit">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="customerScheme" class="card hidden">
                            <div class="card-header">ADD Customer</div>
                            <div class="card-body">
                                <div class="form-container">
                                    <h2>Select Name and Scheme</h2>

                                    <?php
                                    $name_query = "SELECT id, email as name FROM users";
                                    $name_result = $conn->query($name_query);

                                    // Fetch schemes
                                    $scheme_query = "SELECT id, name FROM scheme WHERE login_id = ?";
                                    $stmt = $conn->prepare($scheme_query);
                                    $stmt->bind_param("i", $loginId); // Assuming login_id is an integer
                                    $stmt->execute();
                                    $scheme_result = $stmt->get_result();
                                    ?>

                                    <form id="addUserSchemeForm" action="submit_name_scheme.php" method="POST">
                                        <!-- Name Selection -->
                                        <label for="name">Select Name:</label>
                                        <select name="name" id="addUserSchemeName" required>
                                            <option value="" disabled selected>Select Name</option>
                                            <?php
                                            if ($name_result->num_rows > 0) {
                                                while ($row = $name_result->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>


                                        <!-- Scheme Selection -->
                                        <label for="scheme">Select Scheme:</label>
                                        <select name="scheme" id="addUserSchemeScheme" required>
                                            <option value="" disabled selected>Select Scheme</option>
                                            <?php
                                            if ($scheme_result->num_rows > 0) {
                                                while ($row = $scheme_result->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                        <input type="submit" value="Submit">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Payments & Billing -->
                    <!-- <div id="paymentsBilling" class="card hidden">
                        <div class="card-header">Payments & Billing</div>
                        <div class="card-body">
                            <h5>Pending Payouts:</h5>
                            <ul id="pendingPayoutsList"></ul>

                            <h5 class="mt-4">Payment History:</h5>
                            <ul id="paymentHistoryList"></ul>
                        </div>
                    </div> -->
                    <section id="paymentSystem" class="card hidden">
                        <h2>Payments & Billing</h2>
                        <br>
                        <h3>Add Payment</h3>

                        <!-- Payment Form -->
                        <form action="https://www.escrow.com/checkout" method="post" target="_blank">
                            <!-- Date Field -->
                            <label for="paymentDate">Date:</label>
                            <input type="date" id="paymentDate" name="paymentDate" required><br>

                            <!-- Hidden Fields for Escrow -->
                            <input type="hidden" name="type" value="domain_name">
                            <input type="hidden" name="non_initiator_email" value="saisnehayellumahanti@gmail.com">
                            <input type="hidden" name="non_initiator_id" value="3381154">
                            <input type="hidden" name="non_initiator_role" value="seller">
                            <input type="hidden" name="title" value="Pay Now ">
                            <input type="hidden" name="currency" value="USD">
                            <input type="hidden" name="domain" value="service_dashboard">
                            <input type="hidden" name="concierge" value="false">
                            <input type="hidden" name="with_content" value="false">
                            <input type="hidden" name="inspection_period" value="1">
                            <input type="hidden" name="fee_payer" value="seller">
                            <input type="hidden" name="return_url" value="">
                            <input type="hidden" name="button_types" value="buy_now">
                            <input type="hidden" name="auto_accept" value="">
                            <input type="hidden" name="auto_reject" value="">
                            <input type="hidden" name="item_key" value="undefined">

                            <!-- Amount Field -->
                            <label for="paymentAmount">Amount:</label>
                            <input type="number" name="price" id="paymentAmount" required><br>

                            <!-- Submit Button -->
                            <button class="EscrowButtonPrimary" type="submit">Pay Now @</button>
                        </form>

                        <!-- Hidden Tracking Pixel (if needed) -->
                        <img src="https://t.escrow.com/1px.gif?name=bin&price=55000&title=Pay%20Now%20&user_id=3381154"
                            style="display: none;">
                    </section>


                    <!-- Client Communication -->
                    <!-- <div id="clientCommunication" class="card hidden">
                        <div class="card-header">Client Communication</div>
                        <div class="card-body">
                            <h5>Message Center:</h5>
                            <ul id="messageList"></ul>

                            <h5 class="mt-4">Send a Message:</h5>
                            <form id="sendMessageForm">
                                <div class="mb-3">
                                    <label for="messageContent" class="form-label">Message</label>
                                    <textarea class="form-control" id="messageContent" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                        </ div> -->



                    <!-- Location -->
                    <div id="location" class="card hidden">
                        <div class="card-header">Location</div>
                        <div class="card-body">
                            <h5>Current Location:</h5>
                            <p id="locationDetails"></p>
                            <button id="getLocationBtn" class="btn btn-primary">Get Location</button>
                        </div>
                    </div>

                </div>
            </div>
            </div>

            <!-- Bootstrap JS and Map Integration Scripts (if any) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

            <script>

                async function reverseGeocode(lat, lng) {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                    const data = await response.json();

                    if (data && data.address) {
                        const latitudeInput = document.getElementById('latitude');
                        const longitudeInput = document.getElementById('longitude');
                        const { road, suburb, city, state, country } = data.address;
                        let locationInfo = '';
                        if (road) locationInfo += `Street: ${road}, `;
                        if (suburb) locationInfo += `Area: ${suburb}, `;
                        if (city) locationInfo += `City: ${city}, `;
                        if (state) locationInfo += `State: ${state}, `;
                        if (country) locationInfo += `Country: ${country}`;
                        if (lat && lng) {
                            latitudeInput.value = data.lat;
                            longitudeInput.value = data.lon;
                        }
                        return locationInfo.trim();
                    } else {
                        throw new Error("Unable to get address");
                    }
                }

                window.addEventListener('load', function () {
                    const latitudeInput = document.getElementById('latitude');
                    const longitudeInput = document.getElementById('longitude');

                    if (navigator.geolocation) {
                        // Start watching the user's position
                        watchId = navigator.geolocation.watchPosition(
                            function (position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;

                                latitudeInput.value = lat;
                                longitudeInput.value = lng;
                                console.log(lat, lng)

                                reverseGeocode(lat, lng)
                                    .then(locationInfo => {
                                    })
                                    .catch(error => {
                                    });
                            },
                            function () {
                            },
                            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                        );
                    }
                    else {
                        console.log("location not found")
                    }
                });
                // Retrieve provider profile information from local storage on page load
                document.addEventListener('DOMContentLoaded', function () {
                    const email = localStorage.getItem('loggedInProvider');
                    const storedProvider = localStorage.getItem(email);

                    if (storedProvider) {
                        const providerDetails = JSON.parse(storedProvider);

                        document.getElementById('name').value = providerDetails.name || '';
                        document.getElementById('servicesProvided').value = providerDetails.servicesProvided || '';
                        document.getElementById('serviceArea').value = providerDetails.serviceArea || '';
                        document.getElementById('contactInfo').value = providerDetails.contactInfo || '';
                        document.getElementById('ratesPolicies').value = providerDetails.ratesPolicies || '';
                        document.getElementById('reviewsRatings').value = providerDetails.reviewsRatings || '';

                        document.getElementById('displayName').innerText = providerDetails.name || 'N/A';
                        document.getElementById('displayServices').innerText = providerDetails.servicesProvided || 'N/A';
                        document.getElementById('displayServiceArea').innerText = providerDetails.serviceArea || 'N/A';
                        document.getElementById('displayContactInfo').innerText = providerDetails.contactInfo || 'N/A';
                        document.getElementById('displayRatesPolicies').innerText = providerDetails.ratesPolicies || 'N/A';
                        document.getElementById('displayReviewsRatings').innerText = providerDetails.reviewsRatings || 'N/A';

                        document.getElementById('welcomeName').innerText = providerDetails.name || 'Provider';
                    }

                    // Load new service requests
                    const newServiceRequests = JSON.parse(localStorage.getItem('newServiceRequests')) || [];
                    // const newServiceRequestsList = document.getElementById('newServiceRequestsList');
                    // newServiceRequestsList.innerHTML = '';
                    // newServiceRequests.forEach(request => {
                    //     const li = document.createElement('li');
                    //     li.textContent = `Coverage: ${request.requestType}, Incident: ${request.incidentDetails}, Location: ${request.location}`;
                    //     newServiceRequestsList.appendChild(li);
                    // });

                    // Load active claims

                    // Load pending payouts
                    const pendingPayouts = JSON.parse(localStorage.getItem('pendingPayouts')) || [];
                    // const pendingPayoutsList = document.getElementById('pendingPayoutsList');
                    // pendingPayoutsList.innerHTML = '';
                    // pendingPayouts.forEach(payout => {
                    //     const li = document.createElement('li');
                    //     li.textContent = `Claim ID: ${payout.claimId}, Amount: $${payout.amount}`;
                    //     pendingPayoutsList.appendChild(li);
                    // });

                    // Load payment history
                    const paymentHistory = JSON.parse(localStorage.getItem('paymentHistory')) || [];
                    // const paymentHistoryList = document.getElementById('paymentHistoryList');
                    // paymentHistoryList.innerHTML = '';
                    // paymentHistory.forEach(payment => {
                    //     const li = document.createElement('li');
                    //     li.textContent = `Claim ID: ${payment.claimId}, Amount: $${payment.amount}, Date: ${payment.date}`;
                    //     paymentHistoryList.appendChild(li);
                    // });

                    // Load messages
                    const messages = JSON.parse(localStorage.getItem('messages')) || [];
                    // const messageList = document.getElementById('messageList');
                    // messageList.innerHTML = '';
                    // messages.forEach(message => {
                    //     const li = document.createElement('li');
                    //     li.textContent = `From: ${message.from}, Message: ${message.content}`;
                    //     messageList.appendChild(li);
                    // });

                    // Load Reports & Analytics data
                    const reportsData = JSON.parse(localStorage.getItem('reportsData')) || {
                        claimsHandled: 0,
                        successRate: 0,
                        clientSatisfaction: 0,
                        earningsOverview: 0
                    };

                    // document.getElementById('claimsHandled').innerText = reportsData.claimsHandled;
                    // document.getElementById('successRate').innerText = reportsData.successRate;
                    // document.getElementById('clientSatisfaction').innerText = reportsData.clientSatisfaction;
                    // document.getElementById('earningsOverview').innerText = reportsData.earningsOverview;
                });

                document.getElementById('addschemeform').addEventListener('submit', function (event) {
                    event.preventDefault(); // Prevent default form submission

                    const name = document.getElementById('schemeType').value;
                    const amount = document.getElementById('schemeamount').value;
                    const period = document.getElementById('timePeriod').value;
                    const coverage = document.getElementById('schemecoverage').value;

                    // Create user details object
                    const userDetails = {
                        name: name,
                        amount: amount,
                        period: period,
                        coverage: coverage,
                        latitude: document.getElementById('latitude').value,
                        longitude: document.getElementById('longitude').value,
                        id: document.getElementById('userId').value
                    };

                    // Send the data to the server using fetch
                    fetch('add_scheme.php', {
                        method: 'POST',
                        body: new URLSearchParams(userDetails)
                    })
                        .then(response => response.text())
                        .then(data => {
                            alert(data); // Show the response from the server
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Signup failed. Please try again.');
                        });
                });

                document.getElementById('addUserSchemeForm').addEventListener('submit', function (event) {
                    event.preventDefault(); // Prevent default form submission

                    const addUserSchemeScheme = document.getElementById('addUserSchemeScheme').value;
                    const addUserSchemeName = document.getElementById('addUserSchemeName').value;
                    const period = document.getElementById('timePeriod').value;
                    const coverage = document.getElementById('schemecoverage').value;

                    // Create user details object
                    const userDetails = {
                        schemeId: addUserSchemeScheme,
                        userId: addUserSchemeName,
                        latitude: document.getElementById('latitude').value,
                        longitude: document.getElementById('longitude').value,
                        id: document.getElementById('userId').value
                    };

                    // Send the data to the server using fetch
                    fetch('add_scheme_customer.php', {
                        method: 'POST',
                        body: new URLSearchParams(userDetails)
                    })
                        .then(response => response.text())
                        .then(data => {
                            alert(data); // Show the response from the server
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Signup failed. Please try again.');
                        });
                });

                // Save updated profile information
                document.getElementById('profileForm').addEventListener('submit', function (event) {
                    event.preventDefault();

                    const updatedInfo = {
                        name: document.getElementById('name').value,
                        contact_information: document.getElementById('contactInfo').value,
                        service_provider: document.getElementById('servicesProvided').value,
                        area: document.getElementById('serviceArea').value,
                        location: document.getElementById('location').value,
                        latitude: document.getElementById('latitude').value,
                        longitude: document.getElementById('longitude').value,
                        id: document.getElementById('userId').value
                    };

                    // Update display
                    fetch('service.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams(updatedInfo)
                    })
                        .then(response => response.text())
                        .then(data => {
                            if (data.includes('Success')) {
                                window.location.reload()
                            }
                            else {
                                alert(data)
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Signup failed. Please try again.');
                        });

                    // const email = localStorage.getItem('loggedInProvider');
                    // const providerDetails = {
                    //     name: document.getElementById('name').value,
                    //     servicesProvided: document.getElementById('servicesProvided').value,
                    //     serviceArea: document.getElementById('serviceArea').value,
                    //     contactInfo: document.getElementById('contactInfo').value,
                    //     ratesPolicies: document.getElementById('ratesPolicies').value,
                    //     reviewsRatings: document.getElementById('reviewsRatings').value
                    // };

                    // localStorage.setItem(email, JSON.stringify(providerDetails));

                    // // Update the display with new details
                    // document.getElementById('displayName').innerText = providerDetails.name;
                    // document.getElementById('displayServices').innerText = providerDetails.servicesProvided;
                    // document.getElementById('displayServiceArea').innerText = providerDetails.serviceArea;
                    // document.getElementById('displayContactInfo').innerText = providerDetails.contactInfo;
                    // document.getElementById('displayRatesPolicies').innerText = providerDetails.ratesPolicies;
                    // document.getElementById('displayReviewsRatings').innerText = providerDetails.reviewsRatings;

                    // document.getElementById('welcomeName').innerText = providerDetails.name;

                    // Hide the form again after saving
                    document.getElementById('profileForm').classList.add('hidden');
                });

                // Toggle form visibility when the "Update Details" button is clicked
                document.getElementById('updateDetailsBtn').addEventListener('click', function () {
                    document.getElementById('profileForm').classList.toggle('hidden');
                });

                // // Submit new service request
                // document.getElementById('addServiceRequestForm').addEventListener('submit', function (event) {
                //     event.preventDefault();

                //     const requestType = document.getElementById('requestType').value;
                //     constincidentDetails = document.getElementById('incidentDetails').value;
                //     const location = document.getElementById('location').value;

                //     const newServiceRequests = JSON.parse(localStorage.getItem('newServiceRequests')) || [];

                //     newServiceRequests.push({ requestType, incidentDetails, location });
                //     localStorage.setItem('newServiceRequests', JSON.stringify(newServiceRequests));

                //     const newServiceRequestsList = document.getElementById('newServiceRequestsList');
                //     const li = document.createElement('li');
                //     li.textContent = `Coverage: ${requestType}, Incident: ${incidentDetails}, Location: ${location}`;
                //     newServiceRequestsList.appendChild(li);

                //     // Clear the form
                //     document.getElementById('requestType').value = '';
                //     document.getElementById('incidentDetails').value = '';
                //     document.getElementById('location').value = '';
                // });


                // // Navigation links scroll to sections
                // document.getElementById('profileLink').addEventListener('click', function () {
                //     document.getElementById('profileInfo').scrollIntoView({ behavior: 'smooth' });
                //     document.querySelectorAll('.card').forEach(card => card.classList.add('hidden'));
                //     document.getElementById('profileInfo').classList.remove('hidden');
                // });

                // document.getElementById('serviceRequestsLink').addEventListener('click', function () {
                //     document.getElementById('serviceRequests').scrollIntoView({ behavior: 'smooth' });
                //     document.querySelectorAll('.card').forEach(card => card.classList.add('hidden'));
                //     document.getElementById('serviceRequests').classList.remove('hidden');
                // });

                // document.getElementById('claimsManagementLink').addEventListener('click', function () {
                //     document.getElementById('claimsManagement').scrollIntoView({ behavior: 'smooth' });
                //     document.querySelectorAll('.card').forEach(card => card.classList.add('hidden'));
                //     document.getElementById('claimsManagement').classList.remove('hidden');
                // });

                // document.getElementById('paymentsBillingLink').addEventListener('click', function () {
                //     document.getElementById('paymentsBilling').scrollIntoView({ behavior: 'smooth' });
                //     document.querySelectorAll('.card').forEach(card => card.classList.add('hidden'));
                //     document.getElementById('paymentsBilling').classList.remove('hidden');
                // });

                // document.getElementById('clientCommunicationLink').addEventListener('click', function () {
                //     document.getElementById('clientCommunication').scrollIntoView({ behavior: 'smooth' });
                //     document.querySelectorAll('.card').forEach(card => card.classList.add('hidden'));
                //     document.getElementById('clientCommunication').classList.remove('hidden');
                // });

                // document.getElementById('reportsAnalyticsLink').addEventListener('click', function () {
                //     document.getElementById('reportsAnalytics').scrollIntoView({ behavior: 'smooth' });
                //     document.querySelectorAll('.card').forEach(card => card.classList.add('hidden'));
                //     document.getElementById('reportsAnalytics').classList.remove('hidden');
                // });

                // document.getElementById('locationLink').addEventListener('click', function () {
                //     document.getElementById('location').scrollIntoView({ behavior: 'smooth' });
                //     document.querySelectorAll('.card').forEach(card => card.classList.add('hidden'));
                //     document.getElementById('location').classList.remove('hidden');
                // });

                document.getElementById('profileLink').addEventListener('click', () => showContent('profileInfo'));
                document.getElementById('serviceRequestsLink').addEventListener('click', () => showContent('serviceRequests'));
                document.getElementById('addSchemeLink').addEventListener('click', () => showContent('addScheme'));
                document.getElementById('claimsManagementLink').addEventListener('click', () => showContent('customerScheme'));
                // document.getElementById('paymentsBillingLink').addEventListener('click', () => showContent('addCustomer'));
                // document.getElementById('hireLawyerLink').addEventListener('click', () => showContent('hireLawyer'));
                // document.getElementById('paymentSystemLink').addEventListener('click', () => showContent('paymentSystem'));
                // document.getElementById('locationLink').addEventListener('click', () => showContent('location'));

                function showContent(contentId) {
                    const contents = ['profileInfo', 'serviceRequests', 'addScheme', 'customerScheme'];
                    contents.forEach(content => {
                        document.getElementById(content).classList.add('hidden');
                    });
                    document.getElementById(contentId).classList.remove('hidden');
                }

                document.getElementById('getLocationBtn').addEventListener('click', function () {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;

                        const locationDetails = document.getElementById('locationDetails');
                        locationDetails.innerHTML = '';

                        locationDetails.innerHTML += `Latitude: ${latitude}<br>`;
                        locationDetails.innerHTML += `Longitude: ${longitude}<br>`;

                        // Convert coordinates to address
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({ location: { lat: latitude, lng: longitude } }, function (results, status) {
                            if (status === 'OK') {
                                if (results[0]) {
                                    locationDetails.innerHTML += `Address: ${results[0].formatted_address}<br>`;
                                } else {
                                    locationDetails.innerHTML += 'No address found.<br>';
                                }
                            } else {
                                locationDetails.innerHTML += 'Geocoder failed due to: ' + status + '<br>';
                            }
                        });
                    });
                });


            </script>

        </body>

        </html>

        <?php
    } else {
        // User not found in the database
        echo "User not found.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to login if not logged in
    header("Location: login.html");
    exit();
}
?>