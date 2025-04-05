document.addEventListener('DOMContentLoaded', function() {
    const incidentForm = document.getElementById('incident-form');
    
    if (incidentForm) {
        incidentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = incidentForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            // Create FormData object
            const formData = new FormData(incidentForm);
            
            // Send AJAX request
            fetch(incidentForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Incident reported successfully! Reference ID: ' + data.incident_id);
                    incidentForm.reset();
                    
                    // Optionally redirect to another page
                    // window.location.href = './thank-you.html';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the form. Please try again.');
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Set default date to today
    const dateField = document.getElementById('date');
    if (dateField) {
        const today = new Date();
        dateField.value = today.toISOString().substr(0, 10);
    }
});