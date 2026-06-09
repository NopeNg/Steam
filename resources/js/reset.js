// File: resources/js/reset.js

/**
 * Hàm gửi AJAX dùng chung sử dụng Fetch API
 * @param {string} url - Đường dẫn endpoint
 * @param {string} method - Phương thức (GET, POST, PUT, DELETE)
 * @param {Object|null} data - Dữ liệu gửi lên (nếu có)
 * @returns {Promise<any>} - Trả về dữ liệu JSON
 */
async function sendAjaxRequest(url, method = 'GET', data = null) {
    const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    const options = {
        method: method,
        headers: headers
    };

    if (data && method.toUpperCase() !== 'GET') {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Lỗi AJAX:', error);
        throw error;
    }
}

// Gắn hàm ra toàn cục để các trang khác có thể gọi
window.sendAjaxRequest = sendAjaxRequest;