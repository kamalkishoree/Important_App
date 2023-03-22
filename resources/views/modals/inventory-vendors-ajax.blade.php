<?php
use App\Model\Product;
?>
@foreach ($warehouses as $warehouse)

<?php

$products = Product::where([
    'vendor_id' => $warehouse->id
])->whereIn('id', $product_ids)->get();
?>


<div class="form__inputs__multi-checkbox-container">
	<ul class="multi-checkbox-container__list list--parent">
		<li class="multi-checkbox-container__list__item item--event">
			<div class="form__inputs__checkbox-container">
				<label class="checkbox-container__label"
					for="warehouse_name{{ $warehouse->id}}">{{ $warehouse->slug}}</label>
		<input type="hidden" id="warehouse_id{{$warehouse->id}}"
					name="parent_warehouse" value="{{$warehouse->id}}"> 
		<input type="hidden"
					id="item_count{{$warehouse->id}}" name="item_count"
					value="{{count($products)}}">
			</div>

			<ul class="multi-checkbox-container__list list--children">
				@foreach ($products as $product)
				<li class="multi-checkbox-container__list__item item--video">
					<div class="form__inputs__checkbox-container">
						<input type="checkbox"
							class="checkbox-container__input input--child"
							id="product_{{$product->id}}" name="product_id" data-id="{{$warehouse->id}}"
							value="{{$product->id}}"> <label
							class="checkbox-container__label" for="product_{{$product->id}}">{{$product->sku}}</label>

						<input type="hidden" id="product_id{{$product->id}}"
							name="productId[]" value="{{$product->id}}">
					</div>
				</li> @endforeach

			</ul>
		</li>
	</ul>
</div>
@endforeach
