document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    const map = L.map('map').setView([51.505, -0.09], 13);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Function to fetch and display incidents
    function loadIncidents() {
        fetch('../PHP/map-data.php')
            .then(response => response.json())
            .then(incidents => {
                // Clear existing markers if any
                if (window.incidentMarkers) {
                    window.incidentMarkers.forEach(marker => map.removeLayer(marker));
                }
                
                window.incidentMarkers = [];
                
                // Add markers for each incident
                incidents.forEach(incident => {
                    if (incident.lat && incident.lng) {
                        const marker = L.marker([incident.lat, incident.lng]).addTo(map);
                        marker.bindPopup(`
                            <h3>${incident.title}</h3>
                            <p><strong>Type:</strong> ${incident.type}</p>
                            <p>${incident.description}</p>
                            <small>Reported on: ${incident.date}</small>
                        `);
                        window.incidentMarkers.push(marker);
                    }
                });
                
                // Auto-fit map to show all markers if there are any
                if (incidents.length > 0 && window.incidentMarkers.length > 0) {
                    const group = new L.featureGroup(window.incidentMarkers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            })
            .catch(error => {
                console.error('Error loading incidents:', error);
            });
    }

    // Load incidents initially
    loadIncidents();
    
    // Refresh incidents every 5 minutes
    setInterval(loadIncidents, 300000);
});