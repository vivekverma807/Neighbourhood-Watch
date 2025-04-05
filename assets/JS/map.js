document.addEventListener('DOMContentLoaded', function() {
    // Configuration constants
    const DELHI_COORDINATES = [28.682847, 77.225004];
    const DEFAULT_ZOOM = 12;
    const REFRESH_INTERVAL = 300000; // 5 minutes in milliseconds
    const DEFAULT_IMAGE = '../uploads/default.jpg';
    const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    const POPUP_MIN_WIDTH = 300;
    const POPUP_MAX_WIDTH = 500;
    
    // Initialize the map with Delhi coordinates
    const map = L.map('map').setView(DELHI_COORDINATES, DEFAULT_ZOOM);
    
    // Add tile layer with error handling
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
        detectRetina: true
    }).addTo(map);

    // Fallback tile layer in case OSM fails
    const esriLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri'
    });

    // Handle tile layer errors
    osmLayer.on('tileerror', function() {
        console.warn('OpenStreetMap tiles failed, switching to Esri');
        map.removeLayer(osmLayer);
        esriLayer.addTo(map);
    });

    // Store markers globally with namespace
    const markers = {
        incidents: [],
        clear: function() {
            this.incidents.forEach(marker => map.removeLayer(marker));
            this.incidents = [];
        }
    };

    // Create detailed popup content with improved structure
    function createPopupContent(incident) {
        // Create text content section
        const textContent = `
            <div class="incident-text">
                <h3>${escapeHTML(incident.title)}</h3>
                <div class="incident-details">
                    <div class="incident-detail">
                        <span class="detail-label">Type:</span> ${escapeHTML(incident.type)}
                    </div>
                    <div class="incident-detail">
                        <span class="detail-label">Date:</span> ${formatDate(incident.date)}
                    </div>
                    <div class="incident-detail">
                        <span class="detail-label">Time:</span> ${formatTime(incident.time)}
                    </div>
                    <div class="incident-detail">
                        <span class="detail-label">Location:</span> ${escapeHTML(incident.address)}
                    </div>
                    ${incident.contact_info ? `
                    <div class="incident-detail">
                        <span class="detail-label">Contact:</span> ${escapeHTML(incident.contact_info)}
                    </div>` : ''}
                    <div class="incident-detail">
                        <span class="detail-label">Description:</span> 
                        <p>${escapeHTML(incident.description)}</p>
                    </div>
                </div>
            </div>`;
        
        // Create image content section
        const imageContent = createImageContent(incident);
        
        return `<div class="incident-popup">${textContent}${imageContent}</div>`;
    }

    // Helper function to create image content
    function createImageContent(incident) {
        if (!incident.evidence_url || incident.evidence_url.includes('default.jpg')) {
            return `
            <div class="incident-image">
                <img src="${DEFAULT_IMAGE}" alt="Default Incident Image" loading="lazy">
            </div>`;
        }

        const fileExt = incident.evidence_url.split('.').pop().toLowerCase();
        const isImage = ALLOWED_IMAGE_EXTENSIONS.includes(fileExt);
        
        if (isImage) {
            return `
            <div class="incident-image">
                <div class="evidence-label">Evidence:</div>
                <img src="${incident.evidence_url}" alt="Incident Evidence" loading="lazy" onerror="this.src='${DEFAULT_IMAGE}'">
            </div>`;
        } else {
            return `
            <div class="incident-image">
                <div class="evidence-label">Evidence:</div>
                <a href="${incident.evidence_url}" target="_blank" rel="noopener noreferrer">View attached file</a>
            </div>`;
        }
    }

    // Function to fetch and display incidents with better error handling
    async function loadIncidents() {
        try {
            showLoadingIndicator();
            
            const response = await fetch('../PHP/map-data.php');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const incidents = await response.json();
            
            // Clear existing markers
            markers.clear();
            
            // Add markers for each valid incident
            incidents.forEach(incident => {
                if (incident.lat && incident.lng) {
                    const marker = L.marker([incident.lat, incident.lng], {
                        riseOnHover: true
                    }).addTo(map);
                    
                    marker.bindPopup(createPopupContent(incident), {
                        maxWidth: POPUP_MAX_WIDTH,
                        minWidth: POPUP_MIN_WIDTH,
                        autoClose: false,
                        closeOnClick: false
                    });
                    
                    markers.incidents.push(marker);
                }
            });
            
            // Auto-fit map to show all markers if there are any
            if (markers.incidents.length > 0) {
                const group = new L.featureGroup(markers.incidents);
                map.fitBounds(group.getBounds().pad(0.1));
            }
            
            hideLoadingIndicator();
        } catch (error) {
            console.error('Error loading incidents:', error);
            hideLoadingIndicator();
            showErrorPopup('Failed to load incident data. Please try again later.');
        }
    }

    // Helper functions
    function escapeHTML(str) {
        return str ? str.replace(/&/g, '&amp;')
                     .replace(/</g, '&lt;')
                     .replace(/>/g, '&gt;')
                     .replace(/"/g, '&quot;')
                     .replace(/'/g, '&#039;') : '';
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            return new Date(dateString).toLocaleDateString();
        } catch {
            return dateString;
        }
    }

    function formatTime(timeString) {
        if (!timeString) return 'N/A';
        return timeString;
    }

    function showLoadingIndicator() {
        // You can implement a loading spinner here
        console.log('Loading incidents...');
    }

    function hideLoadingIndicator() {
        // Hide loading spinner
        console.log('Loading complete');
    }

    function showErrorPopup(message) {
        L.popup()
            .setLatLng(map.getCenter())
            .setContent(`<div class="error-popup">${message}</div>`)
            .openOn(map);
    }

    // Initial load
    loadIncidents();
    
    // Set up periodic refresh with cleanup
    let refreshInterval = setInterval(loadIncidents, REFRESH_INTERVAL);
    
    // Clean up on window unload
    window.addEventListener('beforeunload', function() {
        clearInterval(refreshInterval);
        markers.clear();
        map.remove();
    });
});