<div class="container mt-4">
    <h2 class="mb-4">Bình luận</h2>

    <!-- Comments Section -->
    <div class="card">
        <div class="card-header">
            <h5>Nhận xét và đánh giá</h5>
        </div>
        <div class="card-body">
            @if($product->ratings->isEmpty())
                <p class="text-muted">Chưa có nhận xét nào cho sản phẩm này.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($product->ratings as $rating)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center">
                                    <!-- User Avatar -->
                                    <img 
                                        src="{{ asset($rating->user->avatar )}}" 
                                        alt="Avatar of {{ $rating->user->name }}" 
                                        class="rounded-circle me-2" 
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                    
                                    <div>
                                        <strong>{{ $rating->user->name }}</strong>
                                        <br>
                                        <span class="text-muted">{{ $rating->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <!-- Star Rating -->
                                <div style="color: gold;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="mt-2">{{ $rating->comment }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
