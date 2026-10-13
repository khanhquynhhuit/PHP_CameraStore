<div>
    <h3 class="font-heading text-lg font-semibold tracking-tight text-ink mb-4 pb-2 border-b border-ink/10 flex items-center justify-between">
        <span>Tóm tắt đơn hàng</span>
        <span class="text-sm font-normal text-ink/70">({{ $totalItems ?? 0 }} sản phẩm)</span>
    </h3>

    <div class="space-y-3 text-sm">
        <div class="flex justify-between text-ink/70">
            <span>Tạm tính:</span>
            <span class="font-heading font-bold tabular-nums text-ink">{{ number_format($subtotal ?? 0, 0, ',', '.') }} đ</span>
        </div>

        <div class="flex justify-between text-ink/70">
            <span>Phí vận chuyển:</span>
            <span class="text-success-ink font-medium">Miễn phí</span>
        </div>

        <div class="border-t border-ink/10 pt-3 mt-3 flex justify-between items-baseline">
            <span class="font-heading text-base font-semibold text-ink">Tổng thanh toán:</span>
            <span class="font-heading text-xl font-bold tabular-nums text-ink">{{ number_format($total ?? 0, 0, ',', '.') }} đ</span>
        </div>
        <p class="text-xs text-ink/70 italic text-right">(Đã bao gồm VAT nếu có)</p>
    </div>

    <div class="mt-6 space-y-3">
        @if (($totalItems ?? 0) > 0)
            <x-button variant="primary" size="lg" :href="route('checkout.index')" class="w-full">
                Tiến hành thanh toán
            </x-button>
        @else
            <x-button variant="secondary" size="lg" disabled class="w-full">
                Giỏ hàng trống
            </x-button>
        @endif

        <x-button variant="secondary" size="md" :href="route('products.index')" class="w-full">
            &larr; Tiếp tục mua sắm
        </x-button>
    </div>

    <!-- Chính sách cam kết -->
    <div class="mt-6 pt-6 border-t border-ink/10 space-y-2.5 text-xs text-ink/60">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-success-ink shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Cam kết máy ảnh chính hãng 100%</span>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-success-ink shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Bảo hành chính hãng toàn quốc 12 - 24 tháng</span>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-success-ink shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Đổi trả miễn phí trong 7 ngày nếu lỗi sản xuất</span>
        </div>
    </div>
</div>
