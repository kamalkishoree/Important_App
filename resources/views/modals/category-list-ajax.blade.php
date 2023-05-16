 @foreach($category as $cat)
<li>
	<div class="form-check">
		<input type="checkbox" class="form-check-input"
			onchange="getCategory({{$cat->id}})" name="category-check"
			id="category-check_{{$cat->id}}"> <label class="label-check"
			for="category-check_{{$cat->id}}">{{ !empty($cat->translation) ? $cat->translation->name:$cat->slug}}</label>
	</div>
</li>


@endforeach
