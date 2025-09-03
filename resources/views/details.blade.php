@extends('layouts.app')
@section('content')
<style>
    .filled-heart{
        color: red;
    }
</style>
<main class="pt-90">
  <div class="mb-md-1 pb-md-3"></div>
  <section class="product-single container">
    <div class="row">
      <div class="col-lg-7">
        <div class="product-single__media" data-media-type="vertical-thumbnail">
          <div class="product-single__image">
            <div class="swiper-container">
              <div class="swiper-wrapper">

                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{asset('uploads/products')}}/{{ $product->image }}"
                    width="674" height="674" alt="" />
                  <a data-fancybox="gallery" href="{{asset('uploads/products')}}/{{ $product->image }}"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>

                @foreach (explode(',', $product->gallery ) as $gimg)
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{asset('uploads/products')}}/{{ $gimg }}" width="674"
                    height="674" alt="" />
                  <a data-fancybox="gallery" href="{{asset('uploads/products')}}/{{ $gimg }}" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>
                @endforeach

              </div>
              <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_prev_sm" />
                </svg></div>
              <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_next_sm" />
                </svg></div>
            </div>
          </div>
          <div class="product-single__thumbnail">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto thumbnail-img"
                    src="{{asset('uploads/products/thumbnails')}}/{{ $product->image }}" width="104" height="104"
                    alt="" data-image="{{ $product->image }}" data-selected="true" />
                </div>
              </div>

              @foreach (explode(',', $product->gallery) as $gimg)
              <div class="swiper-slide product-single__image-item">
                <img loading="lazy" class="h-auto thumbnail-img"
                  src="{{ asset('uploads/products/thumbnails') }}/{{ $gimg }}" width="104" height="104"
                  data-image="{{ $gimg }}" data-selected="false" alt="" />
              </div>
              @endforeach


            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="d-flex justify-content-between mb-4 pb-md-2">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
          </div><!-- /.breadcrumb -->

          <div
            class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
            <a href="#" class="text-uppercase fw-medium"><svg width="10" height="10" viewBox="0 0 25 25"
                xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_prev_md" />
              </svg><span class="menu-link menu-link_us-s">Prev</span></a>
            <a href="#" class="text-uppercase fw-medium"><span class="menu-link menu-link_us-s">Next</span><svg
                width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_next_md" />
              </svg></a>
          </div><!-- /.shop-acs -->
        </div>
        <h1 class="product-single__name">{{ $product->name }}</h1>
        <div class="product-single__price">
          <span class="current-price">
            @if($product->sale_price)
            <s>RM{{$product->regular_price}}</s> RM{{$product->sale_price}}
            @else
            {{ $product->regular_price }}
            @endif
          </span>
        </div>
        <div class="product-single__short-desc">
          <p>{{ $product->short_description }}</p>
        </div>

        <!-- Selected Image Display -->
        <div class="selected-image-display mb-3">
          <label class="form-label fw-medium">Selected Image:</label>
          <div class="selected-image-preview">
            <img id="selectedImagePreview" src="{{asset('uploads/products/thumbnails')}}/{{ $product->image }}"
              width="80" height="80" alt="Selected Image" class="border rounded" />
            <span id="selectedImageName" class="ms-2 text-muted">{{ $product->image }}</span>
          </div>
        </div>
        <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">

          @csrf
          <div class="product-single__addtocart">
            <div class="qty-control position-relative">
              <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
              <div class="qty-control__reduce">-</div>
              <div class="qty-control__increase">+</div>
            </div><!-- .qty-control -->
            <input type="hidden" name="id" value="{{ $product->id }}" />
            <input type="hidden" name="name" value="{{ $product->name }}" />
            <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price}}" />
            <input type="hidden" name="selected_image" id="selectedImageInput" value="{{ $product->image }}" />
            <button type="submit" class="btn btn-primary btn-addtocart" data-aside="cartDrawer">Add to Cart</button>
          </div>
        </form>

        <div class="product-single__addtolinks">
          @if(Cart::instance('wishlist')->content()->where('id',$product->id)->count()>0)
          <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist filled-heart"><svg width="16" height="16" viewBox="0 0 20 20"
              fill="none" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_heart" />
            </svg><span>Remove to Wishlist</span></a>
          @else
          <form method="POST" action="{{route('wishlist.add')}}" id='wishlist-form'>
            @csrf
            <input type="hidden" name="id" value="{{$product->id}}" />
            <input type="hidden" name="name" value="{{$product->name}}" />
            <input type="hidden" name="price" value="{{$product->sale_price == '' ? $product->regular_price : $product->sale_price}}" />
            <input type="hidden" name="quantity" value="1" />
            <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist" onclick="document.getElementById('wishlist-form').submit();"><svg width="16" height="16" viewBox="0 0 20 20"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg><span>Add to Wishlist</span></a>
          </form>
          @endif


          <share-button class="share-button">
            <button class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center">
              <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_sharing" />
              </svg>
              <span>Share</span>
            </button>
            <details id="Details-share-template__main" class="m-1 xl:m-1.5" hidden="">
              <summary class="btn-solid m-1 xl:m-1.5 pt-3.5 pb-3 px-5">+</summary>
              <div id="Article-share-template__main"
                class="share-button__fallback flex items-center absolute top-full left-0 w-full px-2 py-4 bg-container shadow-theme border-t z-10">
                <div class="field grow mr-4">
                  <label class="field__label sr-only" for="url">Link</label>
                  <input type="text" class="field__input w-full" id="url"
                    value="https://uomo-crystal.myshopify.com/blogs/news/go-to-wellness-tips-for-mental-health"
                    placeholder="Link" onclick="this.select();" readonly="">
                </div>
                <button class="share-button__copy no-js-hidden">
                  <svg class="icon icon-clipboard inline-block mr-1" width="11" height="13" fill="none"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" viewBox="0 0 11 13">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M2 1a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1V1H2zM1 2a1 1 0 00-1 1v9a1 1 0 001 1h7a1 1 0 001-1V3a1 1 0 00-1-1H1zm0 10V3h7v9H1z"
                      fill="currentColor"></path>
                  </svg>
                  <span class="sr-only">Copy link</span>
                </button>
              </div>
            </details>
          </share-button>
          <script src="js/details-disclosure.html" defer="defer"></script>
          <script src="js/share.html" defer="defer"></script>
        </div>
        <div class="product-single__meta-info">
          <div class="meta-item">
            <label>SKU:</label>
            <span>{{ $product->SKU }}</span>
          </div>
          <div class="meta-item">
            <label>Categories:</label>
            <span>{{ $product->category->name }}</span>
          </div>
          <div class="meta-item">
            <label>Tags:</label>
            <span>NA</span>
          </div>
        </div>
      </div>
    </div>

    </div>
    <div class="product-single__details-tab">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
            href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Description</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore" id="tab-additional-info-tab" data-bs-toggle="tab"
            href="#tab-additional-info" role="tab" aria-controls="tab-additional-info" aria-selected="false">Additional
            Information</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
          aria-labelledby="tab-description-tab">
          <div class="product-single__description">
            {{ $product->description }}
          </div>
        </div>
        <div class="tab-pane fade" id="tab-additional-info" role="tabpanel" aria-labelledby="tab-additional-info-tab">
          <div class="product-single__addtional-info">
            <div class="item">
              <label class="h6">Weight</label>
              <span>1.25 kg</span>
            </div>
            <div class="item">
              <label class="h6">Dimensions</label>
              <span>90 x 60 x 90 cm</span>
            </div>
            <div class="item">
              <label class="h6">Size</label>
              <span>XS, S, M, L, XL</span>
            </div>
            <div class="item">
              <label class="h6">Color</label>
              <span>Black, Orange, White</span>
            </div>
            <div class="item">
              <label class="h6">Storage</label>
              <span>Relaxed fit shirt-style dress with a rugged</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Get all thumbnail images
      const thumbnailImages = document.querySelectorAll('.thumbnail-img');
      const selectedImagePreview = document.getElementById('selectedImagePreview');
      const selectedImageName = document.getElementById('selectedImageName');
      const selectedImageInput = document.getElementById('selectedImageInput');

      // Add click event to all thumbnail images
      thumbnailImages.forEach(function(img) {
        img.addEventListener('click', function() {
          // Remove selected class from all thumbnails
          thumbnailImages.forEach(function(thumb) {
            thumb.style.border = 'none';
            thumb.style.opacity = '1';
          });

          // Add selected styling to clicked thumbnail
          this.style.border = '3px solid #007bff';
          this.style.opacity = '0.8';

          // Update the selected image preview
          const selectedImage = this.getAttribute('data-image');
          selectedImagePreview.src = "{{ asset('uploads/products/thumbnails') }}/" + selectedImage;
          selectedImageName.textContent = selectedImage;
          selectedImageInput.value = selectedImage;

          // Update main image display (optional - you can remove this if you don't want the main image to change)
          const mainImage = document.querySelector('.product-single__image img');
          if (mainImage) {
            mainImage.src = "{{ asset('uploads/products') }}/" + selectedImage;
          }
        });
      });

      // Set initial selected state for the first image
      if (thumbnailImages.length > 0) {
        thumbnailImages[0].style.border = '3px solid #007bff';
        thumbnailImages[0].style.opacity = '0.8';
      }
    });
  </script>
  @endsection