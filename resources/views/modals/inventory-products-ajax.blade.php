<?php if(count($products)  > 0) { ?>
  @foreach ($products as $product)
 
<li>
	<div class="prod">
		<div class="prod-pic">
		 @if (isset($product->media[0]) && isset($product->media[0]->image))
                <img alt="{{ $product->id }}" class="rounded-circle" src="{{ $product->media[0]->image->path['proxy_url'] . '30/30' . $product->media[0]->image->path['image_path'] }}">
            @else
          <img src="{{ asset('assets/images/bg-material.png')}}" alt="image">
           @endif
		</div>
   @php
     if(!empty($product->translation_one)){
		
		$title = $product->translation_one->title;
		}else{
		$title = $product->title;
		}
		@endphp
		
		
		@if(empty($title))
           {{$product->sku}}
        @else
        {{$title}}
        
        @endif
		
	</div>
	</div>
	<div class="form-check">
		<input type="checkbox" class="form-check-input"
			onclick="getProductName({{$product->id}})"
			id="product_id_{{$product->id}}" name="product-check"
			value="{{$product->id}}"> <label></label>
	</div>
</li>
@endforeach 
<?php } else {?>

<li>
	<div class="prod">
		<div class=""></div>
		No Results Found
	</div>
</li>
<?php  }?>