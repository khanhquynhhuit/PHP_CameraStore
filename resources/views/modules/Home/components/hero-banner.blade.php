<div class="my-6 space-y-6">
    <!-- 3 Banners trang chủ theo thiết kế mới (Cam Đất Terracotta) -->
    @include('modules.Home.partials.banners')

    <!-- 4 Khối cam kết dịch vụ cao cấp (Phong cách Vintage con dấu với Phosphor Icons) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-surface p-5 rounded-2xl border border-beige/50 shadow-sm">
        <!-- Item 1: Chính hãng -->
        <div class="flex items-center gap-3.5 group">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-sand border border-beige/60 text-primary shrink-0 group-hover:scale-105 transition duration-300 shadow-2xs">
                <x-phosphor-seal-check-light class="w-6 h-6 text-primary" />
            </span>
            <div>
                <p class="font-heading font-semibold text-ink text-xs sm:text-sm tracking-tight">100% Chính Hãng</p>
                <p class="text-ink/65 text-[11px] mt-0.5">Bảo hành toàn quốc 24 tháng</p>
            </div>
        </div>

        <!-- Item 2: Trả góp -->
        <div class="flex items-center gap-3.5 group sm:border-l sm:border-beige/40 sm:pl-4">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-mist border border-beige/60 text-navy shrink-0 group-hover:scale-105 transition duration-300 shadow-2xs">
                <x-phosphor-credit-card-light class="w-6 h-6 text-navy" />
            </span>
            <div>
                <p class="font-heading font-semibold text-ink text-xs sm:text-sm tracking-tight">Trả Góp 0% Lãi Suất</p>
                <p class="text-ink/65 text-[11px] mt-0.5">Duyệt hồ sơ nhanh 15 phút</p>
            </div>
        </div>

        <!-- Item 3: Miễn phí vận chuyển -->
        <div class="flex items-center gap-3.5 group md:border-l md:border-beige/40 md:pl-4">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-sand border border-beige/60 text-primary shrink-0 group-hover:scale-105 transition duration-300 shadow-2xs">
                <x-phosphor-truck-light class="w-6 h-6 text-primary" />
            </span>
            <div>
                <p class="font-heading font-semibold text-ink text-xs sm:text-sm tracking-tight">Giao Hàng Miễn Phí</p>
                <p class="text-ink/65 text-[11px] mt-0.5">Đóng thùng chống sốc chuẩn</p>
            </div>
        </div>

        <!-- Item 4: Đổi trả -->
        <div class="flex items-center gap-3.5 group sm:border-l sm:border-beige/40 sm:pl-4">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-mist border border-beige/60 text-primary-dark shrink-0 group-hover:scale-105 transition duration-300 shadow-2xs">
                <x-phosphor-arrows-clockwise-light class="w-6 h-6 text-primary-dark" />
            </span>
            <div>
                <p class="font-heading font-semibold text-ink text-xs sm:text-sm tracking-tight">Đổi Trả Trong 7 Ngày</p>
                <p class="text-ink/65 text-[11px] mt-0.5">Lỗi 1 đổi 1 an tâm mua sắm</p>
            </div>
        </div>
    </div>
</div>
