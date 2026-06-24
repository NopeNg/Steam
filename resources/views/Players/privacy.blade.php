@extends('Players.Layouts.app')
@section('title', 'Chính sách bảo mật')

@section('content')
<div class="max-w-4xl mx-auto bg-[#171a21] p-8 border border-gray-800 rounded-sm text-gray-300 text-sm leading-relaxed">
    <h1 class="text-white text-2xl font-bold mb-6 border-b border-gray-700 pb-4 uppercase tracking-wider">Chính sách bảo mật</h1>
    <p class="text-gray-400 mb-8 italic">Cập nhật lần cuối: Tháng 06/2026</p>

    <div class="space-y-6">
        <section>
            <h2 class="text-white font-bold text-lg mb-2">1. Thu thập thông tin</h2>
            <p>Chúng tôi thu thập thông tin người dùng nhằm mục đích vận hành hệ thống và cải thiện trải nghiệm dịch vụ. Các loại thông tin bao gồm:</p>
            <ul class="list-disc ml-5 mt-2 space-y-1">
                <li><strong>Thông tin tài khoản:</strong> Tên người dùng, địa chỉ email, mật khẩu (được mã hóa).</li>
                <li><strong>Thông tin giao dịch:</strong> Lịch sử đơn hàng, phương thức thanh toán, địa chỉ IP và thông tin thiết bị.</li>
                <li><strong>Thông tin kỹ thuật:</strong> Cookie và lịch sử duyệt web để duy trì phiên đăng nhập và cá nhân hóa trải nghiệm.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">2. Mục đích sử dụng</h2>
            <p>Thông tin thu thập được sử dụng để:</p>
            <ul class="list-disc ml-5 mt-2 space-y-1">
                <li>Xác thực tài khoản và xử lý đơn hàng tự động thông qua API.</li>
                <li>Thông báo các vấn đề liên quan đến giao dịch hoặc cập nhật dịch vụ quan trọng.</li>
                <li>Phát hiện và ngăn chặn gian lận (Chargeback, lừa đảo).</li>
                <li>Tuân thủ các nghĩa vụ pháp lý và yêu cầu từ cơ quan có thẩm quyền.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">3. Chia sẻ thông tin</h2>
            <p>SteamKey cam kết <strong>không bán, cho thuê hoặc chia sẻ</strong> thông tin cá nhân của người dùng cho bên thứ ba vì mục đích thương mại. Thông tin chỉ được chia sẻ trong các trường hợp cần thiết:</p>
            <ul class="list-disc ml-5 mt-2 space-y-1">
                <li><strong>Cổng thanh toán:</strong> Để thực hiện đối soát và xử lý giao dịch tài chính.</li>
                <li><strong>Nhà cung cấp API:</strong> Để đối soát mã Key và giải quyết khiếu nại sản phẩm.</li>
                <li><strong>Pháp luật:</strong> Khi có yêu cầu hợp lệ từ cơ quan thực thi pháp luật.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">4. Bảo mật dữ liệu</h2>
            <p>Chúng tôi áp dụng các tiêu chuẩn bảo mật cao nhất để bảo vệ thông tin của bạn:</p>
            <ul class="list-disc ml-5 mt-2 space-y-1">
                <li>Kết nối được mã hóa bằng giao thức SSL/TLS an toàn.</li>
                <li>Mật khẩu người dùng được băm (hashing) bằng thuật toán an toàn, không lưu trữ mật khẩu dưới dạng văn bản thuần (plain text).</li>
                <li>Hệ thống được giám sát định kỳ để ngăn chặn các nỗ lực tấn công mạng.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">5. Sử dụng Cookies</h2>
            <p>SteamKey sử dụng Cookies để lưu trữ phiên đăng nhập và giỏ hàng của bạn. Bạn có thể tùy chỉnh cài đặt trình duyệt để từ chối Cookie, tuy nhiên việc này có thể làm gián đoạn trải nghiệm mua sắm trên hệ thống.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">6. Quyền của người dùng</h2>
            <p>Người dùng có quyền truy cập, chỉnh sửa hoặc yêu cầu xóa dữ liệu cá nhân của mình. Đối với các dữ liệu giao dịch đã hoàn tất, chúng tôi có nghĩa vụ lưu trữ để đối soát theo quy định pháp luật.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">7. Thay đổi chính sách</h2>
            <p>Chúng tôi có quyền cập nhật chính sách bảo mật bất cứ lúc nào. Mọi thay đổi sẽ có hiệu lực ngay khi đăng tải. Việc bạn tiếp tục sử dụng website đồng nghĩa với việc bạn chấp nhận các chính sách đã được cập nhật.</p>
        </section>
    </div>
</div>
@endsection