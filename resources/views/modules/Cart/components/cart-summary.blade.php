<div>
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center justify-between">
        <span>Tóm tắt đơn hàng</span>
        <span class="text-sm font-normal text-gray-500">({{ $totalItems ?? 0 }} sản phẩm)</span>
    </h3>

    <div class="space-y-3 text-sm">
        <div class="flex justify-between text-gray-600">
            <span>Tạm tính:</span>
            <span class="font-medium text-gray-900">{{ number_format($subtotal ?? 0, 0, ',', '.') }} đ</span>
        </div>

        <div class="flex justify-between text-gray-600">
            <span>Phí vận chuyển:</span>
            <span class="text-green-600 font-medium">Miễn phí</span>
        </div>

        <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between items-baseline">
            <span class="text-base font-semibold text-gray-900">Tổng thanh toán:</span>
            <span class="text-xl font-bold text-indigo-600">{{ number_format($total ?? 0, 0, ',', '.') }} đ</span>
        </div>
        <p class="text-xs text-gray-400 italic text-right">(Đã bao gồm VAT nếu có)</p>
    </div>

    <div class="mt-6 space-y-3">
        @if (($totalItems ?? 0) > 0)
            <a href="{{ route('checkout.index') }}" 
               class="w-full flex items-center justify-center py-3 px-4 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 active:bg-indigo-800 shadow-sm transition">
                Tiến hành thanh toán
            </a>
        @else
            <button disabled 
                    class="w-full py-3 px-4 bg-gray-200 text-gray-400 rounded-lg font-semibold text-sm cursor-not-allowed">
                Giỏ hàng trống
            </button>
        @endif

        <a href="{{ route('products.index') }}" 
           class="w-full flex items-center justify-center py-2.5 px-4 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition">
            &larr; Tiếp tục mua sắm
        </a>
    </div>

    <!-- Chính sách cam kết -->
    <div class="mt-6 pt-6 border-t border-gray-100 space-y-2.5 text-xs text-gray-500">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Cam kết máy ảnh chính hãng 100%</span>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Bảo hành chính hãng toàn quốc 12 - 24 tháng</span>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Đổi trả miễn phí trong 7 ngày nếu lỗi sản xuất</span>
        </div>
    </div>
</div>
