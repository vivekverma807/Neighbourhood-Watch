document.addEventListener("DOMContentLoaded", function () {
    const GEOCODING_API_URL = 'https://nominatim.openstreetmap.org/search';

    // Initialize the map
    const mapContainer = document.getElementById("map");
    let map;

    if (mapContainer) {
        map = L.map("map").setView([28.594195526353836, 77.2089685886433], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            // maxZoom: 19,
            attribution: "&copy; OpenStreetMap contributors",
        }).addTo(map);

        // Fix for map display issues
        setTimeout(() => {
            map.invalidateSize();
        }, 500);
    }

    // Fetch coordinates from address
    async function fetchCoordinatesFromAddress(address) {
        try {
            const response = await fetch(`${GEOCODING_API_URL}?q=${encodeURIComponent(address)}&format=json`);
            if (!response.ok) throw new Error("Failed to fetch coordinates.");
            const data = await response.json();
            if (data.length > 0) return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
        } catch (error) {
            alert("Error fetching coordinates. Please check your address.");
            console.error(error);
        }
        return null;
    }

    // Add incident to map
    function addIncidentToMap(incident) {
        const [latitude, longitude] = incident.location;
        const marker = L.marker([latitude, longitude]).addTo(map);
        marker.bindPopup(`<strong>${incident.title}</strong><br>${incident.description}<br><small>(${latitude}, ${longitude})</small>`).openPopup();
        map.setView([latitude, longitude], 13);
    }

    // Save incidents to localStorage
    function saveIncident(incident) {
        let incidents = JSON.parse(localStorage.getItem("incidents")) || [];
        incidents.push(incident);
        localStorage.setItem("incidents", JSON.stringify(incidents));
    }

    // Load incidents from localStorage
    // function loadIncidents() {
    //     let incidents = JSON.parse(localStorage.getItem("incidents")) || [];
    //     incidents.forEach(addIncidentToMap);
    // }

    // if (mapContainer) {
    //     loadIncidents();
    // }

    // Handle form submission
    const form = document.getElementById("incident-form");
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            // const username = document.getElementById("username").value.trim();
            const title = document.getElementById("title").value.trim();
            const description = document.getElementById("description").value.trim();
            const address = document.getElementById("address").value.trim();
            const locationInput = document.getElementById("location").value.trim();

            if (!title || !description) {
                alert("Title and description are required!");
                return;
            }

            let coordinates;

            if (locationInput) {
                const [lat, lon] = locationInput.split(",").map(Number);
                if (isNaN(lat) || isNaN(lon)) {
                    alert("Invalid latitude, longitude format.");
                    return;
                }
                coordinates = [lat, lon];
            } else if (address) {
                coordinates = await fetchCoordinatesFromAddress(address);
                if (!coordinates) {
                    alert("Could not find location for the given address.");
                    return;
                }
            } else {
                alert("Please provide either an address or latitude/longitude.");
                return;
            }

            const incident = { title, description, location: coordinates };
            saveIncident(incident);
            addIncidentToMap(incident);
            form.reset();
        });
    }
});

document.getElementById("toggle-password").addEventListener("click", function() {
    let passwordInput = document.getElementById("password");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
});

// Add this to your existing script.js or create a new file for form handling
document.getElementById('incident-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../PHP/incident-process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Incident reported successfully!');
            // Optionally redirect to map page
            window.location.href = '../HTML/map.html';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the report');
    });
});