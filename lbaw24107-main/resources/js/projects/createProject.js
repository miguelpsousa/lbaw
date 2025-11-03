document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-users');
    const searchResultsList = document.getElementById('search-results-list');
    const selectedUsersList = document.getElementById('selected-users-list');
    const selectedUsersInput = document.getElementById('selected-users-input');

    // Maintain state for selected users
    const selectedUsers = [];

    // Handle user search
    searchInput.addEventListener('input', async (e) => {
        const query = e.target.value.trim();

        if (query.length > 0) {
            // Make an AJAX request to search for users
            const response = await fetch(`/search/search-usr?query=${encodeURIComponent(query)}`);
            console.log(response);
            const users = await response.json();


            // Display search results
            searchResultsList.innerHTML = users.map(user => `
                <li class="flex items-center space-x-2 p-2 border-b" data-id="${user.id}" data-username="${user.username}">
            <span>${user.username}</span>
            <button type="button" class="add-user-btn py-1 px-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Add
            </button>
        </li>
            `).join('');
        } else {
            searchResultsList.innerHTML = '<p class="text-gray-500">No users found.</p>';
        }
    });

    // Handle adding a user to the selected list
    searchResultsList.addEventListener('click', (e) => {
        if (e.target.classList.contains('add-user-btn')) {
            const userElement = e.target.closest('li');
            const userId = userElement.getAttribute('data-id');
            const username = userElement.getAttribute('data-username');

            // Prevent duplicates
            if (!selectedUsers.some(user => user.id === userId)) {
                selectedUsers.push({ id: userId, username });
                updateSelectedUsersUI();
                updateSelectedUsersInput();
            }
        }
    });

    // Handle removing a user from the selected list
    selectedUsersList.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-user-btn')) {
            const userId = e.target.getAttribute('data-id');

            // Remove the user from the state
            const userIndex = selectedUsers.findIndex(user => user.id === userId);
            if (userIndex > -1) {
                selectedUsers.splice(userIndex, 1);
                updateSelectedUsersUI();
                updateSelectedUsersInput();
            }
        }
    });

    // Update the UI for selected users
    function updateSelectedUsersUI() {
        selectedUsersList.innerHTML = selectedUsers.map(user => `
            <li class="flex items-center justify-between p-2 border-b" data-id="${user.id}">
        <span class="text-gray-800">${user.username}</span>
        <button type="button" class="remove-user-btn bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" data-id="${user.id}">
            Remove
        </button>
    </li>
        `).join('');
    }

    // Update the hidden input for form submission
    function updateSelectedUsersInput() {
        selectedUsersInput.value = selectedUsers.map(user => user.id).join(',');
    }
});