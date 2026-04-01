@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="card">
    <h1>{{ $product->name }}</h1>

    @if($product->images->count() > 0)
        <div class="product-slideshow">
            <div class="slideshow-container">
                @foreach($product->images as $index => $image)
                    <div class="slide {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}">
                    </div>
                @endforeach
                
                @if($product->images->count() > 1)
                    <button class="slide-nav prev" onclick="changeSlide(-1)">&#10094;</button>
                    <button class="slide-nav next" onclick="changeSlide(1)">&#10095;</button>
                @endif
            </div>
            
            @if($product->images->count() > 1)
                <div class="slide-dots">
                    @foreach($product->images as $index => $image)
                        <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></span>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <p><strong>Category:</strong> {{ $product->category }}</p>
    <p><strong>Price:</strong> ₱{{ number_format($product->price, 2) }}</p>
    <p><strong>Stock:</strong> {{ $product->stock }}</p>
    <p><strong>Description:</strong> {{ $product->description }}</p>

    <button type="button" class="btn" onclick="openQuantityModal()">Add to Cart</button>

    <!-- Quantity Modal -->
    <div id="quantityModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeQuantityModal()">&times;</span>
            <h2>Select Quantity</h2>
            
            <form action="{{ route('cart.add', $product) }}" method="POST" class="modal-form">
                @csrf
                
                <label for="modalQuantity">How many would you like to add?</label>
                <input type="number" id="modalQuantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                
                <div class="modal-buttons">
                    <button type="submit" class="btn">Add to Cart</button>
                    <button type="button" class="btn btn-secondary" onclick="closeQuantityModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-light">Back to Product List</a>
</div>

<script>
    let currentSlideIndex = 1;
    let slideshowInterval;

    function changeSlide(n) {
        clearInterval(slideshowInterval);
        showSlide(currentSlideIndex += n);
        startSlideshow();
    }

    function currentSlide(n) {
        clearInterval(slideshowInterval);
        showSlide(currentSlideIndex = n);
        startSlideshow();
    }

    function showSlide(n) {
        const slides = document.getElementsByClassName('slide');
        const dots = document.getElementsByClassName('dot');
        
        if (n > slides.length) {
            currentSlideIndex = 1;
        }
        if (n < 1) {
            currentSlideIndex = slides.length;
        }
        
        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove('active');
        }
        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.remove('active');
        }
        
        slides[currentSlideIndex - 1].classList.add('active');
        if (dots.length > 0) {
            dots[currentSlideIndex - 1].classList.add('active');
        }
    }

    function startSlideshow() {
        const slides = document.getElementsByClassName('slide');
        if (slides.length <= 1) return;
        
        slideshowInterval = setInterval(() => {
            currentSlideIndex++;
            showSlide(currentSlideIndex);
        }, 5000); // Change slide every 5 seconds
    }

    // Start slideshow when page loads
    window.addEventListener('load', startSlideshow);

    function openQuantityModal() {
        document.getElementById('quantityModal').style.display = 'flex';
    }

    function closeQuantityModal() {
        document.getElementById('quantityModal').style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('quantityModal');
        if (event.target === modal) {
            closeQuantityModal();
        }
    }
</script>
@endsection
