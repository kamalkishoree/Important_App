@extends('layouts.vertical', ['title' => 'Customize']) @section('css')
@endsection @section('content')


<div class="container-fluid">

	<div class="row mt-5">

		<div class="col-md-12">
			<div class="row">
				@csrf
				<div class="col-md-6">
					<div class="form-group mb-1 select_warehouse-field">
					    <h2>Category</h2>
						<select class="form-control inventory_category_id"
							name="inventory-category_id" id="inventory_category_id">
							<option value="">Select Category</option>
							 @foreach ($category as $cat)
							<option value="{{$cat->id}}">{{$cat->slug}}</option> @endforeach
						</select>
					</div>


				</div>
				<div class="col-md-6">

					<div class="form-group mb-1 select_warehouse-field">
					<h2>Product</h2>
						<select class="form-control inventory-products"
							name="inventory_products[]" id="inventory_product">
							<option value="">Select Products</option>

						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 warehouse-data">
		
		<div class="row">
		
		<div class="col-md-8">
		<div class="warehouse-fields mt-2">
				<div class="form-group mb-1 select_inventory-field">
				<h3> Selected Products</h3>
					<select class="form-control select2-multiple selected-products"
						multiple="multiple" name="selected_inventory_products[]"
						id="selected_inventory_products">
						<option value="" disabled>Select Products</option>

					</select>


				</div>
			</div>
		</div>
		<div class="col-md-4">
		<button type="button" id="get-warehouse" class="btn btn-primary	mt-4" >Choose Warehouses</button>
		</div>
		</div>
			
		</div>
		<div class="col-md-12 ">
			<div class="warehouse-fields mt-2 inventory_vendor">
			<h4>Choose Warehouses</h4>
			
				<div class="form-group mb-1 inventory_warehouse"></div>
				<button type="button" id="create_subtask"
					class="btn float-right btn-primary ">Create Subtasks</button>

			</div>
		</div>

	</div>
</div>

@endsection


