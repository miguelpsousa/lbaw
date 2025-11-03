document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.add-member input[type="text"]');
    const searchResults = document.querySelector('.search-results');
    const membersList = document.querySelector('.members-list');
    const projectId = document.querySelector('.add-member').dataset.projectid;
    // Function to fetch search results
    searchInput.addEventListener('input', async () => {
        const query = searchInput.value.trim();
        if (query.length < 1) {
            searchResults.innerHTML = ""; 
            return;
        }

        try {
            const response = await fetch(`/search/search-usr?query=${encodeURIComponent(query)}`);
            if (response.ok) {
                const users = await response.json();
                displaySearchResults(users);
            } else {
                console.error('Error fetching search results:', response.statusText);
            }
        } catch (error) {
            console.error('Network error:', error);
        }
    });

    // Function to display search results
    function displaySearchResults(users) {
        searchResults.innerHTML = users.map(user => `
            <div class="search-result member bg-white p-4 rounded-lg shadow-md flex justify-between items-center hover:bg-gray-100 transition duration-200" data-username="${user.username}" data-id="${user.id}">
                ${user.username}
            </div>
        `).join('');

        // Attach click event listeners to each result
        document.querySelectorAll('.search-result').forEach(result => {
            result.addEventListener('click', handleAddUser);
        });
    }

    // Function to handle adding a user to the project
    async function handleAddUser(event) {
        const selectedUser = event.currentTarget;
        const userId = selectedUser.dataset.id;
        const username = selectedUser.dataset.username;

        try {
            const response = await fetch('/projects/'+ projectId +'/add-member', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ user_id: userId })
            });

            if (response.ok) {
                const result = await response.json();
                
                location.reload();

                // Add the new member to the member list
                appendNewMember(username, 'Pending invitation...');
                
                // Clear search results
                searchResults.innerHTML = '';
                searchInput.value = '';
            } else {
                const errorData = await response.json();
                
            }
        } catch (error) {
            console.error('Error adding user:', error);
        }
    }

    // Function to append a new member to the members list
    function appendNewMember(username, statusText, userId) {
        const newMember = document.createElement('a');
        newMember.href = `/projects/${projectId}/team-members/${userId}`;
        newMember.classList.add('block');
        newMember.innerHTML = `
            <div class="member bg-white p-4 rounded-lg shadow-md flex justify-between items-center hover:bg-gray-100 transition duration-200">
                <div class="member-name text-lg font-semibold text-gray-800">
                    <p>${username}</p>
                </div>
                <div class="member-status text-sm text-gray-600">
                    <p>${statusText}</p>
                </div>
            </div>
        `;
        membersList.appendChild(newMember);
    }
    
});
