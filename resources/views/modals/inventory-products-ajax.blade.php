<option value="">Select Products</option>
@foreach ($products as $product)
<option value="{{$product->id}}">{{$product->sku}}</option>
@endforeach