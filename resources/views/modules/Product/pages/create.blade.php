<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ __('Thêm mới sản phẩm máy ảnh') }}
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    Nhập thông tin sản phẩm máy ảnh, ống kính hoặc phụ kiện mới vào hệ thống
                </p>
            </div>
            <a href="{{ route('admin.products.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-ink/15 text-xs font-semibold text-ink/80 hover:bg-sand/40 transition">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-surface border border-ink/10 shadow-xs sm:rounded-2xl p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Tên sản phẩm --}}
                        <div class="md:col-span-2">
                            <x-form-input 
                                name="name" 
                                label="Tên sản phẩm máy ảnh" 
                                placeholder="VD: Sony Alpha A7 IV Body, Canon EOS R6 Mark II..." 
                                required 
                            />
                        </div>

                        {{-- Mã SKU --}}
                        <div>
                            <x-form-input 
                                name="sku" 
                                label="Mã SKU sản phẩm" 
                                placeholder="VD: SONY-A7M4, CAN-R6M2..." 
                                required 
                            />
                        </div>

                        {{-- Thời gian bảo hành --}}
                        <div>
                            <x-form-input 
                                name="warranty_months" 
                                label="Thời gian bảo hành (Tháng)" 
                                type="number" 
                                :value="old('warranty_months', 12)" 
                                min="0" 
                                required 
                            />
                        </div>

                        {{-- Chọn danh mục --}}
                        <div>
                            <x-form-select 
                                name="category_id" 
                                label="Danh mục sản phẩm" 
                                :options="$danhSachDanhMuc->pluck('name', 'id')->toArray()" 
                                placeholder="-- Chọn danh mục --" 
                                required 
                            />
                        </div>

                        {{-- Chọn thương hiệu --}}
                        <div>
                            <x-form-select 
                                name="brand_id" 
                                label="Thương hiệu" 
                                :options="$danhSachThuongHieu->pluck('name', 'id')->toArray()" 
                                placeholder="-- Chọn thương hiệu --" 
                                required 
                            />
                        </div>

                        {{-- Giá bán thực tế --}}
                        <div>
                            <x-form-input 
                                name="price" 
                                label="Giá bán thực tế (VNĐ)" 
                                type="number" 
                                placeholder="VD: 54990000" 
                                min="0" 
                                required 
                            />
                        </div>

                        {{-- Giá niêm yết / Giá gốc --}}
                        <div>
                            <x-form-input 
                                name="original_price" 
                                label="Giá gốc niêm yết (VNĐ)" 
                                type="number" 
                                placeholder="Để trống nếu không có giảm giá" 
                                min="0" 
                            />
                        </div>

                        {{-- Số lượng tồn kho --}}
                        <div>
                            <x-form-input 
                                name="stock" 
                                label="Số lượng tồn kho ban đầu" 
                                type="number" 
                                :value="old('stock', 10)" 
                                min="0" 
                                required 
                            />
                        </div>

                        {{-- Trạng thái kinh doanh --}}
                        <div>
                            <x-form-select 
                                name="status" 
                                label="Trạng thái kinh doanh" 
                                :options="[
                                    'active' => 'Đang kinh doanh (Hiển thị)',
                                    'hidden' => 'Ẩn khỏi cửa hàng'
                                ]" 
                                :selected="old('status', 'active')" 
                                required 
                            />
                        </div>
                    </div>

                    {{-- Upload ảnh đại diện sản phẩm --}}
                    <div class="pt-2">
                        <label for="image" class="block font-medium text-sm text-ink mb-1">
                            Ảnh đại diện máy ảnh <span class="text-xs font-normal text-ink/60">(Định dạng: JPG, PNG, WebP tối đa 2MB)</span>
                        </label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/png, image/jpeg, image/webp" 
                               class="block w-full text-sm text-ink file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-cream hover:file:bg-primary-dark cursor-pointer bg-base border border-ink/20 rounded-xl p-2 focus:outline-none">
                        <x-input-error for="image" class="mt-1" />
                    </div>

                    {{-- Mô tả chi tiết --}}
                    <x-form-textarea 
                        name="description" 
                        label="Mô tả kỹ thuật & tính năng nổi bật" 
                        rows="4" 
                        placeholder="Nhập thông số cảm biến, ống kính, quay video, tính năng lấy nét..." 
                    />

                    {{-- Nút bấm hành động --}}
                    <div class="pt-6 border-t border-ink/10 flex items-center gap-4">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-sm">
                            Lưu sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" 
                           class="text-sm font-medium text-ink/60 hover:text-ink transition">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
