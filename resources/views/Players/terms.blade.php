@extends('Players.Layouts.app')
@section('title', 'Điều khoản dịch vụ')

@section('content')
<div class="max-w-4xl mx-auto bg-[#171a21] p-8 border border-gray-800 rounded-sm text-gray-300 text-sm leading-relaxed">
    <h1 class="text-white text-2xl font-bold mb-6 border-b border-gray-700 pb-4 uppercase tracking-wider">ĐIỀU KHOẢN DỊCH VỤ</h1>
    <p class="text-gray-400 mb-8 italic">Cập nhật lần cuối: Tháng 06/2026</p>

    <div class="space-y-6">
        <section>
            <h2 class="text-white font-bold text-lg mb-2">1. Quy định chung</h2>
            <p>SteamKey Marketplace (sau đây gọi là "SteamKey") là nền tảng phân phối sản phẩm kỹ thuật số bao gồm Game Key, Gift Code và các sản phẩm liên quan thông qua hệ thống kết nối với các nhà cung cấp và API đối tác.</p>
            <p class="mt-2">Khi đăng ký tài khoản, truy cập hoặc sử dụng bất kỳ dịch vụ nào trên SteamKey, người dùng được xem là đã:</p>
            <ul class="list-disc ml-5 mt-2 space-y-1">
                <li>Đọc toàn bộ Điều khoản dịch vụ.</li>
                <li>Hiểu rõ quyền và nghĩa vụ của mình.</li>
                <li>Đồng ý tuân thủ toàn bộ quy định được nêu trong tài liệu này.</li>
                <li>Chấp nhận các chính sách liên quan được công bố trên hệ thống.</li>
            </ul>
            <p class="mt-2 text-red-400">Nếu không đồng ý với bất kỳ điều khoản nào, người dùng phải ngừng sử dụng dịch vụ ngay lập tức.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">2. Điều kiện sử dụng dịch vụ</h2>
            <p>Người dùng cam kết:</p>
            <ul class="list-disc ml-5 mt-2 space-y-1">
                <li>Cung cấp thông tin chính xác khi đăng ký tài khoản và chịu trách nhiệm về tính chính xác của thông tin đã cung cấp.</li>
                <li>Đủ 18 tuổi hoặc có sự đồng ý của người giám hộ hợp pháp. SteamKey không có nghĩa vụ xác minh tính hợp pháp của sự đồng ý này.</li>
                <li>Tuân thủ pháp luật Việt Nam và các quy định từ nhà phát hành game.</li>
                <li>Chịu trách nhiệm hoàn toàn đối với mọi hoạt động đăng nhập, mua hàng và giao dịch phát sinh từ tài khoản của mình.</li>
            </ul>
            <p class="mt-2">SteamKey có quyền từ chối cung cấp dịch vụ hoặc chấm dứt quyền truy cập đối với các tài khoản vi phạm Điều khoản dịch vụ.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">3. Sản phẩm kỹ thuật số</h2>
            <p>Tất cả sản phẩm trên SteamKey là sản phẩm kỹ thuật số (Digital Goods). Người dùng hiểu và đồng ý rằng:</p>
            <ul class="list-disc ml-5 mt-2 space-y-1">
                <li>Sản phẩm được cung cấp dưới dạng mã kích hoạt (Key), Gift Code hoặc dữ liệu điện tử tương đương.</li>
                <li>Sau khi Key được hiển thị trên hệ thống hoặc gửi tới tài khoản người dùng, sản phẩm được xem là đã bàn giao thành công.</li>
                <li>Do tính chất của sản phẩm kỹ thuật số, SteamKey không thể thu hồi hoặc xác minh việc sử dụng Key sau khi đã hiển thị cho người dùng.</li>
            </ul>
            <p class="mt-2 font-bold">SteamKey không hỗ trợ:</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li>Đổi trả sản phẩm đã nhận.</li>
                <li>Hoàn tiền vì thay đổi ý định sử dụng.</li>
                <li>Hoàn tiền do mua nhầm game, phiên bản hoặc nền tảng.</li>
                <li>Hoàn tiền vì sản phẩm không đáp ứng kỳ vọng cá nhân của người dùng.</li>
            </ul>
            <p class="mt-2 italic">Ngoại lệ duy nhất được áp dụng theo Chính sách hoàn tiền tại Điều 8.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">4. Giao dịch và bảo mật</h2>
            <p>SteamKey chỉ hỗ trợ các giao dịch được thực hiện trực tiếp trên hệ thống chính thức của SteamKey.</p>
            <p class="mt-2 font-bold">SteamKey từ chối hỗ trợ và không chịu trách nhiệm đối với:</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li>Các giao dịch mua bán ngoài hệ thống.</li>
                <li>Việc trao đổi hoặc chuyển nhượng Key giữa người dùng với bên thứ ba.</li>
                <li>Các giao dịch phát sinh trên Discord, Facebook, Telegram hoặc các nền tảng khác.</li>
                <li>Các trường hợp bị lừa đảo khi giao dịch ngoài hệ thống.</li>
            </ul>
            <p class="mt-2">Người dùng tự chịu mọi rủi ro phát sinh từ các giao dịch không được thực hiện trên SteamKey.</p>
            <p class="mt-2 font-bold">Trong trường hợp phát hiện:</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li>Bán lại Key trái phép, phân phối Key trái phép.</li>
                <li>Gian lận, lừa đảo hoặc lợi dụng hệ thống để trục lợi.</li>
            </ul>
            <p class="mt-2">SteamKey có quyền thu hồi Key chưa sử dụng, tạm khóa hoặc khóa vĩnh viễn tài khoản và từ chối hỗ trợ trong tương lai.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">5. Trách nhiệm của người dùng</h2>
            <p class="font-bold">Người dùng có trách nhiệm:</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li><strong>Bảo mật tài khoản:</strong> Không chia sẻ mật khẩu hoặc mã OTP cho bất kỳ ai; không cho người khác sử dụng tài khoản của mình; thông báo ngay cho SteamKey nếu phát hiện dấu hiệu truy cập trái phép.</li>
                <li><strong>Sử dụng hợp pháp:</strong> Không thực hiện các hành vi tấn công hệ thống, khai thác lỗ hổng bảo mật, can thiệp trái phép vào cơ sở dữ liệu, sử dụng bot/script, tạo đơn hàng giả, gian lận thanh toán hoặc gây ảnh hưởng đến hoạt động của hệ thống.</li>
                <li><strong>Sử dụng đúng mục đích:</strong> Không mạo danh SteamKey, không bán lại Key dưới danh nghĩa SteamKey, và không sử dụng dịch vụ cho các hoạt động trái pháp luật.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">6. Tính tương thích sản phẩm</h2>
            <p>Trước khi mua hàng, người dùng có trách nhiệm tự kiểm tra:</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li>Hệ điều hành được hỗ trợ, cấu hình tối thiểu và đề nghị.</li>
                <li>Quốc gia hoặc khu vực kích hoạt (Region Restriction).</li>
                <li>Nền tảng kích hoạt (Steam, Epic Games, EA App, Ubisoft Connect, Rockstar Launcher...).</li>
                <li>Các điều kiện sử dụng từ nhà phát hành.</li>
            </ul>
            <p class="mt-2">SteamKey không chịu trách nhiệm trong các trường hợp: Mua nhầm sản phẩm, phiên bản, khu vực kích hoạt; thiết bị không đáp ứng yêu cầu hệ thống; hoặc tài khoản không đủ điều kiện kích hoạt theo quy định nhà phát hành.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">7. Dịch vụ từ bên thứ ba</h2>
            <p>Một số sản phẩm trên SteamKey được cung cấp thông qua hệ thống API hoặc nhà cung cấp bên thứ ba. Người dùng hiểu và đồng ý rằng:</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li>Giá sản phẩm có thể thay đổi theo thời gian thực.</li>
                <li>Tình trạng tồn kho phụ thuộc vào đối tác cung cấp.</li>
                <li>Một số đơn hàng có thể cần thêm thời gian xử lý.</li>
            </ul>
            <p class="mt-2">SteamKey không chịu trách nhiệm đối với sự chậm trễ, gián đoạn hoặc lỗi phát sinh từ hệ thống của nhà cung cấp bên thứ ba. Tuy nhiên, SteamKey sẽ phối hợp với đối tác để xác minh và hỗ trợ khách hàng theo Chính sách hoàn tiền khi xác định lỗi thuộc phạm vi hỗ trợ.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">8. Chính sách hoàn tiền</h2>
            <p>SteamKey sẽ xem xét hỗ trợ hoàn tiền trong các trường hợp: Thanh toán thành công nhưng không nhận được sản phẩm; Hệ thống ghi nhận lỗi giao dịch; Đơn hàng không thể xử lý do lỗi hệ thống; Nhà cung cấp xác nhận không thể giao sản phẩm.</p>
            <p class="mt-2 font-bold">SteamKey sẽ không hoàn tiền trong các trường hợp:</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li>Key đã hiển thị cho người dùng, đã kích hoạt thành công.</li>
                <li>Người dùng mua nhầm sản phẩm, phiên bản, khu vực.</li>
                <li>Người dùng không đọc kỹ mô tả sản phẩm, vi phạm Điều khoản dịch vụ hoặc giao dịch có dấu hiệu gian lận.</li>
            </ul>
            <p class="mt-2">SteamKey có quyền kiểm tra lịch sử giao dịch, xác minh trạng thái Key và đối soát với nhà cung cấp trước khi đưa ra quyết định cuối cùng.</p>
            <p class="mt-2 italic text-gray-400">Lưu ý: Chúng tôi khuyến khích khách hàng quay video quá trình kích hoạt Key. SteamKey có quyền yêu cầu cung cấp video hoặc bằng chứng liên quan để phục vụ quá trình xác minh khiếu nại.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">9. Chính sách thu hồi và xử lý Key</h2>
            <p>Khi nhận được thông báo từ nhà phát hành hoặc nhà cung cấp về việc Key bị thu hồi hoặc vô hiệu hóa, SteamKey sẽ tiếp nhận yêu cầu, tiến hành xác minh nguyên nhân và đối chiếu dữ liệu với nhà cung cấp.</p>
            <ul class="list-disc ml-5 mt-1 space-y-1">
                <li><strong>Nếu lỗi thuộc về nhà cung cấp:</strong> SteamKey hỗ trợ đổi Key mới tương đương hoặc xem xét hoàn tiền theo Điều 8.</li>
                <li><strong>Nếu lỗi thuộc về người dùng:</strong> SteamKey không chịu trách nhiệm nếu Key bị thu hồi do bán lại, chia sẻ cho bên thứ ba, gian lận thanh toán hoặc vi phạm chính sách của nhà phát hành.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">10. Gian lận thanh toán và Chargeback</h2>
            <p>SteamKey có quyền điều tra các giao dịch có dấu hiệu bất thường (Chargeback, hoàn tiền cưỡng chế, sử dụng tài khoản thanh toán không chính chủ hoặc không hợp pháp). SteamKey có quyền tạm khóa/khóa vĩnh viễn tài khoản, hủy đơn hàng, thu hồi sản phẩm chưa sử dụng và từ chối dịch vụ trong tương lai.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">11. Khóa tài khoản và xử lý vi phạm</h2>
            <p>SteamKey có quyền khóa tài khoản mà không cần thông báo trước nếu phát hiện gian lận thanh toán, lợi dụng lỗi hệ thống, tấn công hệ thống, giả mạo thông tin, lừa đảo hoặc vi phạm nghiêm trọng Điều khoản dịch vụ. Các hình thức xử lý bao gồm cảnh báo, tạm khóa, khóa vĩnh viễn, thu hồi quyền sử dụng dịch vụ.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">12. Giới hạn trách nhiệm</h2>
            <p>SteamKey không có nghĩa vụ pháp lý phải bồi thường đối với sự cố Internet, mất điện, lỗi nhà cung cấp API, sự cố nhà phát hành, thay đổi chính sách từ Steam/Epic/EA/Ubisoft, hoặc thiệt hại gián tiếp phát sinh. Tuy nhiên, SteamKey có thể xem xét hỗ trợ hoàn tiền/cấp lại sản phẩm nếu xác nhận lỗi thuộc phạm vi kiểm soát của hệ thống.</p>
            <p class="mt-2 font-bold text-red-400">Trong mọi trường hợp, tổng trách nhiệm bồi thường tối đa của SteamKey đối với một giao dịch không vượt quá giá trị đơn hàng mà khách hàng đã thanh toán.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">13. Quyền sở hữu trí tuệ</h2>
            <p>Mọi tên trò chơi, hình ảnh, logo, thương hiệu và nội dung liên quan đều thuộc quyền sở hữu của nhà phát hành hoặc chủ sở hữu tương ứng. SteamKey không tuyên bố quyền sở hữu đối với bất kỳ tài sản trí tuệ nào của bên thứ ba.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">14. Thay đổi điều khoản</h2>
            <p>SteamKey có quyền cập nhật, sửa đổi hoặc bổ sung Điều khoản dịch vụ vào bất kỳ thời điểm nào. Mọi thay đổi sẽ có hiệu lực kể từ thời điểm được công bố trên website. Việc tiếp tục sử dụng dịch vụ sau thời điểm cập nhật được xem là sự chấp thuận của người dùng đối với các điều khoản mới.</p>
        </section>

        <section>
            <h2 class="text-white font-bold text-lg mb-2">15. Giải quyết tranh chấp</h2>
            <p>Mọi tranh chấp phát sinh sẽ được ưu tiên giải quyết thông qua thương lượng và hòa giải. Trong trường hợp không đạt được thỏa thuận, tranh chấp sẽ được giải quyết theo quy định của pháp luật Việt Nam tại cơ quan có thẩm quyền. Dữ liệu giao dịch được lưu trữ trên hệ thống SteamKey sẽ được sử dụng làm một trong các căn cứ để đối chiếu và giải quyết tranh chấp.</p>
        </section>
    </div>
</div>
@endsection