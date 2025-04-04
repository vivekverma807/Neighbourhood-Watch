// map.js - Handles the interactive community map

// Global variables
let map;
let incidentMarkers = [];
const iconColors = {
    emergency: '#e74c3c',
    warning: '#f39c12',
    information: '#3498db',
    resolved: '#2ecc71'
};

// Initialize the map
function initMap() {
    // Default coordinates (center of the map)
    const defaultLat = 28.594195526353836;
    const defaultLng = 77.2089685886433;
    
    // Create map instance
    map = L.map('map').setView([defaultLat, defaultLng], 33);
    
    // Add tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Load incidents from server
    loadIncidents();
    
    // Setup filter functionality
    setupFilters();
}

// Create custom marker icon
function getIcon(status) {
    return L.divIcon({
        className: 'custom-icon',
        html: `<div style="background-color: ${iconColors[status]}; 
               width: 20px; 
               height: 20px; 
               border-radius: 50%; 
               border: 2px solid white;
               box-shadow: 0 0 5px rgba(0,0,0,0.3);"></div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });
}

// Load incidents from server
function loadIncidents() {
    // Fetch incidents from your backend API
    fetch('../PHP/get-incidents.php')
        .then(response => response.json())
        .then(data => {
            // Clear existing markers
            clearMarkers();
            
            // Add new markers
            data.forEach(incident => {
                addIncidentMarker(incident);
            });
        })
        .catch(error => {
            console.error('Error loading incidents:', error);
        });
}

// Add a single incident marker to the map
function addIncidentMarker(incident) {
    const marker = L.marker([incident.latitude, incident.longitude], {
        icon: getIcon(incident.status),
        incidentData: incident // Store the full incident data with the marker
    }).addTo(map);
    
    // Create popup content
    const popupContent = `
        <h3>${incident.title}</h3>
        <p><strong>Type:</strong> ${incident.type}</p>
        <p><strong>Reported:</strong> ${new Date(incident.timestamp).toLocaleString()}</p>
        <p>${incident.description}</p>
        ${incident.evidence ? `<p><a href="${incident.evidence}" target="_blank">View Evidence</a></p>` : ''}
    `;
    
    marker.bindPopup(popupContent);
    incidentMarkers.push(marker);
}

// Clear all markers from the map
function clearMarkers() {
    incidentMarkers.forEach(marker => {
        map.removeLayer(marker);
    });
    incidentMarkers = [];
}

// Setup filter controls
function setupFilters() {
    document.querySelectorAll('.incident-filter input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            filterMarkers();
        });
    });
}

// Filter markers based on selected types
function filterMarkers() {
    const selectedTypes = [];
    document.querySelectorAll('.incident-filter input:checked').forEach(checkbox => {
        selectedTypes.push(checkbox.id.replace('filter-', ''));
    });
    
    incidentMarkers.forEach(marker => {
        if (selectedTypes.includes(marker.options.incidentData.type) {
            map.addLayer(marker);
        } else {
            map.removeLayer(marker);
        }
    });
}

// Initialize the map when DOM is loaded
document.addEventListener('DOMContentLoaded', initMap);