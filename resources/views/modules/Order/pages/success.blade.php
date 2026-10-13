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
        
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-gray-200 shadow-xl relative overflow-hidden">
            <!-- Icon thành công rực rỡ -->
            <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-emerald-100 animate-bounce">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Tiêu đề thông báo -->
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mb-2">
                ĐẶT HÀNG & THANH TOÁN THÀNH CÔNG!
            </h1>
            <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">
                Cảm ơn bạn đã tin tưởng mua sắm thiết bị máy ảnh tại <strong class="text-red-600 font-bold">KYMA.VN</strong>. Đơn hàng của bạn đã được tiếp nhận và xử lý tự động.
            </p>

            <!-- Khối chi tiết đơn hàng tóm tắt -->
            <div class="bg-gray-50 rounded-2xl p-5 mb-6 text-left text-xs space-y-2.5 border border-gray-100">
                <div class="flex justify-between items-center pb-2 border-b border-gray-200 font-bold">
                    <span class="text-gray-500">Mã đơn hàng:</span>
                    <span class="text-red-600 text-sm font-black">{{ $order->code }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Người nhận hàng:</span>
                    <span class="font-bold text-gray-900">{{ $order->receiver_name }} ({{ $order->phone }})</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Địa chỉ giao:</span>
                    <span class="font-bold text-gray-900 truncate max-w-[280px]">{{ $order->shipping_address }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Phương thức:</span>
                    <span class="font-bold text-gray-900">
                        {{ $order->payment_method?->label() ?? 'Thanh toán trực tiếp' }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 font-bold">
                    <span class="text-gray-500">Trạng thái thanh toán:</span>
                    <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full font-black text-[11px]">
                        ✓ ĐÃ THANH TOÁN THÀNH CÔNG
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 text-sm font-black">
                    <span class="text-gray-700">Tổng tiền đã thanh toán:</span>
                    <span class="text-red-600 text-base">{{ number_format($order->total, 0, ',', '.') }} đ</span>
                </div>
            </div>

            <!-- Bộ đếm ngược 5 giây tự động nhảy về trang chủ -->
            <div class="p-4 bg-red-50 rounded-xl border border-red-100 mb-6">
                <p class="text-xs text-red-700 font-medium">
                    Hệ thống sẽ tự động chuyển về trang chủ trong 
                    <span class="font-black text-base text-red-600 inline-block px-1" x-text="countdown">5</span> giây nữa...
                </p>
                <!-- Thanh tiến trình thời gian co lại trong 5s -->
                <div class="w-full bg-red-200 h-1.5 rounded-full mt-2.5 overflow-hidden">
                    <div class="bg-red-600 h-full transition-all duration-1000 ease-linear" 
                         :style="'width: ' + ((countdown / 5) * 100) + '%'"></div>
                </div>
            </div>

            <!-- Nút bấm hành động ngay -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" 
                   class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-md shadow-red-600/30">
                    Về Trang Chủ Ngay
                </a>
                <a href="{{ route('orders.index') }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                    Xem Đơn Hàng Của Tôi
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
