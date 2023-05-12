
<li id="new_product_{{$product->id}}">
	<div class="prod">
		<div class="prod-pic">
			<img src="images/no-img.jpg" alt="image">
		</div>
		{{ $product->title}}
	</div>
	<div class="input-group">
		<span class="input-group-btn">
			<button type="button" class="btn-number" data-type="minus"
				data-field="quant[2]">
				<svg width="15" height="15" viewBox="0 0 15 15" fill="none"
					xmlns="http://www.w3.org/2000/svg">
                                                            <circle
						cx="7.6099" cy="7.73002" r="6.76852" stroke="#4838CD"
						stroke-width="0.966932" />
                                                            <path
						d="M4.2251 7.72998C5.69358 7.72998 7.98539 7.72998 7.98539 7.72998H11.3697"
						stroke="#4838CD" stroke-width="1.12809" stroke-linecap="round" />
                                                        </svg>
			</button>
		</span> <input type="text" name="quant[2]"
			class="form-control input-number" value="0" min="1" max="100"> <span
			class="input-group-btn">
			<button type="button" class="btn-number" data-type="plus"
				data-field="quant[2]">
				<svg width="15" height="15" viewBox="0 0 15 15" fill="none"
					xmlns="http://www.w3.org/2000/svg">
                                                            <circle
						cx="7.39408" cy="7.73002" r="6.76852" stroke="#4838CD"
						stroke-width="0.966932" />
                                                            <path
						d="M7.77006 4.3457V11.1142M4.00977 7.72996H7.77006H11.1543"
						stroke="#4838CD" stroke-width="1.12809" stroke-linecap="round" />
                                                        </svg>
			</button>
		</span>
	</div>
</li>