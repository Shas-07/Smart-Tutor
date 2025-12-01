// Student Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const progressSelects = document.querySelectorAll('.progress-select');
    
    progressSelects.forEach(select => {
        select.addEventListener('change', function() {
            const materialId = this.getAttribute('data-material-id');
            const status = this.value;
            
            // Update progress via AJAX
            const formData = new FormData();
            formData.append('update_progress', '1');
            formData.append('material_id', materialId);
            formData.append('status', status);
            
            fetch('dashboard.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Show success feedback
                const card = this.closest('.material-card');
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 200);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update progress. Please try again.');
            });
        });
    });
});

