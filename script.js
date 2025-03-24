// Geolocation and form sliding logic
const loginForm = document.querySelector("form.login");
const signupForm = document.querySelector("form.signup");
const signupBtn = document.querySelector("label.signup");
const signupLink = document.querySelector(".signup-link a");
const loginText = document.querySelector(".title-text .login");

let watchId = null;

signupBtn.addEventListener("click", () => {
    loginForm.style.marginLeft = "-50%";
    loginText.style.marginLeft = "-50%";
});

// Geolocation API for detecting live location
window.addEventListener('load', function () {
    const status = document.getElementById('locationStatus');
    const submitBtn = document.getElementById('signupBtn');

    if (navigator.geolocation) {
        // Start watching the user's position
        watchId = navigator.geolocation.watchPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;



                reverseGeocode(lat, lng)
                    .then(locationInfo => {
                        status.textContent = `Current Location: ${locationInfo}. Latitude: ${lat.toFixed(4)}, Longitude: ${lng.toFixed(4)}`;
                        status.style.color = 'green';
                        submitBtn.disabled = false;
                    })
                    .catch(error => {
                        status.textContent = 'Unable to detect exact location.';
                        status.style.color = 'red';
                        submitBtn.disabled = false;
                    });
            },
            function () {
                status.textContent = 'Unable to retrieve your location. Please enable location services.';
                submitBtn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
        );
    } else {
        status.textContent = 'Geolocation is not supported by this browser.';
        submitBtn.disabled = false;
    }
});

// Reverse Geocoding
async function reverseGeocode(lat, lng) {
    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
    const data = await response.json();

    const latitudeInput = document.getElementById('signuplatitude');
    const longitudeInput = document.getElementById('signuplongitude');
    const loginlatitudeInput = document.getElementById('loginlatitude');
    const loginlongitudeInput = document.getElementById('loginlongitude');

    if (data && data.address) {
        const { road, suburb, city, state, country } = data.address;
        let locationInfo = '';
        if (road) locationInfo += `Street: ${road}, `;
        if (suburb) locationInfo += `Area: ${suburb}, `;
        if (city) locationInfo += `City: ${city}, `;
        if (state) locationInfo += `State: ${state}, `;
        if (country) locationInfo += `Country: ${country}`;
        console.log(data.lat, data.lon)
        latitudeInput.value = data.lat;
        longitudeInput.value = data.lon;
        loginlatitudeInput.value = data.lat;
        loginlongitudeInput.value = data.lon;
        return locationInfo.trim();
    } else {
        throw new Error("Unable to get address");
    }
}

// Handle signup form submission
document.getElementById('signupForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    const email = document.getElementById('signupEmail').value;
    const username = document.getElementById('signupUsername').value;
    const password = document.getElementById('signupPassword').value;
    const category = document.getElementById('category').value;

    // Create user details object
    const userDetails = {
        username: username,
        email: email,
        password: password,
        category: category,
        latitude: document.getElementById('signuplatitude').value,
        longitude: document.getElementById('signuplongitude').value
    };

    // Send the data to the server using fetch
    fetch('signup.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
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


// Handle login form submission
document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    // Create user details object
    const userDetails = {
        email: email,
        password: password,
        latitude: document.getElementById('loginlatitude').value,
        longitude: document.getElementById('loginlongitude').value
    };

    // Send the data to the server using fetch
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(userDetails)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success === true) {
                if (data.category === 'customer') {
                    window.location.href = 'customer_dashboard.php';
                }
                else if (data.category === 'advocate') {
                    window.location.href = 'lawyer_dashboard.php';
                }
                else if (data.category === 'agencies') {
                    window.location.href = 'service_dashboard.php';
                }
            }
            alert(data.message)
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Login failed. Please try again.');
        });
});

// On page load, check if user is logged in and retrieve their details
document.addEventListener('DOMContentLoaded', function () {
    const email = localStorage.getItem('loggedInUser'); // Retrieve logged-in user's email

    if (email) {
        const userDetails = JSON.parse(localStorage.getItem(email)); // Get user details from local storage

        // if (userDetails) {
        //     alert('Welcome back, ' + userDetails.name + '!');
        //     // Optionally, you can auto-redirect to the appropriate dashboard
        // }
    }
});
// Improved JavaScript for Login-Signup with Location Detection

document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("form.login");
    const signupForm = document.querySelector("form.signup");
    const loginText = document.querySelector(".title-text .login");
    const signupBtn = document.querySelector("label.signup");
    const loginBtn = document.querySelector("label.login");
    const locationStatus = document.getElementById("locationStatus");
    const signupSubmitBtn = document.getElementById("signupBtn");
    let watchId = null;

    // Toggle Forms
    signupBtn.addEventListener("click", () => {
        loginForm.style.marginLeft = "-50%";
        loginText.style.marginLeft = "-50%";
    });
    
    loginBtn.addEventListener("click", () => {
        loginForm.style.marginLeft = "0%";
        loginText.style.marginLeft = "0%";
    });

    // Detect location on load
    if (navigator.geolocation) {
        watchId = navigator.geolocation.watchPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                reverseGeocode(lat, lng)
                    .then(locationInfo => {
                        locationStatus.textContent = `Current Location: ${locationInfo}. Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
                        locationStatus.style.color = "yellow";
                        signupSubmitBtn.disabled = false;
                        document.getElementById("signuplatitude").value = lat;
                        document.getElementById("signuplongitude").value = lng;
                        document.getElementById("loginlatitude").value = lat;
                        document.getElementById("loginlongitude").value = lng;
                    })
                    .catch(() => {
                        locationStatus.textContent = "Unable to detect exact location.";
                        locationStatus.style.color = "red";
                    });
            },
            () => {
                locationStatus.textContent = "Location access denied. Enable location services.";
                locationStatus.style.color = "red";
            },
            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
        );
    } else {
        locationStatus.textContent = "Geolocation is not supported by this browser.";
        locationStatus.style.color = "red";
    }

    // Reverse Geocoding Function
    async function reverseGeocode(lat, lng) {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
        const data = await response.json();
        if (data && data.address) {
            const { road, suburb, city, state, country } = data.address;
            return `${road || ""}, ${suburb || ""}, ${city || ""}, ${state || ""}, ${country || ""}`.replace(/, ,/g, ",").trim();
        } else {
            throw new Error("Unable to get address");
        }
    }

    // Handle Signup Form Submission
    document.getElementById("signupForm").addEventListener("submit", function (event) {
        event.preventDefault();
        submitForm("signup.php", "signupForm");
    });

    // Handle Login Form Submission
    document.getElementById("loginForm").addEventListener("submit", function (event) {
        event.preventDefault();
        submitForm("login.php", "loginForm");
    });

    function submitForm(url, formId) {
        const formData = new FormData(document.getElementById(formId));
        fetch(url, {
            method: "POST",
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                const redirectPage = {
                    "customer": "customer_dashboard.php",
                    "advocate": "lawyer_dashboard.php",
                    "agencies": "service_dashboard.php"
                }[data.category];
                if (redirectPage) window.location.href = redirectPage;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Request failed. Please try again.");
        });
    }
});
