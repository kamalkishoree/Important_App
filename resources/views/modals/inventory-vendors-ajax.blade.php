<?php
use App\Model\Product;
?>
 @foreach ($warehouses as $warehouse)
<div>

<?php

$products = Product::where([
    'vendor_id' => $warehouse->id
])->whereIn('id', $product_ids)->get();
?>
	<h3>{{$warehouse->slug}}</h3>
	@foreach ($products as $product) <input type="checkbox"
		name="product_id[]" id="product_{{$product->id}}"> <label
		for="product_{{$product->id}}">{{ $product->sku}}</label> @endforeach


</div>
@endforeach
