@extends('Players.Layouts.app')
@section('title', 'Liên hệ hoàn tiền')

@section('content')
<div class="max-w-4xl mx-auto bg-[#171a21] p-8 border border-gray-800 rounded-sm text-gray-300 text-sm leading-relaxed">
    <h1 class="text-white text-2xl font-bold mb-6 border-b border-gray-700 pb-4 uppercase tracking-wider">Hỗ trợ & Hoàn tiền</h1>

    <div class="space-y-6">
        <section>
            <h2 class="text-white font-bold text-lg mb-2">Quy trình yêu cầu hoàn tiền</h2>
            <p>Nếu bạn gặp sự cố với sản phẩm và cho rằng mình thuộc diện được hoàn tiền theo <a href="{{ route('terms') }}" class="text-sky-400 underline">Điều khoản dịch vụ</a> (Điều 8), vui lòng thực hiện theo các bước sau:</p>
            <ol class="list-decimal ml-5 mt-2 space-y-2">
                <li><strong>Gửi thông tin về Email/Support:</strong> Hãy gửi yêu cầu về [Địa chỉ Email hỗ trợ của bạn] hoặc qua hệ thống hỗ trợ trực tuyến.</li>
                <li><strong>Cung cấp mã đơn hàng:</strong> Ghi rõ mã đơn hàng (Order ID) để chúng tôi kiểm tra hệ thống.</li>
                <li><strong>Cung cấp bằng chứng lỗi:</strong>
                    <ul class="list-disc ml-5 mt-1">
                        <li>Nếu Key bị báo "đã kích hoạt": Vui lòng cung cấp ảnh chụp màn hình hoặc video quay lại quá trình kích hoạt key từ đầu đến lúc báo lỗi.</li>
                        <li>Nếu Key không hợp lệ: Vui lòng cung cấp ảnh chụp màn hình thông báo lỗi từ nền tảng kích hoạt (Steam, Epic, v.v.).</li>
                    </ul>
                </li>
            </ol>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">Thời gian xử lý</h2>
            <p>Sau khi tiếp nhận yêu cầu, bộ phận kỹ thuật của SteamKey sẽ tiến hành đối soát với nhà cung cấp Key. Thời gian phản hồi dự kiến từ <strong>24h đến 48h làm việc</strong>.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">Lưu ý quan trọng</h2>
            <div class="bg-red-900/20 border border-red-800 p-4 rounded-sm">
                <ul class="list-disc ml-5 space-y-1 text-red-200">
                    <li>Chúng tôi <strong>từ chối giải quyết</strong> các khiếu nại không có bằng chứng hình ảnh/video rõ ràng.</li>
                    <li>Mọi hành vi cố tình làm giả bằng chứng hoặc gian lận nhằm trục lợi hoàn tiền sẽ dẫn đến việc <strong>khóa tài khoản vĩnh viễn</strong>.</li>
                    <li>Hoàn tiền chỉ được thực hiện thông qua hình thức thanh toán gốc hoặc số dư tài khoản trên SteamKey (tùy trường hợp).</li>
                </ul>
            </div>
        </section>
    </div>

    <div class="mt-8">
<a href="mailto:hole1524@gmail.com" 
   class="inline-block bg-sky-600 hover:bg-sky-500 text-white font-bold py-3 px-6 rounded transition duration-300">
   Gửi Email Hỗ Trợ
</a>
    </div>
    
</div>
@endsection