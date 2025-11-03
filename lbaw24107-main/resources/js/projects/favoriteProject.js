document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.favorite-star').forEach(starElement => {
        starElement.addEventListener('click', async function () {
            // Find the star icon within the clicked element
            const starIcon = this.querySelector('i');
            
            // Check if the project is currently favorited
            const isFavorited = starIcon.classList.contains('fa-solid');

            // Determine the project ID (you'll need to set data attributes in the server-side code)
            const projectId = this.dataset.projectid;

            if (!projectId) {
                console.error('Project ID not found on favorite-star element.');
                return;
            }

            try {
                // Send the update request to the server
                const response = await fetch(`/toggle-favorite/${projectId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Laravel CSRF token
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to update favorite status.');
                }

                // Toggle the star's appearance based on the new state
                if (isFavorited) {
                    // Change from solid to regular
                    starIcon.classList.remove('fa-solid', 'text-yellow-500');
                    starIcon.classList.add('fa-regular', 'text-gray-400');
                } else {
                    // Change from regular to solid
                    starIcon.classList.remove('fa-regular', 'text-gray-400');
                    starIcon.classList.add('fa-solid', 'text-yellow-500');
                }
            } catch (error) {
                console.error('Error updating favorite status:', error);
            }
        });
    });
});
