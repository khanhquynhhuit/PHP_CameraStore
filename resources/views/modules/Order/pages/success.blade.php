<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-12 sm:py-20 text-center" 
         x-data="{ countdown: 5 }" 
         x-init="
             let timer = setInterval(() => {
                 countdown--;
                 if (countdown <= 0) {
                     clearInterval(timer);
                     window.location.href = '{{ route('home') }}';
                 }
             }, 1000);
         ">
        
        <div class="bg-surface rounded-3xl p-8 sm:p-12 border border-ink/10 shadow-sm relative overflow-hidden">
            <!-- Icon thành công -->
            <div class="w-20 h-20 bg-success text-success-ink rounded-full flex items-center justify-center mx-auto mb-6 border border-success-ink/20 animate-bounce">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Tiêu đề thông báo -->
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-ink tracking-tight mb-2">
                ĐẶT HÀNG & THANH TOÁN THÀNH CÔNG!
            </h1>
            <p class="text-sm text-ink/70 mb-6 max-w-md mx-auto">
                Cảm ơn bạn đã tin tưởng mua sắm thiết bị máy ảnh tại <strong class="text-ink font-bold">CAMERASTORE.VN</strong>. Đơn hàng của bạn đã được tiếp nhận và xử lý tự động.
            </p>

            <!-- Khối chi tiết đơn hàng tóm tắt -->
            <div class="bg-base rounded-2xl p-5 mb-6 text-left text-xs space-y-2.5 border border-ink/10">
                <div class="flex justify-between items-center pb-2 border-b border-ink/10 font-bold">
                    <span class="text-ink/70">Mã đơn hàng:</span>
                    <span class="font-heading text-cream bg-primary px-2.5 py-0.5 rounded-full text-sm font-bold tabular-nums">{{ $order->code }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-ink/70">Người nhận hàng:</span>
                    <span class="font-bold text-ink">{{ $order->receiver_name }} ({{ $order->phone }})</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-ink/70">Địa chỉ giao:</span>
                    <span class="font-bold text-ink truncate max-w-[280px]">{{ $order->shipping_address }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-ink/70">Phương thức:</span>
                    <span class="font-bold text-ink">
                        {{ $order->payment_method?->label() ?? 'Thanh toán trực tiếp' }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-ink/10 font-bold">
                    <span class="text-ink/70">Trạng thái thanh toán:</span>
                    <span class="text-success-ink bg-success px-2.5 py-0.5 rounded-full font-semibold text-[11px]">
                        ✓ ĐÃ THANH TOÁN THÀNH CÔNG
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-ink/10 text-sm font-bold">
                    <span class="font-heading font-semibold text-ink">Tổng tiền đã thanh toán:</span>
                    <span class="font-heading font-bold tabular-nums text-primary text-base">{{ number_format($order->total, 0, ',', '.') }} đ</span>
                </div>
            </div>

            <!-- Bộ đếm ngược 5 giây tự động nhảy về trang chủ -->
            <div class="p-4 bg-primary/20 rounded-xl border border-ink/10 mb-6">
                <p class="text-xs text-ink font-medium">
                    Hệ thống sẽ tự động chuyển về trang chủ trong 
                    <span class="font-heading font-bold tabular-nums text-base text-ink inline-block px-1" x-text="countdown">5</span> giây nữa...
                </p>
                <!-- Thanh tiến trình thời gian co lại trong 5s -->
                <div class="w-full bg-primary/30 h-1.5 rounded-full mt-2.5 overflow-hidden">
                    <div class="bg-primary h-full transition-all duration-1000 ease-linear" 
                         :style="'width: ' + ((countdown / 5) * 100) + '%'"></div>
                </div>
            </div>

            <!-- Nút bấm hành động ngay -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <x-button variant="primary" size="lg" :href="route('home')" class="uppercase tracking-wider">
                    Về Trang Chủ Ngay
                </x-button>
                <x-button variant="outline" size="lg" :href="route('orders.index')" class="uppercase tracking-wider">
                    Xem Đơn Hàng Của Tôi
                </x-button>
            </div>
        </div>
    </div>
</x-app-layout>
