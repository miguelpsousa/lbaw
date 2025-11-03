document.addEventListener('DOMContentLoaded', function () {
    const favoriteStars = document.querySelectorAll('.favorite-star');

    favoriteStars.forEach(star => {
        star.addEventListener('click', function () {
            const projectId = this.dataset.projectid;
            const isFavorite = this.querySelector('i').classList.contains('fa-solid');

            if(!projectId) {
            console.error('Project ID not found on favorite-star element.');
            return;
            }
            const starElement = this;
            fetch(`/toggle-favorite/${projectId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            })
            .then(response => {
            if (response.ok) {
                if (isFavorite) {
                starElement.querySelector('i').classList.remove('fa-solid', 'text-yellow-500');
                starElement.querySelector('i').classList.add('fa-regular', 'text-gray-400');
                } else {
                starElement.querySelector('i').classList.remove('fa-regular', 'text-gray-400');
                starElement.querySelector('i').classList.add('fa-solid', 'text-yellow-500');
                }
                // Remove project entry if in favorites mode
                if (document.querySelector('.projects-tab').dataset.favorites == true) {
                    starElement.closest('li').remove();
                }
            } else {
                alert('Failed to update favorite status.');
            }
            })
            .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating favorite status.');
            });
        });
    });
});