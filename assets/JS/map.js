document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    const map = L.map('map').setView([51.505, -0.09], 13);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Function to create detailed popup content
    function createPopupContent(incident) {
        let textContent = `
            <div class="incident-text">
                <h3>${incident.title}</h3>
                <div class="incident-detail">
                    <span class="detail-label">Type:</span> ${incident.type}
                </div>
                <div class="incident-detail">
                    <span class="detail-label">Date:</span> ${incident.date}
                </div>
                <div class="incident-detail">
                    <span class="detail-label">Time:</span> ${incident.time}
                </div>
                <div class="incident-detail">
                    <span class="detail-label">Location:</span> ${incident.address}
                </div>`;
        
        // Add contact info if available
        if (incident.contact_info) {
            textContent += `
                <div class="incident-detail">
                    <span class="detail-label">Contact:</span> ${incident.contact_info}
                </div>`;
        }
        
        textContent += `
                <div class="incident-detail">
                    <span class="detail-label">Description:</span> 
                    <p>${incident.description}</p>
                </div>
            </div>`;
        
        // Add evidence image or default image
        let imageContent = '';
        const defaultImage = '../uploads/default.jpg';
        const hasEvidence = incident.evidence_url && !incident.evidence_url.includes('default.jpg');
        
        if (hasEvidence) {
            const fileExt = incident.evidence_url.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);
            
            if (isImage) {
                imageContent = `
                <div class="incident-image">
                    <div class="evidence-label">Evidence:</div>
                    <img src="${incident.evidence_url}" alt="Incident Evidence" onerror="this.src='${defaultImage}'">
                </div>`;
            } else {
                imageContent = `
                <div class="incident-image">
                    <div class="evidence-label">Evidence:</div>
                    <a href="${incident.evidence_url}" target="_blank">View attached file</a>
                </div>`;
            }
        } else {
            imageContent = `
            <div class="incident-image">
                <img src="${defaultImage}" alt="Default Incident Image">
            </div>`;
        }
        
        return `<div class="incident-popup">${textContent}${imageContent}</div>`;
    }

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
                        marker.bindPopup(createPopupContent(incident), {
                            maxWidth: 500,
                            minWidth: 400
                        });
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
                L.popup()
                    .setLatLng(map.getCenter())
                    .setContent('Failed to load incident data. Please try again later.')
                    .openOn(map);
            });
    }

    // Load incidents initially
    loadIncidents();
    
    // Refresh incidents every 5 minutes
    setInterval(loadIncidents, 300000);
});