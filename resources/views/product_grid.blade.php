@extends('layouts.app')

@section('content')
<div class="container order_adminGrid">
    <h3>Shopify Product</h3>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <!-- Form for adding a new product inside a card -->
                    <div class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Add Product</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('product_store') }}"  enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_price">Product Price</label>
                                        <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_desc">Product Description</label>
                                        <input type="text"  class="form-control" id="product_desc" name="product_desc" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_type">Product Type</label>
                                        <input type="text" class="form-control" id="product_type" name="product_type" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Product</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End of form for adding a new product inside a card -->

                    <!-- Existing list view with cards -->
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product['title'] }}</h5>
                                    <p class="card-text">Description: {{ $product['body_html'] }}</p>
                                    <p class="card-text">Vendor: {{ $product['vendor'] }}</p>
                                    <p class="card-text">Product Type: {{ $product['product_type'] }}</p>
                                    <p class="card-text">Tags: {{ $product['tags'] }}</p>
                                    <p class="card-text">Qty: {{ $product['variants'][0]['inventory_quantity'] }}</p>
                                    <?php
                                        if(!empty($product['image']['src'])){
                                            $image_src = $product['image']['src'];
                                        }
                                    ?>
                                    <img height="80px" width="80px" class="card-img-top" src="{{ $image_src }}" alt="Image">
                                    <p class="card-text"><small class="text-muted">Status: {{ $product['status'] }}</small></p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">{{ $product['created_at'] }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- End of existing list view with cards -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
