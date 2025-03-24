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
    SELECT u.name, u.login_id, l.type, u.gender, u.age, u.role, u.contact_number, u.upiId,u.file_name
    FROM login l 
    JOIN users u ON l.id = u.login_id 
    WHERE l.username = ?
    ");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($name, $loginId, $category, $gender, $age, $role, $contactNumber, $upiId, $file_name);
        $stmt->fetch();
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Dashboard</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
                integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.min.css" rel="stylesheet">

            <style>
                @import url(https://fonts.googleapis.com/css?family=Open+Sans:600);

                .EscrowButtonPrimary.EscrowButtonPrimary {
                    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG8AAAALCAMAAABGfiMeAAAAolBMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8ELnaCAAAANXRSTlMA95xuSysUU/vh2tWxlA4Z88Sselc1He/quriOCwbNiYFEMALm0Mm+taJnP6ZjIoRdTjp03P7kXRgAAAI+SURBVDjLhZKJkppQEEXvQ0BAZBEEHQFlUVzHZXL//9fSj1Ti1GRMTpWtJe/16faKt4EpEBqu6qoGOHpKuQbuS8GwABTJNveMDCYrAB3l8DvzFkLj/3CrA4D0zYs3BfDoDAiF5+F76CyEHtjJJ4cNjmSk6GFEjRLhnswVF2HgOAEscgJcuAdEG9F1IwVs5NsFdyVMKhvAjc4r3wQDIScBYJfYspZ3S3z7+3jJgxi6BoHPtYx01+PQxoEfgNZc9FW0kZuhPDBFyuFJTJb/9tncthBaJ4dGfFOIa4uxOIGTbLTmCmdWHMPlHILPAJqMhtQ5r7IYPX2NDF743FQoIHtNkgwIOHn6xPUmr1/NlkOAnUo5zRiVEGKFp08sa1k/5wkrqeEL38C7ZLElRWE/fa6/V7x98kmAc/Zy4iZjaHbRV9+IK57L3PX5/mo/UyggNElOM6D326e54pMPFX1usJWa4Pv9Eo53asTkQOtlfk/kYKv+5OfXE93X5AxATR9YkTxhKvUBzYw2NCHPUh8yy5XmhcoJDc7/4xs3bXHmETEvgW3VQ36WcuYIqcw266UZ7qQqZWEdXz0NkfADKBqgix76LzyWieoioi8znV74qIQd7IgOubClm8YbfLjQK6SHfuTagE32Q10CExkgXMhtKiAlO8W+Fc8D95GFNWuEyYf5l3U2sEYw6l3voEM2+x9efIU5uwC4zsay+t6L1zaE1fk21BQ4xrJXZsS7/UrHa8T+qAQ2lQXNsRJTfTK//qo/Aes/TdDnJ+8NAAAAAElFTkSuQmCC);
                    background-color: #0ecb6f !important;
                    background-repeat: no-repeat !important;
                    background-position: right 13px !important;
                    border-radius: 4px !important;
                    border: 1px solid #0ecb6f !important;
                    box-shadow: 0 2px 4px 0 hsla(0, 12%, 54%, .1) !important;
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
                    transition: all .1s linear !important;
                }

                .EscrowButtonPrimary.EscrowButtonPrimary:hover {
                    background-color: #56da9a !important;
                    border-color: #56da9a !important;
                }

                .EscrowButtonPrimary.EscrowButtonPrimary:focus {
                    background-color: #00b65a !important;
                }

                /* General styles for hidden cards */
                .hidden {
                    display: none;
                }

                /* Payments & Billing Section */
                #paymentsBilling {
                    padding: 20px;
                    background-color:rgb(201, 187, 187);
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

                /* Form Styling */
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
                    background: linear-gradient(135deg,rgb(61, 61, 58),rgb(123, 121, 121));
                    /* Lighter gradient */
                    height: 100%;
                    color: black;
                    /* Font color set to black */
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
                    color:rgb(70, 67, 67);
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
                    background-color:rgb(95, 92, 90);
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
                    background-color:rgb(104, 101, 100);
                    border-color:rgb(102, 95, 93);
                    padding: 10px 20px;
                    border-radius: 5px;
                    transition: background-color 0.3s ease, box-shadow 0.3s ease;
                    font-weight: 600;
                    color: #FDF6E3;
                    /* Text color for button */
                }

                .btn-primary:hover {
                    background-color:rgb(136, 130, 122);
                    border-color:rgb(104, 103, 100);
                    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
                    color: #2C3E50;
                }

                .hidden {
                    display: none;
                }

                .nav-item .active {
                    background-color:rgb(132, 128, 127);
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
                    border-color:rgb(104, 93, 90);
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

                .container {
                    margin: auto;
                    background: #fff;
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                }

                h2 {
                    text-align: center;
                    color: #333;
                    margin-bottom: 20px;
                }

                label {
                    display: block;
                    margin: 10px 0 5px;
                    font-weight: bold;
                    color: #555;
                }

                input[type="text"],
                input[type="date"],
                input[type="number"],
                input[type="file"],
                textarea {
                    width: 100%;
                    padding: 12px;
                    margin: 5px 0 20px;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    font-size: 16px;
                    transition: border-color 0.3s;
                }

                input[type="text"]:focus,
                input[type="date"]:focus,
                input[type="number"]:focus,
                textarea:focus {
                    border-color: #28a745;
                    outline: none;
                }

                textarea {
                    height: 120px;
                    resize: none;
                }

                button {
                    background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 12px 20px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: background-color 0.3s;
                    width: 100%;
                }

                button:hover {
                    background-color: #218838;
                }

                .geo-location {
                    margin: 10px 0;
                    font-weight: bold;
                    text-align: center;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                }
            </style>

        </head>

        <body>


            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <div id="wrapper">
                <nav id="sidebar">
                    <div class="sidebar-header text-center">
                        <h3><a href="index.html"><i class="bi bi-box-arrow-right"></i></a> User Dashboard</h3>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#profileInfo" id="profileLink">
                                <i class="bi bi-person"></i> Profile Information
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#insuranceSchemes" id="insuranceLink">
                                <i class="bi bi-shield-lock"></i> Insurance Schemes
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="#hireLawyer" id="hireLawyerLink">
                                <i class="fa-solid fa-user-graduate icon-outline"></i>Lawyers
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#paymentSystem" id="paymentSystemLink">
                                <i class="bi bi-credit-card"></i> Payment System
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#location" id="locationLink">
                                <i class="bi bi-geo-alt"></i> Location
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="#biddingPage" id="biddingPageLink">
                                <i class="fa-light fa-circle-exclamation icon-outline"></i> Add Your Problem
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#notification" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-bell"></i> Notifications 
                            </a>
                            
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-danger" href="index.html" id="logoutBtn">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>

                    </ul>
                </nav>

                <div id="content">
                    <div class="container">
                        <h2>Welcome, <span id="welcomeName"><?php echo $name; ?></span>!</h2>

                        <div id="profileInfo" class="card">
                            <div class="card-header">Profile Information</div>
                            <div class="card-body">
                                <h5>Current Information:</h5>
                                <p>
                                    <strong>Name:</strong>
                                    <span id="displayName">
                                        <?php echo $name ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Age:</strong>
                                    <span id="displayAge">
                                        <?php echo $age; ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Gender:</strong>
                                    <span id="displayGender">
                                        <?php echo $gender; ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Role:</strong>
                                    <span id="displayRole">
                                        <?php echo $role; ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Profile:</strong>
                                    <span id="displayRole">
                                        <img width="450" height="250" src="<?php echo $file_name; ?>"></img>
                                    </span>
                                </p>
                                <p>
                                    <strong>UPI ID:</strong>
                                    <span id="displayRole">
                                        <?php echo $upiId; ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Contact Number:</strong>
                                    <span id="displayContact">
                                        <?php echo $contactNumber; ?>
                                    </span>
                                </p>
                                <button id="updateDetailsBtn" class="btn btn-primary">Update Details</button>

                                <form id="profileForm" class="hidden mt-3" enctype="multipart/form-data" action="user.php"
                                    method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" value="<?php echo $name ?>" id="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="age" class="form-label">Age</label>
                                        <input type="number" class="form-control" value="<?php echo $age ?>" id="age" required>
                                    </div>
                                    <input type="hidden" value="<?php echo $loginId ?>" id="userId">

                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control" id="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?php echo $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo $gender == 'Female' ? 'selected' : ''; ?>>Female
                                            </option>
                                            <option value="Others" <?php echo $gender == 'Others' ? 'selected' : ''; ?>>Others
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <input type="text" class="form-control" id="role" value="<?php echo $role ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" value="<?php echo $contactNumber ?>"
                                            id="contact" required>
                                    </div>
                                    <!-- New File Upload Field -->
                                    <div class="mb-3">
                                        <label for="fileUpload" class="form-label">Upload File</label>
                                        <input type="file" class="form-control" id="fileUpload" name="image">
                                    </div>
                                    <!-- New UPI/Bank Account Field -->
                                    <div class="mb-3">
                                        <label for="upiBank" class="form-label">UPI ID or Bank Account</label>
                                        <input type="text" class="form-control" id="upiBank" name="upiID"
                                            value="<?php echo $upiId ?>" placeholder="Enter UPI ID or Bank Account" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Information</button>
                                </form>
                            </div>
                        </div>

                        <div id="biddingPage" class="card hidden">
                            <div class="card-header">Add Your Problem</div>
                            <div class="card-body">
                                <form id="biddingForm" enctype="multipart/form-data" action="problem.php" method="POST">
                                    <div class="mb-3">
                                        <label for="caseDescription" class="form-label">Description of Your Case</label>
                                        <textarea class="form-control" id="caseDescription" name="description" rows="4"
                                            placeholder="Describe your case..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bidderName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="bidderName" name="name"
                                            placeholder="Your name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactNumber" class="form-label">Contact Number</label>
                                        <input type="tel" class="form-control" id="contactNumber" name="contact_number"
                                            placeholder="Your contact number" required>
                                    </div>
                                    <input type="hidden" name="login_id" value="<?php echo $loginId ?>" id="caseuserId">
                                    <div class="mb-3">
                                        <label for="fileUpload" class="form-label">Upload File</label>
                                        <input type="file" class="form-control" id="casefileUpload" name="image" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Details</button>
                                </form>
                            </div>
                        </div>

                        <?php
                        $scheme_query = "
                        SELECT s.*, a.name as agency_name
                        FROM scheme s 
                        JOIN agencies a ON s.login_id = a.login_id";
                        $scheme_result = $conn->query($scheme_query);
                        ?>

                        <div id="insuranceSchemes" class="card hidden">
                            <div class="card-header">Insurance Schemes</div>
                            <div class="card-body">
                                <ul id="insuranceSchemesList">
                                    <?php
                                    if ($scheme_result->num_rows > 0) {
                                        while ($row = $scheme_result->fetch_assoc()) {
                                            echo '<li>
                            <div>Name: ' . htmlspecialchars($row['name']) . '</div>
                            <div>Amount: ' . htmlspecialchars($row['amount']) . '</div>
                            <div>Months Validity: ' . htmlspecialchars($row['period']) . '</div>
                            <div>Agency Name: ' . htmlspecialchars($row['agency_name']) . '</div>
                          </li>';
                                        }
                                    } else {
                                        echo '<li>No insurance schemes available.</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div id="hireLawyer" class="card hidden">
                            <div class="card-header">Lawyers</div>
                            <div class="card-body">
                                <ul id="lawyersList"></ul>
                                <div id="lawyerDiv"></div>
                            </div>
                        </div>

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
                                <input type="hidden" name="title" value="Pay Now">
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
                                <button class="EscrowButtonPrimary" type="submit">Pay Now</button>
                            </form>

                            <!-- Hidden Tracking Pixel (if needed) -->
                            <img src="https://t.escrow.com/1px.gif?name=bin&price=55000&title=Pay%20Now%20&user_id=3381154"
                                style="display: none;">
                        </section>







                        <div id="location" class="card hidden">
                            <div class="card-header">Location</div>
                            <div class="card-body">
                                <h5>Your Live Location:</h5>
                                <p id="locationInfo"></p>
                            </div>
                        </div>

                        <div id="requestServiceInfo" class="card hidden">
                            <div class="card-header">Request Service</div>
                            <div class="card-body">
                                <div class="container">
                                    <h2>Submit Your Information</h2>
                                    <form id="complaintForm">
                                        <label for="description">Description:</label>
                                        <textarea id="complaintdescription" name="complaintdescription" required></textarea>

                                        <label for="date">Date:</label>
                                        <input type="date" id="complaintdate" name="complaintdate" required>

                                        <label for="type">Type:</label>
                                        <input type="text" id="complainttype" name="complainttype" required>

                                        <label for="contact">Contact No:</label>
                                        <input type="text" id="complaintcontact" name="complaintcontact" required>

                                        <label for="image">Add Image:</label>
                                        <input type="file" id="complaintimage" name="complaintimage" accept="image/*" required>

                                        <label for="price">Price:</label>
                                        <input type="number" id="complaintprice" name="complaintprice" required>

                                        <button type="submit">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="notification" class="card hidden">
                            <div class="card-header">Notification</div>
                            <div class="card-body">
                            <?php  $query = "SELECT notification FROM users WHERE login_id = ?";
                               $stmt = $conn->prepare($query);
                               $stmt->bind_param("i", $loginId);
                               $stmt->execute();
                               $stmt->bind_result($notification);
                               $stmt->fetch();
                               echo $notification;
                               ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Reverse Geocoding
                async function reverseGeocode(lat, lng) {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                    const data = await response.json();

                    if (data && data.address) {
                        const latitudeInput = document.getElementById('latitude');
                        const longitudeInput = document.getElementById('longitude');
                        const { road, suburb, city, state, country } = data.address;
                        latitudeInput.value = data.lat;
                        longitudeInput.value = data.lon;
                        let locationInfo = '';
                        if (road) locationInfo += `Street: ${road}, `;
                        if (suburb) locationInfo += `Area: ${suburb}, `;
                        if (city) locationInfo += `City: ${city}, `;
                        if (state) locationInfo += `State: ${state}, `;
                        if (country) locationInfo += `Country: ${country}`;
                        if (lat && lng) {
                            fetch('nearby_lawyers.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({ latitude: data.lat, longitude: data.lon, id: document.getElementById('userId').value })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    if (typeof data === 'object') {
                                        const lawyerDiv = document.getElementById('lawyerDiv');

                                        // Clear the div first
                                        lawyerDiv.innerHTML = '';

                                        data.map((record) => {
                                            const div = document.createElement('div');
                                            div.innerHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label>Name: </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div>${record.name}</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label>Contact Number: </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div>${record.contact_number}</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label>Experience: </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div>${record.experience}</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label>Distance: </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div>${record.distance}&nbsp;km</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `;

                                            // Append the new div to lawyerDiv
                                            lawyerDiv.appendChild(div);
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred');
                                });
                        }
                        return locationInfo.trim();
                    } else {
                        throw new Error("Unable to get address");
                    }
                }

                window.addEventListener('load', function () {

                    if (navigator.geolocation) {
                        // Start watching the user's position
                        watchId = navigator.geolocation.watchPosition(
                            function (position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;

                                reverseGeocode(lat, lng)
                                    .then(locationInfo => {
                                        console.log(locationInfo.lat, locationInfo.lon, locationInfo)
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

                function hideAllSections() {
                    document.querySelectorAll('.card').forEach(function (card) {
                        card.classList.add('hidden');
                    });
                }

                // Show Bidding Page when the corresponding link is clicked
                document.getElementById('biddingPageLink').addEventListener('click', function () {
                    hideAllSections(); // Hide other sections
                    document.getElementById('biddingPage').classList.remove('hidden'); // Show bidding page
                });

                // Add click event for other nav-links to hide the bidding page
                const navLinks = document.querySelectorAll('.nav-link');

                navLinks.forEach(function (link) {
                    link.addEventListener('click', function (event) {
                        const targetId = event.target.getAttribute('href').substring(1);
                        if (targetId !== 'biddingPage') {
                            hideAllSections(); // Hide all sections
                            document.getElementById(targetId).classList.remove('hidden'); // Show the selected section
                        }
                    });
                });

                // Ensure the default state hides all sections except for the first one if needed
                hideAllSections();
                document.addEventListener('DOMContentLoaded', function () {
                    const email = localStorage.getItem('loggedInUser');
                    const storedUser = localStorage.getItem(email);

                    // Show only the profile info by default
                    showContent('profileInfo');

                    const insuranceSchemes = JSON.parse(localStorage.getItem('insuranceSchemes')) || [];
                    const insuranceSchemesList = document.getElementById('insuranceSchemesList');
                    insuranceSchemes.forEach(scheme => {
                        const li = document.createElement('li');
                        li.textContent = scheme;
                        insuranceSchemesList.appendChild(li);
                    });

                    const lawyers = JSON.parse(localStorage.getItem('lawyers')) || [];
                    const lawyersList = document.getElementById('lawyersList');
                    lawyers.forEach(lawyer => {
                        const li = document.createElement('li');
                        li.textContent = lawyer.name + " - " + lawyer.specialization;
                        lawyersList.appendChild(li);
                    });

                    const paymentHistory = JSON.parse(localStorage.getItem('paymentHistory')) || [];
                    const paymentHistoryList = document.getElementById('paymentHistoryList');
                    paymentHistory.forEach(payment => {
                        const li = document.createElement('li');
                        li.textContent = payment.date + ": $" + payment.amount;
                        paymentHistoryList.appendChild(li);
                    });

                    const locationInfo = localStorage.getItem('location') || 'Location not set.';
                    document.getElementById('locationInfo').innerText = locationInfo;

                    // Get user's location
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(success, error);
                    } else {
                        setDefaultLocation();
                    }


                    function success(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;

                        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`)
                            .then(response => response.json())
                            .then(data => {
                                const city = data.address.city || 'Visakhapatnam';
                                const state = data.address.state || 'Andhra Pradesh';
                                const country = data.address.country || 'India';
                                const area = data.address.suburb || data.address.village || 'Nearby Area';

                                const locationDetails = `Area: ${area}`;
                                document.getElementById('locationInfo').innerText = locationDetails.trim();
                                localStorage.setItem('location', locationDetails.trim());
                            })
                            .catch(() => setDefaultLocation());
                    }


                    function error() {
                        setDefaultLocation();
                    }

                    function setDefaultLocation() {
                        const locationDetails = 'Area: Nearby Area, City: Visakhapatnam, State: Andhra Pradesh, Country: India, Latitude: N/A, Longitude: N/A';
                        document.getElementById('locationInfo').innerText = locationDetails;
                        localStorage.setItem('location', locationDetails);
                    }

                    document.getElementById('updateDetailsBtn').addEventListener('click', function () {
                        document.getElementById('profileForm').classList.toggle('hidden');
                    });

                    document.getElementById('profileForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        const name = document.getElementById('name').value;
                        const age = document.getElementById('age').value;
                        const gender = document.getElementById('gender').value;
                        const role = document.getElementById('role').value;
                        const contact = document.getElementById('contact').value;
                        formData.append('name', document.getElementById('name').value);
                        formData.append('age', document.getElementById('age').value);
                        formData.append('gender', document.getElementById('gender').value);
                        formData.append('role', document.getElementById('role').value);
                        formData.append('contactNumber', document.getElementById('contact').value);
                        formData.append('latitude', document.getElementById('latitude').value);
                        formData.append('longitude', document.getElementById('longitude').value);
                        formData.append('id', document.getElementById('userId').value);
                        const fileUpload = document.getElementById('fileUpload').files.length;

                        if (!age || !name || !gender || !role || !contact) {
                            alert('All fields are required.');
                            return; // Exit if validation fails
                        }

                        fetch('user.php', {
                            method: 'POST',
                            body: formData
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
                    });

                    document.getElementById('biddingForm').addEventListener('submit', function (event) {
                        event.preventDefault(); // Prevent the default form submission

                        // Collect form data
                        const formData = new FormData(this);
                        formData.append('latitude', document.getElementById('latitude').value);
                        formData.append('longitude', document.getElementById('longitude').value,);

                        // Optional: Add custom validation
                        const description = document.getElementById('caseDescription').value.trim();
                        const name = document.getElementById('bidderName').value.trim();
                        const contactNumber = document.getElementById('contactNumber').value.trim();
                        const caseuserId = document.getElementById('caseuserId').value.trim();
                        const fileUpload = document.getElementById('casefileUpload').files.length;

                        if (!description || !name || !contactNumber || fileUpload === 0 || !caseuserId) {
                            alert('All fields are required.');
                            return; // Exit if validation fails
                        }

                        // Use fetch to submit the form data
                        fetch('problem.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.text())
                            .then(data => {
                                if (data.includes('Case created successfully.')) {
                                    window.location.reload();
                                } else {
                                    alert(data); // Show any error messages from the server
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error occurred while creating case');
                            });
                    });

                    document.getElementById('complaintForm').addEventListener('submit', function (event) {
                        event.preventDefault(); // Prevent the default form submission

                        // Collect form data
                        const formData = new FormData(this);
                        formData.append('latitude', document.getElementById('latitude').value);
                        formData.append('longitude', document.getElementById('longitude').value,);
                        formData.append('login_id', document.getElementById('userId').value);

                        // Optional: Add custom validation
                        const complaintdescription = document.getElementById('complaintdescription').value.trim();
                        const complaintdate = document.getElementById('complaintdate').value.trim();
                        const complainttype = document.getElementById('complainttype').value.trim();
                        const complaintcontact = document.getElementById('complaintcontact').value.trim();
                        const complaintprice = document.getElementById('complaintprice').value.trim();
                        const image = document.getElementById('complaintimage').files.length;

                        if (!complaintdescription || !complaintdate || !complainttype || !complaintcontact || fileUpload === 0 || !complaintprice) {
                            alert('All fields are required.');
                            return; // Exit if validation fails
                        }

                        // Use fetch to submit the form data
                        fetch('complaint.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.text())
                            .then(data => {
                                if (data.includes('Submission successful!')) {
                                    window.location.reload();
                                } else {
                                    alert(data); // Show any error messages from the server
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error occurred while creating case');
                            });
                    });


                    document.getElementById('profileLink').addEventListener('click', () => showContent('profileInfo'));
                    document.getElementById('insuranceLink').addEventListener('click', () => showContent('insuranceSchemes'));
                    document.getElementById('hireLawyerLink').addEventListener('click', () => showContent('hireLawyer'));
                    document.getElementById('paymentSystemLink').addEventListener('click', () => showContent('paymentSystem'));
                    document.getElementById('locationLink').addEventListener('click', () => showContent('location'));
                    document.getElementById('requestServiceLink').addEventListener('click', () => showContent('requestServiceInfo'));

                    function showContent(contentId) {
                        const contents = ['profileInfo', 'insuranceSchemes', 'hireLawyer', 'paymentSystem', 'location', 'requestServiceInfo'];
                        contents.forEach(content => {
                            document.getElementById(content).classList.add('hidden');
                        });
                        document.getElementById(contentId).classList.remove('hidden');
                    }
                });


                // document.getElementById('logoutBtn').addEventListener('click', function (e) {
                //     e.preventDefault(); // Stop default action

                //     // Clear user session and storage
                //     sessionStorage.clear();
                //     localStorage.clear();
                //     document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"; // Delete session cookie

                //     // Redirect to login page
                //     window.location.href = 'login.html';
                // });

                // // Prevent back button from accessing the previous page after logout
                // window.history.pushState(null, null, window.location.href);
                // window.addEventListener('popstate', function () {
                //     sessionStorage.clear();
                //     localStorage.clear();
                //     document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                //     window.location.href = 'login.html'; // Force login if back button is pressed
                // });

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