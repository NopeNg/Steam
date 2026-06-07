(function() {
    // Predefined answers
    const FAQ = {
        muagame: '🎮 Hướng dẫn mua Game:\n1. Tìm game trên trang chủ\n2. Chọn phiên bản → Thêm vào giỏ\n3. Thanh toán qua QR (VNPAY)\n4. Key sẽ xuất hiện trong Thư viện',
        kichhoat: '🔑 Hướng dẫn kích hoạt Key:\n1. Vào Thư viện → Chưa kích hoạt\n2. Bấm "XEM KEY" để xem mã\n3. Mở game → Redeem Code → Dán key\n4. Quay lại bấm "KÍCH HOẠT"',
        hotro: '❓ Trung tâm hỗ trợ:\n🛒 Thanh toán: Quét QR qua VNPAY\n🔑 Lỗi key: Liên hệ Admin\n📞 Email: admin@gamekey.com\n📞 Hotline: +84 123 456 789'
    };

    const API_URL = '/chat';
    const CSRF = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Create chat box HTML
    const html = `
    <style>
        #cb{position:fixed;bottom:90px;right:20px;width:360px;height:500px;background:#1b2838;
        border:1px solid #2a475e;border-radius:12px;display:none;flex-direction:column;z-index:9999;
        box-shadow:0 8px 30px rgba(0,0,0,0.5);font-family:system-ui,sans-serif;}
        #cb.show{display:flex}
        #cb-hd{padding:12px 14px;border-bottom:1px solid #2a475e;display:flex;align-items:center;justify-content:space-between}
        #cb-hd h4{margin:0;color:#fff;font-size:14px}
        #cb-hd small{color:#38b2ac;font-size:11px}
        #cb-msgs{flex:1;overflow-y:auto;padding:10px 12px;display:flex;flex-direction:column;gap:6px}
        .u{align-self:flex-end;max-width:80%;background:#38b2ac;color:#fff;padding:8px 12px;
        border-radius:12px 0 12px 12px;font-size:13px;line-height:1.4}
        .b{align-self:flex-start;max-width:80%;background:#101822;border:1px solid #2a475e;color:#c7d5e0;
        padding:8px 12px;border-radius:0 12px 12px 12px;font-size:13px;line-height:1.5;white-space:pre-line}
        .b.err{color:#ff6b6b;border-color:#5a2a2a}
        #cb-in{padding:10px 12px;border-top:1px solid #2a475e;display:flex;gap:6px}
        #cb-in input{flex:1;background:#101822;border:1px solid #2a475e;border-radius:8px;padding:8px 12px;color:#c7d5e0;font-size:13px;outline:none}
        #cb-in input:focus{border-color:#38b2ac}
        #cb-in button{width:36px;height:36px;border-radius:8px;border:none;background:#38b2ac;color:#fff;cursor:pointer;font-size:16px}
        #cb-btn{position:fixed;bottom:20px;right:20px;width:52px;height:52px;border-radius:14px;
        background:linear-gradient(135deg,#38b2ac,#319795);color:#fff;border:none;cursor:pointer;z-index:9999;font-size:22px;
        box-shadow:0 4px 16px rgba(56,178,172,0.4);transition:transform .2s}
        #cb-btn:hover{transform:scale(1.08)}
        .qr{display:inline-block;background:#2a475e;color:#c7d5e0;padding:5px 10px;border-radius:6px;
        font-size:11px;cursor:pointer;border:none;margin:3px;transition:all .15s}
        .qr:hover{background:#38b2ac;color:#fff}
        #cb-msgs::-webkit-scrollbar{width:4px}
        #cb-msgs::-webkit-scrollbar-thumb{background:#2a475e;border-radius:4px}
    </style>
    <button id="cb-btn">💬</button>
    <div id="cb">
        <div id="cb-hd">
            <div><h4>🎮 GameKey Support</h4><small>● Online</small></div>
            <button onclick="document.getElementById('cb').classList.remove('show')" 
                    style="background:none;border:none;color:#8f98a0;font-size:20px;cursor:pointer">✕</button>
        </div>
        <div id="cb-msgs"></div>
        <div id="cb-in">
            <input type="text" placeholder="Nhập câu hỏi..." id="cb-txt">
            <button id="cb-send">➤</button>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', html);

    const box = document.getElementById('cb');
    const msgs = document.getElementById('cb-msgs');
    const txt = document.getElementById('cb-txt');

    // Add message
    function add(text, type) {
        const d = document.createElement('div');
        d.className = type + (type === 'err' ? ' err' : '');
        d.textContent = text;
        msgs.appendChild(d);
        msgs.scrollTop = msgs.scrollHeight;
    }

    // Welcome
    add('👋 Xin chào! Chào mừng tới cửa hàng key game bản quyền SteamKey! Chúng tôi có thể giúp gì cho bạn ?', 'b');
    add('vui lòng chọn chủ đề hoặc nhập câu hỏi bên dưới:', 'b');
    msgs.lastChild.insertAdjacentHTML('afterend', `
        <div style="text-align:center;margin:4px 0">
            <button class="qr" onclick="window._cbQ('muagame')">🎮 Mua game</button>
            <button class="qr" onclick="window._cbQ('kichhoat')">🔑 Kích hoạt key</button>
            <button class="qr" onclick="window._cbQ('hotro')">❓ Hỗ trợ</button>
        </div>`);

    window._cbQ = function(q) {
        add(q === 'muagame' ? '🎮 Mua game' : q === 'kichhoat' ? '🔑 Kích hoạt key' : '❓ Hỗ trợ', 'u');
        setTimeout(() => add(FAQ[q], 'b'), 200);
    };

    // Send to API
    async function send() {
        const msg = txt.value.trim();
        if (!msg) return;
        txt.value = '';
        add(msg, 'u');

        try {
            const res = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF() },
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            // Gemini: data.candidates[0].content.parts[0].text
            let text = data?.candidates?.[0]?.content?.parts?.[0]?.text;
            if (!text) {
                // Handle error responses
                const errMsg = data?.error?.message || data?.error || '';
                text = errMsg || 'Không nhận được phản hồi từ AI.';
            }
            add(text, 'b');
        } catch (e) {
            add('⚠️ Lỗi kết nối. Vui lòng thử lại.', 'err');
        }
    }

    // Events
    document.getElementById('cb-btn').onclick = () => box.classList.toggle('show');
    document.getElementById('cb-send').onclick = send;
    txt.onkeypress = (e) => { if (e.key === 'Enter') send(); };
})();