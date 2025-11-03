document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.search-form');
    console.log(form);
    const typeSelect = document.getElementById('type');
    const queryInput = document.getElementById('query');
    const taskOptions = document.getElementById('task-options');
    const projectOptions = document.getElementById('project-options');
    const userOptions = document.getElementById('user-options');
    const searchResults = document.querySelector('.search-results');
    let selectedProjects = [];


     // Add event listeners for inputs and selects
     form.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', performSearch);
        input.addEventListener('change', performSearch);
    });

    // Show/hide specific options based on the selected type
    function toggleOptions() {
        const type = typeSelect.value;
        taskOptions.style.display = type === 'tasks' ? 'block' : 'none';
        projectOptions.style.display = type === 'projects' ? 'block' : 'none';
        userOptions.style.display = type === 'users' ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', toggleOptions);

    // Helper function to get form data as an object
    function getFormData() {
        const formData = new FormData(form);
        return Object.fromEntries(formData.entries());
    }

    // AJAX search function
    function performSearch() {
        const data = getFormData();
        console.log(data);
        let requestUrl = '/search';
        let type = data.type;

        if(data.type === 'tasks') {
            requestUrl = '/search/search-task';
            requestUrl += `?project_id=${data.task_project}`;
            requestUrl += `&status=${data.task_status}`;
            if(data.task_priority !== ''){
                requestUrl += `&priority=${data.task_priority}`;
            }
            if(data.task_due_date !== ''){
                requestUrl += `&due_date=${data.task_due_date}`;
            }


        }
        else if(data.type === 'projects') {
            requestUrl = '/search/search-project';
            requestUrl += `?status=${data.project_status}`;
            requestUrl += `&category=${data.project_category}`;
            
        }
        else if(data.type === 'users') {
            requestUrl = '/search/search-usr';
            requestUrl += '?common=' +  data.common_projects.toString();
            

        }
        requestUrl += `&query=${data.query}`;
        requestUrl += `&exact=${data.exact_match == 'on' ? true : false}`;
        console.log(requestUrl);
        fetch(requestUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); // Assuming the server returns HTML
            })
            .then(json => {
                console.log(json);
                updateSearchResults(json,type);
            })
            .catch(error => {
                console.error('Error:', error);
                searchResults.innerHTML = '<p class="text-red-600">An error occurred while fetching results.</p>';
            });
    }

   

    // Initial toggle for options
    toggleOptions();



    function updateSearchResults(json,type) {
        const results = JSON.parse(json);
        searchResults.innerHTML = '';
        console.log(results);if (type === 'tasks') {
            results.forEach(task => {
                const taskContent = document.createElement('div');
                taskContent.classList.add('task', 'bg-white', 'rounded-lg', 'p-4', 'shadow-md', 'mb-4', 'hover:bg-gray-100', 'transition-colors', 'duration-200');
                taskContent.innerHTML = `
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">${task.name}</h3>
                    <p class="text-sm text-gray-600 mb-1">${task.description}</p>
                    <p class="text-sm text-gray-500 mb-1">Project: ${task.project[0].name}</p>`;
                let status = task.status;
                if (task.status === 'complete') {
                    status = 'Completed';
                } else if (task.status === 'in-progress') {
                    status = 'In Progress';
                } else if (task.status === 'pending') {
                    status = 'Pending';
                }
        
                taskContent.innerHTML += `<p class="text-sm text-gray-500 mb-1">Status: ${status}</p>`;
                taskContent.innerHTML += `
                    <p class="text-sm text-gray-500 mb-1">Priority: ${task.priority}</p>
                    <p class="text-sm text-gray-500">Due date: ${task.due_date}</p>
                `;
                const taskDiv = document.createElement('a');
                taskDiv.href = `/tasks/${task.id}`;
                taskDiv.appendChild(taskContent);
                searchResults.appendChild(taskDiv);
            });
        } else if (type === 'projects') {
            results.forEach(project => {
                const projectContent = document.createElement('div');
                projectContent.classList.add('project', 'bg-white', 'rounded-lg', 'p-4', 'shadow-md', 'mb-4', 'hover:bg-gray-100', 'transition-colors', 'duration-200');
                projectContent.innerHTML = `
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">${project.name}</h3>
                    <p class="text-sm text-gray-600 mb-2">${project.description}</p>
                    <p class="text-sm text-gray-500">Status: ${project.status}</p>
                `;
                const projectDiv = document.createElement('a');
                projectDiv.href = `/projects/${project.id}`;
                projectDiv.appendChild(projectContent);
                searchResults.appendChild(projectDiv);
            });
        } else if (type === 'users') {
            results.forEach(user => {
                const userContent = document.createElement('div');
                userContent.classList.add('flex', 'items-center', 'space-x-4', 'hover:bg-gray-100', 'transition-colors', 'duration-200');
                userContent.innerHTML = `
                    <img class="w-16 h-16 rounded-full" src="${user.profile_picture}" alt="${user.username}'s profile picture">
                    <h3 class="text-lg font-semibold text-gray-800">${user.username}</h3>
                `;
                const userDiv = document.createElement('a');
                userDiv.classList.add('user', 'block', 'bg-white', 'border', 'border-gray-200', 'rounded-lg', 'p-4', 'shadow-md', 'mb-4', 'hover:bg-gray-100', 'transition-colors', 'duration-200');
                userDiv.href = `/profile/${user.id}`;
                userDiv.appendChild(userContent);
                searchResults.appendChild(userDiv);
            });
        }
        
        
        
            
    }
});
