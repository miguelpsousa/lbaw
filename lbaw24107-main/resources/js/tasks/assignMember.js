const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('DOMContentLoaded', () => {
    console.debug('Initializing Assign Member script...');

    // Toggle Assign Member Modal
    document.querySelectorAll('.assign-member-btn').forEach(button => {
        console.debug(`Assign member button found for task ID: ${button.getAttribute('data-task-id')}`);
        button.addEventListener('click', toggleAssignMemberModal);
    });

    // Initialize search input listeners
    document.querySelectorAll('.search-members-input').forEach(input => {
        console.debug(`Search input initialized for task ID: ${input.getAttribute('data-task-id')}`);
        input.addEventListener('input', handleSearchInput);
    });
});

// Toggle the visibility of the modal
function toggleAssignMemberModal(event) {
    const button = event.currentTarget;
    const taskId = button.getAttribute('data-task-id');
    const projectId = button.getAttribute('data-project-id');
    const modal = document.getElementById(`assign-member-modal-${taskId}`);

    if (modal) {
        modal.classList.toggle('hidden');
        console.debug(`Toggled modal visibility for task ID: ${taskId}. Modal is now: ${modal.classList.contains('hidden') ? 'hidden' : 'visible'}`);
    } else {
        console.error(`Modal not found for task ID: ${taskId}`);
    }
}

// Handle user input in the search bar
async function handleSearchInput(event) {
    const input = event.target;
    const taskId = input.getAttribute('data-task-id'); // Assuming task ID is tied to the input
    const projectId = input.getAttribute('data-project-id'); // Retrieve project ID
    const query = input.value.trim();

    console.debug(`Search input detected for task ID: ${taskId}, project ID: ${projectId}, query: "${query}"`);

    const resultsList = document.getElementById(`search-results-list-${taskId}`);
    if (!resultsList) {
        console.error(`Search results list not found for task ID: ${taskId}`);
        return;
    }

    if (query.length === 0) {
        console.debug(`Clearing search results for task ID: ${taskId} (empty query)`);
        resultsList.innerHTML = ''; // Clear results when query is empty
        return;
    }

    if (!projectId) {
        console.error(`Project ID not found for task ID: ${taskId}`);
        return;
    }

    try {
        console.debug(`Fetching search results for query: "${query}", project ID: ${projectId}`);
        const response = await fetch(`/search/search-accepted-usr?query=${encodeURIComponent(query)}&projectId=${projectId}`);
        if (response.ok) {
            const members = await response.json();
            console.debug(`Fetched ${members.length} search results for task ID: ${taskId}`);
            renderSearchResults(members, taskId);
        } else {
            console.error(`Error fetching members for query: "${query}". Response status: ${response.status}`);
        }
    } catch (error) {
        console.error('Network error fetching members:', error);
    }
}


// Render search results dynamically
function renderSearchResults(members, taskId) {
    const resultsList = document.getElementById(`search-results-list-${taskId}`);
    if (!resultsList) {
        console.error(`Search results list not found for task ID: ${taskId}`);
        return;
    }

    // Fetch the list of already assigned users for the task
    const assignedUsers = Array.from(document.querySelectorAll(`.responsible-username[data-task-id="${taskId}"]`))
        .map(element => element.dataset.userId);

    // Filter out already assigned users from the search results
    const filteredMembers = members.filter(member => !assignedUsers.includes(member.id.toString()));

    console.debug(`Rendering ${filteredMembers.length} search results for task ID: ${taskId}`);
    resultsList.innerHTML = filteredMembers.map(member => `
        <li class="search-result-item flex items-center space-x-2 p-2 border-b" data-member-id="${member.id}" data-task-id="${taskId}">
            <span>${member.username}</span>
            <button type="button" class="add-user-btn py-1 px-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Assign
            </button>
        </li>
    `).join('');

    // Reattach event listeners to newly created buttons
    attachAddUserHandlers();
}

// Attach event listeners to "Assign" buttons
function attachAddUserHandlers() {
    document.querySelectorAll('.add-user-btn').forEach(button => {
        button.removeEventListener('click', handleAddUser); // Avoid duplicate listeners
        button.addEventListener('click', handleAddUser);
        console.debug(`Attached click handler to Assign button for member ID: ${button.closest('.search-result-item').dataset.memberId}`);
    });
}

// Handle assigning a user to a task
async function handleAddUser(event) {
    const button = event.currentTarget;
    const userElement = button.closest('.search-result-item');
    const memberId = userElement.dataset.memberId;
    const taskId = userElement.dataset.taskId;
    const projectId = document.querySelector(`.assign-member-btn[data-task-id="${taskId}"]`).getAttribute('data-project-id');

    console.debug(`Assigning member ID: ${memberId} to task ID: ${taskId}`);

    try {
        const response = await fetch(`/tasks/${taskId}/assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ member_id: memberId })
        });

        if (response.ok) {
            // Check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const result = await response.json();
                console.debug('Server response:', result);
            } else {
                console.debug('Non-JSON response received. Assuming successful redirect or empty response.');
            }

            closeAssignMemberModal(taskId);
        }
    } catch (error) {
        console.error('Network error assigning member:', error);
    }
}

// Close the modal after assigning a user
function closeAssignMemberModal(taskId) {
    const modal = document.getElementById(`assign-member-modal-${taskId}`);
    if (modal) {
        modal.classList.add('hidden');
        location.reload();
    } else {
        console.error(`Modal not found for task ID: ${taskId}`);
    }
}
