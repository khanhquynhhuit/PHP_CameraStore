<footer class="bg-gray-900 text-gray-300 font-sans border-t-4 border-red-600 mt-16">
    <!-- 1. Thanh đăng ký nhận bản tin & Hotline nhanh -->
    <div class="border-b border-gray-800 bg-gray-950 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-red-600/30">
                    📩
                </div>
                <div>
                    <h3 class="text-base font-bold text-white uppercase tracking-tight">ĐĂNG KÝ NHẬN BẢN TIN KHUYẾN MÃI</h3>
                    <p class="text-xs text-gray-400">Nhận ngay voucher giảm giá 500.000đ cho đơn hàng máy ảnh đầu tiên</p>
                </div>
            </div>

            <div class="w-full md:w-auto flex-1 max-w-md">
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký nhận tin khuyến mãi!');" class="flex gap-2">
                    <input type="email" 
                           placeholder="Nhập địa chỉ email của bạn..." 
                           required 
                           class="w-full px-4 py-2.5 rounded-lg bg-gray-900 border border-gray-700 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                    <button type="submit" 
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg uppercase tracking-wider transition shrink-0 shadow-md">
                        Đăng Ký
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Nội dung chính của Footer (Showroom, Chính sách, Hỗ trợ) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            <!-- Cột 1: Thông tin công ty & Logo -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-lg bg-red-600 text-white flex items-center justify-center font-black text-lg">
                        📷
                    </span>
                    <span class="text-2xl font-black tracking-tight text-white">
                        KYMA<span class="text-red-500">.VN</span>
                    </span>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed pr-6">
                    Hệ thống bán lẻ máy ảnh, ống kính, thiết bị âm thanh và phụ kiện quay phim chính hãng hàng đầu Việt Nam. Đại lý ủy quyền chính thức của Sony, Canon, Fujifilm, Nikon.
                </p>

                <!-- Hệ thống Showroom chuẩn Kyma.vn -->
                <div class="space-y-2 text-xs">
                    <div class="flex items-start gap-2">
                        <span class="text-red-500 font-bold mt-0.5">📍</span>
                        <span class="text-gray-300"><strong>Showroom Hà Nội:</strong> Số 122 Trần Thái Tông, Cầu Giấy, Hà Nội</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-red-500 font-bold mt-0.5">📍</span>
                        <span class="text-gray-300"><strong>Showroom Đà Nẵng:</strong> Số 47 Nguyễn Văn Linh, Hải Châu, Đà Nẵng</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-red-500 font-bold mt-0.5">📍</span>
                        <span class="text-gray-300"><strong>Showroom TP.HCM:</strong> Số 474 Nguyễn Thị Minh Khai, Q.3, TP.HCM</span>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <span class="text-red-500 font-bold">📞</span>
                        <span class="text-gray-300">Tổng đài CSKH: <strong class="text-red-400">1900 6789</strong> (8:00 - 21:30)</span>
                    </div>
                </div>
            </div>

            <!-- Cột 2: Hỗ trợ khách hàng -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-l-2 border-red-600 pl-2">
                    Hỗ Trợ Khách Hàng
                </h4>
                <ul class="space-y-2 text-xs text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Hướng dẫn mua hàng online</a></li>
                    <li><a href="#" class="hover:text-white transition">Chính sách giao hàng & COD</a></li>
                    <li><a href="#" class="hover:text-white transition">Chính sách bảo hành 24 tháng</a></li>
                    <li><a href="#" class="hover:text-white transition">Chính sách đổi trả hàng 7 ngày</a></li>
                    <li><a href="#" class="hover:text-white transition">Quy định mua trả góp 0%</a></li>
                    <li><a href="#" class="hover:text-white transition">Tra cứu bảo hành sản phẩm</a></li>
                </ul>
            </div>

            <!-- Cột 3: Về chúng tôi -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-l-2 border-red-600 pl-2">
                    Về KYMA.VN
                </h4>
                <ul class="space-y-2 text-xs text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Giới thiệu về Kyma</a></li>
                    <li><a href="#" class="hover:text-white transition">Tuyển dụng nhân tài</a></li>
                    <li><a href="#" class="hover:text-white transition">Chương trình đối tác Studio</a></li>
                    <li><a href="#" class="hover:text-white transition">Tin tức & Workshop nhiếp ảnh</a></li>
                    <li><a href="#" class="hover:text-white transition">Chính sách bảo mật thông tin</a></li>
                    <li><a href="#" class="hover:text-white transition">Điều khoản sử dụng</a></li>
                </ul>
            </div>

            <!-- Cột 4: Phương thức thanh toán & Chứng nhận -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-l-2 border-red-600 pl-2">
                    Thanh Toán & Kết Nối
                </h4>
                <p class="text-xs text-gray-400 mb-2">Hỗ trợ đa dạng phương thức:</p>
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <span class="bg-gray-800 text-center py-1.5 rounded text-[11px] font-bold text-gray-300 border border-gray-700">COD</span>
                    <span class="bg-gray-800 text-center py-1.5 rounded text-[11px] font-bold text-blue-400 border border-gray-700">VISA</span>
                    <span class="bg-gray-800 text-center py-1.5 rounded text-[11px] font-bold text-red-400 border border-gray-700">VNPAY</span>
                    <span class="bg-gray-800 text-center py-1.5 rounded text-[11px] font-bold text-green-400 border border-gray-700">VIETQR</span>
                    <span class="bg-gray-800 text-center py-1.5 rounded text-[11px] font-bold text-pink-400 border border-gray-700">MOMO</span>
                    <span class="bg-gray-800 text-center py-1.5 rounded text-[11px] font-bold text-amber-400 border border-gray-700">0% LÃI</span>
                </div>

                <p class="text-xs text-gray-400 mb-2">Theo dõi chúng tôi:</p>
                <div class="flex items-center gap-2">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-blue-600 text-white flex items-center justify-center text-xs font-bold transition">f</a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-red-600 text-white flex items-center justify-center text-xs font-bold transition">▶</a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-pink-600 text-white flex items-center justify-center text-xs font-bold transition">📸</a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-blue-400 text-white flex items-center justify-center text-xs font-bold transition">in</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Dòng Copyright & Bản quyền dưới cùng -->
    <div class="border-t border-gray-800 bg-black py-4 px-4 text-center text-xs text-gray-500">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <p>© {{ date('Y') }} KYMA.VN - Đồ án Công nghệ Web & Laravel (Camera Store). All rights reserved.</p>
            <p class="text-gray-400">Được phát triển bởi nhóm sinh viên K7 • Responsive UX/UI by <strong>Hào</strong></p>
        </div>
    </div>
</footer>
