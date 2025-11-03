document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('task-search');
    const taskList = document.getElementById('task-list');
    const tasks = window.taskData || [];
    const currentUserId = window.currentUserId;

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        const filteredTasks = tasks.filter(task => task.name.toLowerCase().includes(query));

        taskList.innerHTML = '';
        if (filteredTasks.length > 0) {
            filteredTasks.forEach(task => {
                const taskRow = document.createElement('tr');
                const isEditable = task.responsible_id === currentUserId && task.status !== 'Completed';

                taskRow.className = 'border-b hover:bg-gray-100';

                taskRow.innerHTML = `
                    <td class="px-4 py-2">
                        <a href="/tasks/${task.id}" class="text-indigo-600 hover:underline font-medium">
                            ${task.name}
                        </a>
                        ${isEditable ? `
                            <a href="/tasks/${task.id}/edit" class="inline-block ml-2 py-1 px-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>` : ''}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-600">
                        ${task.status}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-600">
                        ${task.responsible ? task.responsible.username : '<span class="italic text-gray-400">Unassigned</span>'}
                    </td>
                    <td class="px-4 py-2">
                        ${isEditable ? `
                        <form method="POST" action="/tasks/${task.id}/complete" class="inline-block">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <button type="submit" class="py-1 px-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                <i class="fas fa-check"></i> Complete
                            </button>
                        </form>` : ''}
                    </td>
                `;
                taskList.appendChild(taskRow);
            });
        } else {
            taskList.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500 italic">
                        No tasks available.
                    </td>
                </tr>
            `;
        }
    });
});

