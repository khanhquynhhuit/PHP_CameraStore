<footer class="bg-ink text-cream/80 font-sans border-t border-beige/30 mt-16">
    <!-- 1. Thanh đăng ký nhận bản tin & Hotline nhanh -->
    <div class="border-b border-white/10 bg-[#221B19] py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/20 text-accent flex items-center justify-center text-2xl shadow-sm border border-primary/30">
                    <x-phosphor-envelope-simple-light class="w-6 h-6 text-accent" />
                </div>
                <div>
                    <h3 class="font-heading text-base font-semibold text-cream uppercase tracking-tight">ĐĂNG KÝ NHẬN BẢN TIN KHUYẾN MÃI</h3>
                    <p class="text-xs text-cream/70">Nhận ngay voucher giảm giá 500.000đ cho đơn hàng máy ảnh đầu tiên</p>
                </div>
            </div>

            <div class="w-full md:w-auto flex-1 max-w-md">
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký nhận tin khuyến mãi!');" class="flex gap-2">
                    <input type="email" 
                           placeholder="Nhập địa chỉ email của bạn..." 
                           required 
                           class="w-full px-4 py-2.5 rounded-lg bg-ink border border-white/20 text-sm text-cream placeholder-cream/40 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    <x-button variant="primary" size="md" type="submit" class="shrink-0 uppercase tracking-wider">
                        Đăng Ký
                    </x-button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Nội dung chính của Footer (Showroom, Chính sách, Hỗ trợ) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            <!-- Cột 1: Thông tin công ty & Logo -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="CameraStore.vn" 
                         class="w-12 h-12 object-contain">
                    <span class="font-heading text-2xl font-bold tracking-tight text-cream">
                        CAMERASTORE<span class="text-primary">.VN</span>
                    </span>
                </div>
                <p class="text-xs text-cream/70 leading-relaxed pr-6">
                    Hệ thống bán lẻ máy ảnh, ống kính, thiết bị âm thanh và phụ kiện quay phim chính hãng hàng đầu Việt Nam. Đại lý ủy quyền chính thức của Sony, Canon, Fujifilm, Nikon.
                </p>

                <!-- Hệ thống Showroom CameraStore.vn -->
                <div class="space-y-2 text-xs">
                    <div class="flex items-start gap-2">
                        <span class="text-accent font-bold mt-0.5">📍</span>
                        <span class="text-cream/80"><strong>Showroom Hà Nội:</strong> Số 122 Trần Thái Tông, Cầu Giấy, Hà Nội</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-accent font-bold mt-0.5">📍</span>
                        <span class="text-cream/80"><strong>Showroom Đà Nẵng:</strong> Số 47 Nguyễn Văn Linh, Hải Châu, Đà Nẵng</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-accent font-bold mt-0.5">📍</span>
                        <span class="text-cream/80"><strong>Showroom TP.HCM:</strong> Số 474 Nguyễn Thị Minh Khai, Q.3, TP.HCM</span>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <span class="text-accent font-bold">📞</span>
                        <span class="text-cream/80">Tổng đài CSKH: <strong class="font-heading font-bold text-cream bg-primary px-1.5 py-0.5 rounded">1900 6789</strong> (8:00 - 21:30)</span>
                    </div>
                </div>
            </div>

            <!-- Cột 2: Hỗ trợ khách hàng -->
            <div>
                <h4 class="font-heading text-sm font-semibold text-cream uppercase tracking-wider mb-4 border-l-2 border-primary pl-2">
                    Hỗ Trợ Khách Hàng
                </h4>
                <ul class="space-y-2 text-xs text-cream/70">
                    <li><a href="#" class="hover:text-accent transition">Hướng dẫn mua hàng online</a></li>
                    <li><a href="#" class="hover:text-accent transition">Chính sách giao hàng & COD</a></li>
                    <li><a href="#" class="hover:text-accent transition">Chính sách bảo hành 24 tháng</a></li>
                    <li><a href="#" class="hover:text-accent transition">Chính sách đổi trả hàng 7 ngày</a></li>
                    <li><a href="#" class="hover:text-accent transition">Quy định mua trả góp 0%</a></li>
                    <li><a href="#" class="hover:text-accent transition">Tra cứu bảo hành sản phẩm</a></li>
                </ul>
            </div>

            <!-- Cột 3: Về chúng tôi -->
            <div>
                <h4 class="font-heading text-sm font-semibold text-cream uppercase tracking-wider mb-4 border-l-2 border-primary pl-2">
                    Về CAMERASTORE.VN
                </h4>
                <ul class="space-y-2 text-xs text-cream/70">
                    <li><a href="#" class="hover:text-accent transition">Giới thiệu về CameraStore</a></li>
                    <li><a href="#" class="hover:text-accent transition">Tuyển dụng nhân tài</a></li>
                    <li><a href="#" class="hover:text-accent transition">Chương trình đối tác Studio</a></li>
                    <li><a href="#" class="hover:text-accent transition">Tin tức & Workshop nhiếp ảnh</a></li>
                    <li><a href="#" class="hover:text-accent transition">Chính sách bảo mật thông tin</a></li>
                    <li><a href="#" class="hover:text-accent transition">Điều khoản sử dụng</a></li>
                </ul>
            </div>

            <!-- Cột 4: Phương thức thanh toán & Chứng nhận -->
            <div>
                <h4 class="font-heading text-sm font-semibold text-cream uppercase tracking-wider mb-4 border-l-2 border-primary pl-2">
                    Thanh Toán & Kết Nối
                </h4>
                <p class="text-xs text-cream/70 mb-2">Hỗ trợ đa dạng phương thức:</p>
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <span class="bg-[#221B19] text-center py-1.5 rounded text-[11px] font-bold text-cream border border-white/10">COD</span>
                    <span class="bg-[#221B19] text-center py-1.5 rounded text-[11px] font-bold text-cream border border-white/10">VISA</span>
                    <span class="bg-[#221B19] text-center py-1.5 rounded text-[11px] font-bold text-cream border border-white/10">VNPAY</span>
                    <span class="bg-[#221B19] text-center py-1.5 rounded text-[11px] font-bold text-cream border border-white/10">VIETQR</span>
                    <span class="bg-[#221B19] text-center py-1.5 rounded text-[11px] font-bold text-cream border border-white/10">MOMO</span>
                    <span class="bg-[#221B19] text-center py-1.5 rounded text-[11px] font-bold text-accent border border-white/10">0% LÃI</span>
                </div>

                <p class="text-xs text-cream/70 mb-2">Theo dõi chúng tôi:</p>
                <div class="flex items-center gap-2">
                    <a href="#" class="w-8 h-8 rounded-full bg-[#221B19] hover:bg-primary hover:text-cream text-cream border border-white/10 flex items-center justify-center text-xs font-bold transition">f</a>
                    <a href="#" class="w-8 h-8 rounded-full bg-[#221B19] hover:bg-primary hover:text-cream text-cream border border-white/10 flex items-center justify-center text-xs font-bold transition">▶</a>
                    <a href="#" class="w-8 h-8 rounded-full bg-[#221B19] hover:bg-primary hover:text-cream text-cream border border-white/10 flex items-center justify-center text-xs font-bold transition">📸</a>
                    <a href="#" class="w-8 h-8 rounded-full bg-[#221B19] hover:bg-primary hover:text-cream text-cream border border-white/10 flex items-center justify-center text-xs font-bold transition">in</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Dòng Copyright & Bản quyền dưới cùng -->
    <div class="border-t border-white/10 bg-[#1C1615] py-4 px-4 text-center text-xs text-cream/60">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <p>© {{ date('Y') }} CAMERASTORE.VN - Đồ án Công nghệ Web & Laravel (Camera Store). All rights reserved.</p>
            <p class="text-cream/50">Được phát triển bởi nhóm sinh viên K7 • Terracotta Aesthetic Theme</p>
        </div>
    </div>
</footer>
