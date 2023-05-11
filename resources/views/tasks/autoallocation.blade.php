@extends('layouts.vertical', ['title' => __('Auto Allocation')])
<style> 

form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 50px;
    font-size: 18px;
    background-color: #fff;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 30px;
    border: 2px solid #ccc;
}

label {
    margin-top: 20px;
}

input[type="text"] {
    width: 300px;
    height: 40px;
    padding: 5px;
    margin-top: 10px;
    font-size: 18px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

button[type="submit"] {
    width: 200px;
    height: 40px;
    margin-top: 20px;
    font-size: 18px;
    background-color: #2196F3;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

button[type="submit"]:hover {
    background-color: #0C7CBD;
}

</style>
@section('content')


<form method="POST" action="{{ route('dispatcher-autoallocation') }}">
    @csrf
    <label for="from_location">Starting Location:</label>
    <input type="text" name="from_location" id="from_location" placeholder="Enter a location" required>
    <input type="hidden" name="lat1" id="lat1" >
    <input type="hidden" name="long1" id="long1" >
    <br>
    <label for="to_location">Product Warehouse Location:</label>
    <select class="form-control" data-toggle="select2" name="to_location" id="to_location_id" style="width:300px;">
                            @php
                             $warehouses = App\Model\Warehouse::all();
                            @endphp
                            <option>Select</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{$warehouse->id}}">{{ $warehouse->name}}</option>
                            
                            @endforeach
                         
                                    </select>

    <br>
    <button type="submit">Calculate Distance</button>
</form>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJgoJnT57PuPJDSTrLIVOa2-cq4FO3p0k&libraries=places"></script>
<script>
  // Initialize the Google Maps autocomplete feature for the from_location and to_location input fields
  var from_location_input = document.getElementById('from_location');
  var to_location_input = document.getElementById('to_location');

  var autocomplete_from_location = new google.maps.places.Autocomplete(from_location_input);

  // Set the latitude and longitude values of the input fields when the user selects a location from the autocomplete dropdown
  google.maps.event.addListener(autocomplete_from_location, 'place_changed', function () {
    var place = autocomplete_from_location.getPlace();
    document.getElementById('lat1').value = place.geometry.location.lat();
    document.getElementById('long1').value = place.geometry.location.lng();
  });


</script>

@endsection
