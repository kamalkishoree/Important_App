 @foreach ($products as $product)
<li>
	<div class="prod">
		<div class="prod-pic">
			<img src="images/pngwing.png" alt="image">
		</div>
		{{$product->title}}
	</div>
	<div class="form-check">
		<input type="checkbox" class="form-check-input" onclick="getProductName({{$product->id}})"
			id="product_id_{{$product->id}}"  name="product-check" value="{{$product->id}}"> <label></label>
	</div>
</li>
@endforeach
