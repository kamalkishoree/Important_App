<option value="">Select Products</option>
@foreach ($product as $item)
    <option value="{{$item->id}}" >{{$item->title}}</option>
@endforeach