<section
    id="reviews"
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10"
>
    <div class="bg-surface border border-ink/10 rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row sm:items-center
                    sm:justify-between gap-4 border-b
                    border-ink/10 pb-5">
            <div>
                <h2 class="font-heading text-xl font-semibold text-ink">
                    Đánh giá sản phẩm
                </h2>

                <p class="text-sm text-ink/60 mt-1">
                    Đánh giá từ khách hàng đã mua sản phẩm
                </p>
            </div>

            <div class="text-right">
                <div class="text-3xl font-bold text-warning-ink">
                    ★
                    {{ number_format(
                        (float) ($product->reviews_avg_rating ?? 0),
                        1
                    ) }}
                </div>

                <p class="text-sm text-ink/60">
                    {{ $product->reviews_count }} đánh giá
                </p>
            </div>
        </div>

        @auth
            @if (auth()->user()->isCustomer())
                @if ($reviewableOrders->isNotEmpty())
                    <form
                        method="POST"
                        action="{{ route(
                            'reviews.store',
                            $product->id
                        ) }}"
                        class="mt-6 p-5 bg-base border
                               border-ink/10 rounded-xl"
                    >
                        @csrf

                        <h3 class="font-semibold text-ink mb-4">
                            Viết đánh giá của bạn
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    for="order_id"
                                    class="block text-sm font-medium
                                           text-ink mb-2"
                                >
                                    Đơn hàng đã mua
                                </label>

                                <select
                                    id="order_id"
                                    name="order_id"
                                    class="w-full rounded-lg
                                           border-ink/20"
                                    required
                                >
                                    <option value="">
                                        -- Chọn đơn hàng --
                                    </option>

                                    @foreach ($reviewableOrders as $order)
                                        <option
                                            value="{{ $order->id }}"
                                            @selected(
                                                old('order_id')
                                                == $order->id
                                            )
                                        >
                                            {{ $order->code }}
                                            -
                                            {{ $order->created_at
                                                ->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('order_id')
                                    <p class="text-sm text-red-600 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    for="rating"
                                    class="block text-sm font-medium
                                           text-ink mb-2"
                                >
                                    Số sao
                                </label>

                                <select
                                    id="rating"
                                    name="rating"
                                    class="w-full rounded-lg
                                           border-ink/20"
                                    required
                                >
                                    <option value="">
                                        -- Chọn số sao --
                                    </option>
                                    <option value="5">
                                        ★★★★★ - Tuyệt vời
                                    </option>
                                    <option value="4">
                                        ★★★★☆ - Tốt
                                    </option>
                                    <option value="3">
                                        ★★★☆☆ - Bình thường
                                    </option>
                                    <option value="2">
                                        ★★☆☆☆ - Chưa tốt
                                    </option>
                                    <option value="1">
                                        ★☆☆☆☆ - Không hài lòng
                                    </option>
                                </select>

                                @error('rating')
                                    <p class="text-sm text-red-600 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label
                                for="comment"
                                class="block text-sm font-medium
                                       text-ink mb-2"
                            >
                                Nhận xét
                            </label>

                            <textarea
                                id="comment"
                                name="comment"
                                rows="4"
                                maxlength="1000"
                                class="w-full rounded-lg border-ink/20"
                                placeholder="Chia sẻ trải nghiệm của bạn..."
                            >{{ old('comment') }}</textarea>

                            @error('comment')
                                <p class="text-sm text-red-600 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="mt-4 px-5 py-2.5 rounded-lg
                                   bg-primary text-white font-semibold
                                   hover:brightness-95"
                        >
                            Gửi đánh giá
                        </button>
                    </form>
                @else
                    <div class="mt-6 p-4 bg-base rounded-xl
                                border border-ink/10 text-sm text-ink/70">
                        Bạn chỉ có thể đánh giá sau khi đã mua và
                        nhận sản phẩm thành công.
                    </div>
                @endif
            @endif
        @else
            <div class="mt-6 p-4 bg-base rounded-xl border border-ink/10">
                <a
                    href="{{ route('login') }}"
                    class="text-primary font-semibold hover:underline"
                >
                    Đăng nhập
                </a>
                để đánh giá sản phẩm.
            </div>
        @endauth

        <div class="mt-8 space-y-4">
            @forelse ($product->reviews as $review)
                <article class="border-b border-ink/10 pb-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold text-ink">
                                {{ $review->user?->name
                                    ?? 'Khách hàng' }}
                            </p>

                            <p class="text-warning-ink mt-1">
                                {{ str_repeat(
                                    '★',
                                    $review->rating
                                ) }}
                                <span class="text-ink/30">
                                    {{ str_repeat(
                                        '☆',
                                        5 - $review->rating
                                    ) }}
                                </span>
                            </p>
                        </div>

                        <time class="text-xs text-ink/50">
                            {{ $review->created_at->format('d/m/Y') }}
                        </time>
                    </div>

                    @if ($review->comment)
                        <p class="mt-3 text-sm text-ink/70">
                            {{ $review->comment }}
                        </p>
                    @endif
                </article>
            @empty
                <p class="py-6 text-center text-ink/60">
                    Sản phẩm chưa có đánh giá.
                </p>
            @endforelse
        </div>
    </div>
</section>