document.addEventListener('DOMContentLoaded', function () {
    window.editComment = function (commentId) {
        const commentText = document.getElementById(`comment-content-${commentId}`).innerText;
        const commentContentDiv = document.getElementById(`comment-content-${commentId}`);
        const editButton = document.getElementById(`edit-button-${commentId}`);
        editButton.style.display = 'none';
        commentContentDiv.innerHTML = `
            <form action="/task-comments/${commentId}" method="POST">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <input type="text" name="comment_text" class="form-control w-full p-2 border rounded-lg" value="${commentText}" required>
                </div>
                <div class="flex space-x-2 mt-2">
                    <button type="button" class="py-2 px-4 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700" onclick="cancelEdit(${commentId}, '${commentText}')">Cancel</button>
                    <button type="submit" class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">Update Comment</button>
                </div>
            </form>
        `;
    };

    window.cancelEdit = function (commentId, commentText) {
        const commentContentDiv = document.getElementById(`comment-content-${commentId}`);
        const editButton = document.getElementById(`edit-button-${commentId}`);
        editButton.style.display = 'inline';
        commentContentDiv.innerHTML = `<p class="text-gray-700">${commentText}</p>`;
    };

    window.confirmDelete = function () {
        return confirm('Are you sure you want to delete this comment?');
    };
});