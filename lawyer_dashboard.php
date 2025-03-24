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
    SELECT u.name, u.login_id, l.type, u.contact_number, u.specialization, u.experience, u.license_details, u.location_of_practice, u.qr_code_file, u.document_file, u.upi_id
    FROM login l 
    JOIN lawyer u ON l.id = u.login_id 
    WHERE l.username = ?
    ");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($name, $loginId, $category, $contact_number, $specialization, $experience, $license_details, $location_of_practice, $qrCodefileName, $documentFileName, $upiId);
        $stmt->fetch();
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Lawyer Dashboard</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.min.css" rel="stylesheet">
            <style>
    /* Neumorphic Background */
    body {
        font-family: 'Quicksand', sans-serif;
        background: linear-gradient(135deg,rgb(14, 14, 14),rgb(11, 11, 11)); /* Soft gradient */
        color: #2C3E50;
        margin: 0;
        padding: 0;
        display: flex;
        height: 100vh;
        justify-content: center;
        align-items: center;
    }

    #wrapper {
        display: flex;
        height: 100vh;
        width: 100%;
    }

    /* Glassmorphism Sidebar */
    #sidebar {
        width: 250px;
        background: rgba(74, 71, 71, 0.6);
        backdrop-filter: blur(10px);
        height: 100%;
        color: white;
        padding-top: 20px;
        position: fixed;
        box-shadow: 2px 0 10px rgba(83, 83, 83, 0.1);
    }

    #sidebar a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        display: block;
        transition: background-color 0.3s ease, padding-left 0.3s ease;
    }

    #sidebar .nav-link {
        padding: 15px 20px;
    }

    #sidebar a:hover {
        background-color: rgba(255, 255, 255, 0.2);
        padding-left: 15px;
    }

    #sidebar .sidebar-heading {
        padding: 10px 20px;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: bold;
        color: rgba(211, 204, 204, 0.9);
        border-bottom: 1px solid rgba(221, 217, 217, 0.94);
    }

    /* Glassmorphism Content Area */
    #content {
        margin-left: 250px;
        padding: 40px;
        flex-grow: 1;
        overflow-y: auto;
        background: linear-gradient(145deg,rgba(212, 213, 213, 0.96),rgb(185, 186, 185));
        box-shadow: inset 5px 5px 10pxrgb(131, 132, 135), inset -5px -5px 10pxrgb(168, 164, 164);
        border-left: 1px solid rgba(239, 234, 234, 0.92);
        border-radius: 20px;
    }

    /* Neumorphic Header */
    .card-header {
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 20px;
        font-size: 18px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    h2 {
        font-family: 'Playfair Display', serif;
        color: #333;
        margin-bottom: 20px;
    }

    /* Neumorphic Cards */
    .card {
        background: linear-gradient(145deg, #e3e9f0, #ffffff);
        border-radius: 15px;
        border: none;
        box-shadow: 5px 5px 10px #babecc, -5px -5px 10px #ffffff;
        margin-bottom: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 10px 10px 15px #babecc, -10px -10px 15px #ffffff;
    }

    .card-body {
        padding: 20px;
        color: #2C3E50;
        line-height: 1.6;
    }

    /* Fixed Neumorphic Button */
    .btn-primary {
        background: linear-gradient(145deg, #e3e9f0, #ffffff);
        border: none;
        padding: 12px 25px;
        border-radius: 50px;
        box-shadow: 5px 5px 10px #babecc, -5px -5px 10px #ffffff;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #2C3E50;
        text-align: center;
        cursor: pointer;
        outline: none;
    }

    /* Button Press Effect */
    .btn-primary:hover {
        background: #e0e5ec;
        box-shadow: inset 5px 5px 10px #babecc, inset -5px -5px 10px #ffffff;
        color: #2C3E50;
    }

    /* Button Click Effect */
    .btn-primary:active {
        box-shadow: inset 3px 3px 6px #babecc, inset -3px -3px 6px #ffffff;
        transform: scale(0.98);
    }

    .hidden {
        display: none;
    }

    .star-rating {
        color: #FFD700;
    }

    .review-item {
        margin-bottom: 10px;
    }

    /* Smooth Transitions */
    a,
    button {
        transition: all 0.3s ease;
    }

    a:hover,
    button:hover {
        opacity: 0.9;
    }

    .case-history-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.case-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.case-body img {
    max-width: 100%;
    height: auto;
    margin-top: 1rem;
}

.case-footer {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}
</style>

        </head>

        <body>

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <div id="wrapper">
                <nav id="sidebar">
                    <div class="sidebar-header text-center">
                        <h3><a href="index.html"><i class="bi bi-box-arrow-right"></i></a> Lawyer Dashboard</h3>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#profileInfo" id="profileLink">
                                <i class="bi bi-person"></i> Profile Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#caseRequests" id="caseRequestLink">
                                <i class="bi bi-file-earmark"></i> Case Requests
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#solvableCases" id="solvableCasesLink">
                                <i class="bi bi-check-circle"></i> Case History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reviewsRatings" id="reviewsRatingsLink">
                                <i class="bi bi-star"></i> Reviews & Ratings
                            </a>
                        </li>
                    </ul>
                </nav>

                <div id="content">
                    <div class="container">
                        <h2>Welcome, <span id="welcomeName"> Advocate</span>!</h2>

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
                                    <strong>Specialization:</strong>
                                    <span id="displaySpecialization">
                                        <?php echo $specialization ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Experience:</strong>
                                    <span id="displayExperience">
                                        <?php echo $experience ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>License ID:</strong>
                                    <span id="displayLicense">
                                        <?php echo $license_details ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Contact Number:</strong>
                                    <span id="contactNumber">
                                        <?php echo $contact_number ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Location/Region of Practice:</strong>
                                    <span id="displayLocation">
                                        <?php echo $location_of_practice ?>
                                    </span>
                                </p>
                                <p>
                                    <strong>Document:</strong>
                                    <span>
                                        <img src='<?php echo $documentFileName ?>' width="450" height="250" />
                                    </span>
                                </p>
                                <p>
                                    <strong>QR CODE:</strong>
                                    <span>
                                        <img src='<?php echo $qrCodefileName ?>' width="450" height="250" />
                                    </span>
                                </p>
                                <p>
                                    <strong>UPI ID:</strong>
                                    <span>
                                        <?php echo $upiId ?>
                                    </span>
                                </p>

                                <button id="updateDetailsBtn" class="btn btn-primary">Update Details</button>

                                <form id="profileForm" class="hidden mt-3">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" value="<?php echo $name; ?>" id="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="specialization" class="form-label">Specialization</label>
                                        <input type="text" class="form-control" value="<?php echo $specialization; ?>"
                                            id="specialization" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="experience" class="form-label">Experience (Years)</label>
                                        <input type="number" class="form-control" value="<?php echo $experience; ?>"
                                            id="experience" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="license" class="form-label">License ID</label>
                                        <input type="text" class="form-control" value="<?php echo $license_details; ?>"
                                            id="license" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location/Region of Practice</label>
                                        <input type="text" class="form-control" value="<?php echo $location_of_practice; ?>"
                                            id="location" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" value="<?php echo $contact_number; ?>"
                                            id="contact_number" required>
                                    </div>

                                    <!-- New Field for Document Upload -->
                                    <div class="mb-3">
                                        <label for="document" class="form-label">Upload Document</label>
                                        <input type="file" class="form-control" id="document" name="document">
                                    </div>

                                    <input type="hidden" value="<?php echo $loginId ?>" id="userId">

                                    <!-- New Field for QR Upload -->
                                    <div class="mb-3">
                                        <label for="qrCode" class="form-label">Upload QR Code</label>
                                        <input type="file" class="form-control" id="qrCode" name="qrCode">
                                    </div>

                                    <!-- New Text Field for UPI ID or Bank Account -->
                                    <div class="mb-3">
                                        <label for="paymentDetails" class="form-label">UPI ID or Bank Account</label>
                                        <input type="text" class="form-control" id="paymentDetails" name="upiId"
                                            placeholder="Enter UPI ID or Bank Account" value="<?php echo $upiId ?>">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Information</button>
                                </form>
                            </div>
                        </div>

                        <div id="caseRequests" class="card">
                            <div class="card-header">Case Requests</div>
                            <div class="card-body">
                                <ul id="caseRequestsList"></ul>
                                <div id="usersDiv"></div>
                            </div>
                        </div>

                        <!-- <div id="addCase" class="card">
                    <div class="card-header">Add a Case You Can Solve</div>
                    <div class="card-body">
                        <form id="addCaseForm">
                            <div class="mb-3">
                                <label for="caseDetails" class="form-label">Case Details</label>
                                <textarea class="form-control" id="caseDetails" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="caseBid" class="form-label">Bid Amount</label>
                                <input type="number" class="form-control" id="caseBid" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Case</button>
                        </form>
                    </div>
                </div> -->

                        <div id="solvableCases" class="card">
                            <div class="card-header">Solved cases</div>
                            <div class="card-body">
                                <ul id="solvableCasesList"></ul>
                                <div id="solvedCasesDiv"></div>
                            </div>
                        </div>

                        <div id="caseHistory" class="card">
                            <div class="card-header">CASE HISTORY</div>
                            <div class="card-body">
                                <ul id="caseHistoryList"></ul>
                                <form id="caseHistoryForm" class="mt-3">
                                    <div class="mb-3">
                                        <label for="solvedCase" class="form-label">Solved Case</label>
                                        <input type="text" class="form-control" id="solvedCase" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Solved Case</button>
                                </form>
                            </div>
                        </div>

                        <div id="reviewsRatings" class="card">
                            <div class="card-header">Reviews and Ratings</div>
                            <div class="card-body">
                                <h5>Average Rating: <span id="averageRating"></span></h5>
                                <ul id="reviewsList"></ul>
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
                        let locationInfo = '';
                        if (road) locationInfo += `Street: ${road}, `;
                        if (suburb) locationInfo += `Area: ${suburb}, `;
                        if (city) locationInfo += `City: ${city}, `;
                        if (state) locationInfo += `State: ${state}, `;
                        if (country) locationInfo += `Country: ${country}`;
                        if (lat && lng) {
                            latitudeInput.value = data.lat;
                            longitudeInput.value = data.lon;
                            console.log(lat, lng)
                            fetch('nearby_cases.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    latitude: data.lat,
                                    longitude: data.lon,
                                    user_id: document.getElementById('userId').value
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (typeof data === 'object') {
                                        const lawyerDiv = document.getElementById('usersDiv');
                                        lawyerDiv.innerHTML = '';

                                        data.forEach((record) => {
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
                                                                                                                                                                                                                                                                                                                                                                                <label>Description: </label>
                                                                                                                                                                                                                                                                                                                                                                                <div>${record.description}</div>
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                                <label>Image: </label>
                                                                                                                                                                                                                                                                                                                                                                                <img width='450' height='250' src='${record.file_name}' />
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                                <label>Distance: </label>
                                                                                                                                                                                                                                                                                                                                                                                <div>${record.distance}</div>
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                                <button id="accept-button-${record.case_id}">
                                                                                                                                                                                                                                                                                                                                                                                    ${record.status === 'pending' ? 'Case Solved' : 'Accept Request'}
                                                                                                                                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                    `;

                                            // Append the new div to lawyerDiv
                                            lawyerDiv.appendChild(div);

                                            // Add click event listener to the button
                                            const acceptButton = document.getElementById(`accept-button-${record.case_id}`);
                                            acceptButton.onclick = () => handleAcceptRequest(record.case_id, record.status === 'pending' ? 'solved' : 'pending', <?php echo $loginId ?>);
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred');
                                });
                            fetch('lawyer_solved_cases.php', {
                                method: 'POST',
                                body: new URLSearchParams({
                                    latitude: data.lat,
                                    longitude: data.lon,
                                    user_id: document.getElementById('userId').value
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (typeof data === 'object') {
                                        const lawyerDiv = document.getElementById('solvedCasesDiv');
                                        lawyerDiv.innerHTML = '';

                                        data.forEach((record) => {
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
                                                                                                                                                                                                                                                                                                                                                                                <label>Description: </label>
                                                                                                                                                                                                                                                                                                                                                                                <div>${record.description}</div>
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                                <label>Image: </label>
                                                                                                                                                                                                                                                                                                                                                                                <img width='450' height='250' src='${record.file_name}' />
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                                <label>Status: </label>
                                                                                                                                                                                                                                                                                                                                                                                CASE SOLVED
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

        // Update the handleAcceptRequest function
function handleAcceptRequest(id, action, loginId) {
    fetch('change_case_status.php', {
        method: 'POST',
        body: new URLSearchParams({
            id: id, 
            action: action, 
            userId: loginId
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.status === 'SUCCESS') {
            // Remove the accepted case from requests
            const acceptedCase = document.getElementById(`case-${id}`);
            if (acceptedCase) acceptedCase.remove();
            
            // Add to case history immediately
            addToCaseHistory(data.caseDetails);
            
            // Refresh solved cases list
            loadSolvedCases();
        } else {
            alert('Failed to accept request: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred: ' + error.message);
    });
}

// New function to add cases to history
function addToCaseHistory(caseDetails) {
    const historyList = document.getElementById('solvedCasesDiv');
    
    const caseElement = document.createElement('div');
    caseElement.className = 'col-md-6 mb-3';
    caseElement.innerHTML = `
        <div class="case-history-card">
            <div class="case-header">
                <h5>${caseDetails.name}</h5>
                <span class="badge bg-success">Solved</span>
            </div>
            <div class="case-body">
                <p><strong>Contact:</strong> ${caseDetails.contact_number}</p>
                <p><strong>Description:</strong> ${caseDetails.description}</p>
                <img src="${caseDetails.file_name}" class="img-thumbnail" alt="Case evidence">
                <div class="case-footer">
                    <small class="text-muted">Solved on: ${new Date().toLocaleDateString()}</small>
                </div>
            </div>
        </div>
    `;
    
    historyList.prepend(caseElement);
}

// Modified loadSolvedCases function
function loadSolvedCases(lat, lng) {
    fetch('lawyer_solved_cases.php', {
        method: 'POST',
        body: new URLSearchParams({
            latitude: lat,
            longitude: lng,
            user_id: document.getElementById('userId').value
        })
    })
    .then(response => response.json())
    .then(data => {
        const historyList = document.getElementById('solvedCasesDiv');
        historyList.innerHTML = '';
        
        data.forEach((record) => {
            const caseElement = document.createElement('div');
            caseElement.className = 'col-md-6 mb-3';
            caseElement.innerHTML = `
                <div class="case-history-card">
                    <div class="case-header">
                        <h5>${record.name}</h5>
                        <span class="badge bg-success">Solved</span>
                    </div>
                    <div class="case-body">
                        <p><strong>Contact:</strong> ${record.contact_number}</p>
                        <p><strong>Description:</strong> ${record.description}</p>
                        <img src="${record.file_name}" class="img-thumbnail" alt="Case evidence">
                        <div class="case-footer">
                            <small class="text-muted">Solved on: ${new Date(record.solved_date).toLocaleDateString()}</small>
                        </div>
                    </div>
                </div>
            `;
            historyList.appendChild(caseElement);
        });
    })
    .catch(error => console.error('Error loading solved cases:', error));
}

// Update the case request rendering
function renderCaseRequests(cases) {
    const container = document.getElementById('usersDiv');
    container.innerHTML = cases.map(caseItem => `
        <div class="col-md-6 mb-3" id="case-${caseItem.case_id}">
            <div class="case-request-card">
                <h5>${caseItem.name}</h5>
                <p class="text-muted">${caseItem.description}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge-status bg-primary">
                        ${caseItem.distance} km away
                    </span>
                    <button class="btn btn-legal" 
                        onclick="handleAcceptRequest(${caseItem.case_id}, 'solved', <?php echo $loginId ?>)">
                        Accept Case
                    </button>
                </div>
                ${caseItem.file_name ? `<img src="${caseItem.file_name}" class="img-thumbnail mt-2" alt="Case evidence">` : ''}
            </div>
        </div>
    `).join('');
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

                document.addEventListener('DOMContentLoaded', function () {
                    const email = 'user@example.com'; // Assume user is logged in
                    const storedUser = localStorage.getItem(email);

                    if (storedUser) {
                        const userData = JSON.parse(storedUser);
                        document.getElementById('welcomeName').textContent = userData.username;

                        // Load saved profile information
                        document.getElementById('displayName').textContent = userData.profile.name || '';
                        document.getElementById('displaySpecialization').textContent = userData.profile.specialization || '';
                        document.getElementById('displayExperience').textContent = userData.profile.experience || '';
                        document.getElementById('displayLicense').textContent = userData.profile.license || '';
                        document.getElementById('displayLocation').textContent = userData.profile.location || '';
                    }

                    // Profile Information Update
                    const profileForm = document.getElementById('profileForm');
                    const updateDetailsBtn = document.getElementById('updateDetailsBtn');
                    const displayElements = {
                        name: document.getElementById('displayName'),
                        specialization: document.getElementById('displaySpecialization'),
                        experience: document.getElementById('displayExperience'),
                        license: document.getElementById('displayLicense'),
                        location: document.getElementById('displayLocation')
                    };

                    updateDetailsBtn.addEventListener('click', function () {
                        profileForm.classList.toggle('hidden');
                    });

                    profileForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const formData = new FormData(this)
                        formData.append('name', document.getElementById('name').value);
                        formData.append('specialization', document.getElementById('specialization').value);
                        formData.append('experience', document.getElementById('experience').value);
                        formData.append('license', document.getElementById('license').value);
                        formData.append('contact_number', document.getElementById('contact_number').value);
                        formData.append('location', document.getElementById('location').value);
                        formData.append('latitude', document.getElementById('latitude').value);
                        formData.append('longitude', document.getElementById('longitude').value);
                        formData.append('id', document.getElementById('userId').value);

                        // Update display
                        fetch('lawyer.php', {
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

                    // Handle case submissions
                    // const addCaseForm = document.getElementById('addCaseForm');
                    const solvableCasesList = document.getElementById('solvableCasesList');

                    // addCaseForm.addEventListener('submit', function (e) {
                    //     e.preventDefault();
                    //     const caseDetails = document.getElementById('caseDetails').value;
                    //     const caseBid = document.getElementById('caseBid').value;

                    //     // Store case in local storage
                    //     const userCases = JSON.parse(localStorage.getItem(email)) || { cases: [] };
                    //     userCases.cases.push({ details: caseDetails, bid: caseBid });
                    //     localStorage.setItem(email, JSON.stringify(userCases));

                    //     // Clear form
                    //     addCaseForm.reset();
                    //     displaySolvableCases();
                    // });

                    const displaySolvableCases = () => {
                        solvableCasesList.innerHTML = ''; // Clear the current list
                        const userCases = JSON.parse(localStorage.getItem(email))?.cases || [];
                        userCases.forEach(caseInfo => {
                            const caseElement = document.createElement('li');
                            caseElement.textContent = `${caseInfo.details} - Bid: ${caseInfo.bid}`;
                            solvableCasesList.appendChild(caseElement);
                        });
                    };

                    displaySolvableCases();

                    // Reviews and Ratings Display
                    const reviewsList = document.getElementById('reviewsList');
                    const averageRatingDisplay = document.getElementById('averageRating');

                    const reviews = [
                        { review: "Excellent lawyer, very helpful!", rating: 5 },
                        { review: "Good service, but response time could be faster.", rating: 4 },
                        { review: "Satisfied with the legal support.", rating: 4 },
                        { review: "Very professional, highly recommended!", rating: 5 }
                    ];

                    let totalRating = 0;

                    reviews.forEach(function (reviewObj) {
                        const reviewItem = document.createElement('li');
                        reviewItem.classList.add('review-item');
                        reviewItem.innerHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="star-rating">${'★'.repeat(reviewObj.rating)}${'☆'.repeat(5 - reviewObj.rating)}</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <p>${reviewObj.review}</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `;
                        reviewsList.appendChild(reviewItem);
                        totalRating += reviewObj.rating;
                    });

                    const averageRating = (totalRating / reviews.length).toFixed(1);
                    averageRatingDisplay.textContent = `${averageRating} / 5`;

                    // Navigation Logic for Side Panel
                    const sections = {
                        profileLink: 'profileInfo',
                        caseRequestLink: 'caseRequests',
                        caseHistoryLink: 'caseHistory',
                        solvableCasesLink: 'solvableCases',
                        reviewsRatingsLink: 'reviewsRatings'
                    };

                    for (const linkId in sections) {
                        document.getElementById(linkId).addEventListener('click', function () {
                            for (const sectionId in sections) {
                                document.getElementById(sections[sectionId]).classList.add('hidden');
                            }
                            document.getElementById(sections[linkId]).classList.remove('hidden');
                        });
                    }
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