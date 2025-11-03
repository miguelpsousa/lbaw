document.addEventListener('DOMContentLoaded', function () {
    window.editMessage = function (messageId) {
        const messageText = document.getElementById(`message-content-${messageId}`).innerText;
        const messageContentDiv = document.getElementById(`message-content-${messageId}`);
        messageContentDiv.innerHTML = `
            <form action="/messages/${messageId}" method="POST">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <input type="text" name="message_text" class="form-control w-full p-2 border rounded-lg" value="${messageText}" required>
                </div>
                <div class="flex space-x-2 mt-2">
                    <button type="button" class="py-2 px-4 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700" onclick="cancelEdit(${messageId}, '${messageText}')">Cancel</button>
                    <button type="submit" class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">Update Message</button>
                </div>
            </form>
        `;
    };

    window.cancelEdit = function (messageId, messageText) {
        const messageContentDiv = document.getElementById(`message-content-${messageId}`);
        messageContentDiv.innerHTML = `<p class="text-gray-700">${messageText}</p>`;
    };

    window.confirmDelete = function () {
        return confirm('Are you sure you want to delete this message?');
    };
});