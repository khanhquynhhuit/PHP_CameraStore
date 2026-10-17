<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ __('Chỉnh sửa sản phẩm') }}: <span class="text-primary">{{ $sanPham->name }}</span>
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    Mã SKU: {{ $sanPham->sku }} | Cập nhật lần cuối: {{ $sanPham->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}
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
                <form method="POST" action="{{ route('admin.products.update', $sanPham->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Tên sản phẩm --}}
                        <div class="md:col-span-2">
                            <x-form-input 
                                name="name" 
                                label="Tên sản phẩm máy ảnh" 
                                :value="old('name', $sanPham->name)" 
                                placeholder="VD: Sony Alpha A7 IV Body..." 
                                required 
                            />
                        </div>

                        {{-- Mã SKU --}}
                        <div>
                            <x-form-input 
                                name="sku" 
                                label="Mã SKU sản phẩm" 
                                :value="old('sku', $sanPham->sku)" 
                                placeholder="VD: SONY-A7M4..." 
                                required 
                            />
                        </div>

                        {{-- Thời gian bảo hành --}}
                        <div>
                            <x-form-input 
                                name="warranty_months" 
                                label="Thời gian bảo hành (Tháng)" 
                                type="number" 
                                :value="old('warranty_months', $sanPham->warranty_months)" 
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
                                :selected="old('category_id', $sanPham->category_id)" 
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
                                :selected="old('brand_id', $sanPham->brand_id)" 
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
                                :value="old('price', (int)$sanPham->price)" 
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
                                :value="old('original_price', $sanPham->original_price ? (int)$sanPham->original_price : '')" 
                                placeholder="Để trống nếu không có giảm giá" 
                                min="0" 
                            />
                        </div>

                        {{-- Số lượng tồn kho --}}
                        <div>
                            <x-form-input 
                                name="stock" 
                                label="Số lượng tồn kho" 
                                type="number" 
                                :value="old('stock', $sanPham->stock)" 
                                min="0" 
                                required 
                            />
                        </div>

                        {{-- Trạng thái kinh doanh --}}
                        <div>
                            @php
                                $currentStatus = is_object($sanPham->status) ? $sanPham->status->value : $sanPham->status;
                            @endphp
                            <x-form-select 
                                name="status" 
                                label="Trạng thái kinh doanh" 
                                :options="[
                                    'active' => 'Đang kinh doanh (Hiển thị)',
                                    'hidden' => 'Ẩn khỏi cửa hàng'
                                ]" 
                                :selected="old('status', $currentStatus)" 
                                required 
                            />
                        </div>
                    </div>

                    {{-- Quản lý ảnh đại diện --}}
                    <div class="pt-2">
                        <label class="block font-medium text-sm text-ink mb-1">
                            Ảnh đại diện hiện tại & Thay đổi ảnh
                        </label>
                        @php
                            $primaryImage = $sanPham->primary_image;
                            $imagePath = $primaryImage ? $primaryImage->path : null;
                        @endphp
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-20 h-20 rounded-xl border border-ink/15 bg-base p-1 flex items-center justify-center overflow-hidden">
                                @if ($imagePath)
                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                         alt="{{ $sanPham->name }}" 
                                         class="max-w-full max-h-full object-contain"
                                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';">
                                @else
                                    <span class="text-xs text-ink/40">No image</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/png, image/jpeg, image/webp" 
                                       class="block w-full text-sm text-ink file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-cream hover:file:bg-primary-dark cursor-pointer bg-base border border-ink/20 rounded-xl p-2 focus:outline-none">
                                <p class="text-xs text-ink/50 mt-1">Chọn ảnh mới nếu muốn thay thế ảnh đại diện hiện tại (JPG, PNG, WebP max 2MB).</p>
                                <x-input-error for="image" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- Mô tả chi tiết --}}
                    <x-form-textarea 
                        name="description" 
                        label="Mô tả kỹ thuật & tính năng nổi bật" 
                        rows="4" 
                        :value="old('description', $sanPham->description)" 
                        placeholder="Nhập thông số kỹ thuật, cảm biến, tính năng quay video..." 
                    />

                    {{-- Nút bấm hành động --}}
                    <div class="pt-6 border-t border-ink/10 flex items-center gap-4">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-sm">
                            Cập nhật sản phẩm
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
