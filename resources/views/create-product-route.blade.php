@extends('layouts.vertical', ['title' => 'Customize']) @section('css')

@endsection @section('content')
<style>
.Categories-list li {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 12px 22px 12px 12px;
	border-bottom: 1px solid #E2E2E2;
	position: relative;
}

.Categories-list li:last-child {
	border-bottom: 0;
}

.warehouses input.search {
	border-radius: 4px;
	font-weight: 400;
	padding-left: 20px;
	padding-right: 20px;
}

.warehouses .prod-search {
	background: #FFFFFF;
	border: 1px solid #ced4da;
	border-radius: 5px;
	padding: 10px 15px;
}

.warehouses .search-bar {
	position: relative;
	max-width: 265px;
	margin: 0 auto 15px;
}

.warehouses .search-bar button.btn {
	position: absolute;
	top: 0;
	right: 0;
	background: transparent;
	border: 0;
	padding: 4px 12px;
	height: 100%;
}

.prod-list li {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 12px 22px 12px 12px;
	border-bottom: 1px solid #E2E2E2;
	position: relative;
}

.prod-list li:last-child {
	border-bottom: 0;
}

.warehouses .prod-list li::after {
	content: " ";
	position: absolute;
	background: #dee2e6;
	width: 1px;
	height: 38px;
	top: 50%;
	right: 70px;
	transform: translateY(-50%);
}

.warehouses .prod-list ul {
	padding: 0;
	margin: 0;
	list-style: none;
	max-height: 500px;
	overflow: hidden auto;
}

.warehouses .prod-details ul, .Categories-list ul {
	padding: 0;
	margin: 0;
	list-style: none;
	max-height: 680px;
	overflow: hidden auto;
}

.warehouses .prod {
	display: flex;
	align-items: center;
	gap: 20px;
	font-style: normal;
	font-weight: 400;
	font-size: 16;
	text-align: center;
}

.warehouses .prod-pic {
	background: #E9E7FA;
	border-radius: 6.11031px;
	width: 38px;
	height: 38px;
	text-align: center;
	padding: 5px 4px;
}

.prod-pic img {
	width: 100%;
}

.warehouses .prod-list .form-check {
	position: relative;
	padding-left: 0;
}

.warehouses .prod-list .form-check input.form-check-input {
	border: 1px solid #6658DD;
	width: 18px;
	height: 18px;
	opacity: 0;
	position: absolute;
	z-index: 99;
	top: 0;
	left: 20px;
}

.warehouses .prod-list .form-check input[type=checkbox]:checked+label:after
	{
	background: #6658DD;
}

.warehouses .prod-list .form-check input[type=checkbox]:checked+label:before
	{
	opacity: 1;
	z-index: 9;
}

.warehouses .prod-list .form-check label {
	position: relative;
	width: 18px;
	height: 18px;
	margin: 0 !important;
}

.warehouses .prod-list .form-check label:after {
	content: " ";
	border: 1px solid #6658DD;
	width: 18px;
	height: 18px;
	position: absolute;
	left: 0;
	top: 5px;
	border-radius: 3px;
	background: transparent;
}

.warehouses .prod-list .form-check label::before {
	content: " ";
	width: 6px;
	height: 10px;
	top: 11px;
	left: 3px;
	position: absolute;
	transform: rotate(45deg) translateY(-50%);
	border-bottom: 2px solid #fff;
	border-right: 2px solid #fff;
	opacity: 0;
	background: transparent !important;
}

.warehouses .prod-list ul::-webkit-scrollbar {
	width: 3px;
}

.warehouses .prod-list ul::-webkit-scrollbar-track {
	background: #D9D9D9;
}

.warehouses .prod-list ul::-webkit-scrollbar-thumb {
	background: #6658DD;
}

.prod-details input.form-control.input-number {
	max-width: 25px;
	padding: 0;
	text-align: center;
	border: 0;
}

.prod-details .input-group {
	justify-content: center;
	gap: 12px;
	align-items: center;
	position: relative;
}

.prod-details button.btn-number {
	padding: 0;
	background: transparent;
	border: 0;
	cursor: pointer;
}

.prod-details li {
	padding: 12px;
}

.warehouses .prod-details  li::after {
	display: none;
}

.prod-list.prod-details li {
	display: block;
	border: 0;
}

.prod-list.prod-details .product {
	border-bottom: 1px solid #E2E2E2;
	padding: 18px 10px;
}

.prod-list.prod-details .product .row {
	align-items: center;
}

.prod-list.prod-details .product:last-child {
	border: 0;
}

.stock p {
	margin: 0;
	text-align: center;
}

.stock {
	position: relative;
	border-left: 1px solid #E2E2E2;
	border-right: 1px solid #E2E2E2;
}

p.title {
	position: absolute;
	top: -40px;
	left: 50%;
	transform: translateX(-50%);
}

.product.produ p.title {
	display: block;
}

.product p.title {
	display: none;
}

.select-bar, .bars {
	display: flex;
	gap: 10px;
}

.warehouses .bars .search-bar {
	position: relative;
	max-width: auto;
	margin: 0;
}

.select-bar button {
	background: transparent;
	border: 0;
	display: flex;
	align-items: center;
	padding: 0;
	gap: 10px;
	width: 267px;
	font-size: 12px;
}

.select-bar select {
	border-radius: 33px;
}

@media only screen and (max-width: 575px) {
	.prod-list.prod-details .prod {
		margin-bottom: 20px;
	}
	p.title {
		position: relative;
		top: 0;
		left: 0;
		transform: translateX(0);
		margin: 0 0 0 0;
	}
	.prod-details .input-group {
		width: 100%;
	}
	.stock {
		position: relative;
		display: flex;
		align-items: center;
		gap: 20px;
		border-left: 0;
	}
	.product p.title {
		display: block;
	}
}
</style>

</head>

<div class="container-fluid">
	<div class="warehouses">
		<form>
			<div class="row mt-5">
				<div class="col-xl-3">
					<label>Categories</label>
					<div class="prod-search">
						<div class="search-bar">
							<input class="form-control search" type="search"
								placeholder="Search" aria-label="Search" id="category-search">
							<button class="btn" type="submit">
								<svg width="10" height="10" viewBox="0 0 10 10" fill="none"
									xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
										clip-rule="evenodd"
										d="M4.54083 0.108887C4.54083 0.108887 5.38981 0.108887 6.1657 0.43706C6.1657 0.43706 6.91485 0.753921 7.49239 1.33146C7.49239 1.33146 8.06993 1.909 8.38679 2.65815C8.38679 2.65815 8.71496 3.43404 8.71496 4.28302C8.71496 4.28302 8.71496 5.132 8.38679 5.90789C8.38679 5.90789 8.06993 6.65704 7.49239 7.23458C7.49239 7.23458 6.91485 7.81212 6.1657 8.12898C6.1657 8.12898 5.38981 8.45715 4.54083 8.45715C4.54083 8.45715 3.69185 8.45715 2.91596 8.12898C2.91596 8.12898 2.16681 7.81212 1.58927 7.23458C1.58927 7.23458 1.01173 6.65704 0.694872 5.90789C0.694872 5.90789 0.366699 5.132 0.366699 4.28302C0.366699 4.28302 0.366699 3.43403 0.694872 2.65815C0.694872 2.65815 1.01173 1.909 1.58927 1.33146C1.58927 1.33146 2.16681 0.753922 2.91596 0.43706C2.91596 0.43706 3.69185 0.108887 4.54083 0.108887ZM4.54083 0.834823C4.54083 0.834823 3.83906 0.834823 3.19875 1.10565C3.19875 1.10565 2.58001 1.36736 2.10259 1.84478C2.10259 1.84478 1.62517 2.3222 1.36346 2.94094C1.36346 2.94094 1.09263 3.58124 1.09264 4.28302C1.09264 4.28302 1.09264 4.98479 1.36346 5.6251C1.36346 5.6251 1.62517 6.24384 2.10259 6.72126C2.10259 6.72126 2.58001 7.19868 3.19875 7.46039C3.19875 7.46039 3.83906 7.73121 4.54083 7.73121C4.54083 7.73121 5.2426 7.73121 5.88291 7.46039C5.88291 7.46039 6.50165 7.19868 6.97907 6.72126C6.97907 6.72126 7.4565 6.24384 7.7182 5.6251C7.7182 5.6251 7.98903 4.98479 7.98903 4.28302C7.98903 4.28302 7.98903 3.58125 7.7182 2.94094C7.7182 2.94094 7.4565 2.3222 6.97907 1.84478C6.97907 1.84478 6.50165 1.36735 5.88291 1.10565C5.88291 1.10565 5.24261 0.834823 4.54083 0.834823Z"
										fill="#4F4F4F" />
                                            <path
										d="M9.18407 9.43957C9.25214 9.50764 9.34465 9.54607 9.44092 9.54607C9.53718 9.54607 9.62951 9.50783 9.69757 9.43976C9.76564 9.37169 9.80389 9.27937 9.80389 9.1831C9.80389 9.08684 9.76564 8.99452 9.69757 8.92645L7.49259 6.72146C7.42447 6.65335 7.33215 6.61511 7.23589 6.61511C7.13962 6.61511 7.04714 6.65319 6.97907 6.72126C6.911 6.78933 6.87292 6.88181 6.87292 6.97807C6.87292 7.07434 6.91116 7.16666 6.97923 7.23473L9.18407 9.43957Z"
										fill="#4F4F4F" />
                                        </svg>
							</button>
						</div>
						<div class="Categories-list prod-list">
							<ul class="cat-list">

								@foreach($category as $cat)
								<li>
									<div class="prod">{{ $cat->slug}}</div>
									<div class="form-check">
										<input type="checkbox" class="form-check-input"
											onchange="getCategory({{$cat->id}})" name="category-check"
											id="category-check_{{$cat->id}}"> <label class="label-check" for="category-check_{{$cat->id}}"></label>
									</div>
								</li> @endforeach

							</ul>
						</div>
					</div>
				</div>
				<div class="col-xl-3">
					<label>Select Products</label>
					<div class="prod-search">
						<div class="search-bar">
							<input class="form-control search" type="search"
								id="product-search" placeholder="Search" aria-label="Search">
							<button class="btn" type="submit">
								<svg width="10" height="10" viewBox="0 0 10 10" fill="none"
									xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
										clip-rule="evenodd"
										d="M4.54083 0.108887C4.54083 0.108887 5.38981 0.108887 6.1657 0.43706C6.1657 0.43706 6.91485 0.753921 7.49239 1.33146C7.49239 1.33146 8.06993 1.909 8.38679 2.65815C8.38679 2.65815 8.71496 3.43404 8.71496 4.28302C8.71496 4.28302 8.71496 5.132 8.38679 5.90789C8.38679 5.90789 8.06993 6.65704 7.49239 7.23458C7.49239 7.23458 6.91485 7.81212 6.1657 8.12898C6.1657 8.12898 5.38981 8.45715 4.54083 8.45715C4.54083 8.45715 3.69185 8.45715 2.91596 8.12898C2.91596 8.12898 2.16681 7.81212 1.58927 7.23458C1.58927 7.23458 1.01173 6.65704 0.694872 5.90789C0.694872 5.90789 0.366699 5.132 0.366699 4.28302C0.366699 4.28302 0.366699 3.43403 0.694872 2.65815C0.694872 2.65815 1.01173 1.909 1.58927 1.33146C1.58927 1.33146 2.16681 0.753922 2.91596 0.43706C2.91596 0.43706 3.69185 0.108887 4.54083 0.108887ZM4.54083 0.834823C4.54083 0.834823 3.83906 0.834823 3.19875 1.10565C3.19875 1.10565 2.58001 1.36736 2.10259 1.84478C2.10259 1.84478 1.62517 2.3222 1.36346 2.94094C1.36346 2.94094 1.09263 3.58124 1.09264 4.28302C1.09264 4.28302 1.09264 4.98479 1.36346 5.6251C1.36346 5.6251 1.62517 6.24384 2.10259 6.72126C2.10259 6.72126 2.58001 7.19868 3.19875 7.46039C3.19875 7.46039 3.83906 7.73121 4.54083 7.73121C4.54083 7.73121 5.2426 7.73121 5.88291 7.46039C5.88291 7.46039 6.50165 7.19868 6.97907 6.72126C6.97907 6.72126 7.4565 6.24384 7.7182 5.6251C7.7182 5.6251 7.98903 4.98479 7.98903 4.28302C7.98903 4.28302 7.98903 3.58125 7.7182 2.94094C7.7182 2.94094 7.4565 2.3222 6.97907 1.84478C6.97907 1.84478 6.50165 1.36735 5.88291 1.10565C5.88291 1.10565 5.24261 0.834823 4.54083 0.834823Z"
										fill="#4F4F4F" />
                                            <path
										d="M9.18407 9.43957C9.25214 9.50764 9.34465 9.54607 9.44092 9.54607C9.53718 9.54607 9.62951 9.50783 9.69757 9.43976C9.76564 9.37169 9.80389 9.27937 9.80389 9.1831C9.80389 9.08684 9.76564 8.99452 9.69757 8.92645L7.49259 6.72146C7.42447 6.65335 7.33215 6.61511 7.23589 6.61511C7.13962 6.61511 7.04714 6.65319 6.97907 6.72126C6.911 6.78933 6.87292 6.88181 6.87292 6.97807C6.87292 7.07434 6.91116 7.16666 6.97923 7.23473L9.18407 9.43957Z"
										fill="#4F4F4F" />
                                        </svg>
							</button>
						</div>
						<div class="prod-list">
							<ul class="inventory-products" data-id="">


							</ul>
						</div>
					</div>
				</div>
				<div class="col-xl-6">
					<label>Products Details</label>
					<div class="prod-search">
						<div class="row">
							<div class="col-sm-5 px-1">
								<div class="search-bar">
									<input class="form-control search" type="search" id="warehouse-search"
										placeholder="Search" aria-label="Search">
									<button class="btn" type="submit">
										<svg width="10" height="10" viewBox="0 0 10 10" fill="none"
											xmlns="http://www.w3.org/2000/svg">
                                                    <path
												fill-rule="evenodd" clip-rule="evenodd"
												d="M4.54083 0.108887C4.54083 0.108887 5.38981 0.108887 6.1657 0.43706C6.1657 0.43706 6.91485 0.753921 7.49239 1.33146C7.49239 1.33146 8.06993 1.909 8.38679 2.65815C8.38679 2.65815 8.71496 3.43404 8.71496 4.28302C8.71496 4.28302 8.71496 5.132 8.38679 5.90789C8.38679 5.90789 8.06993 6.65704 7.49239 7.23458C7.49239 7.23458 6.91485 7.81212 6.1657 8.12898C6.1657 8.12898 5.38981 8.45715 4.54083 8.45715C4.54083 8.45715 3.69185 8.45715 2.91596 8.12898C2.91596 8.12898 2.16681 7.81212 1.58927 7.23458C1.58927 7.23458 1.01173 6.65704 0.694872 5.90789C0.694872 5.90789 0.366699 5.132 0.366699 4.28302C0.366699 4.28302 0.366699 3.43403 0.694872 2.65815C0.694872 2.65815 1.01173 1.909 1.58927 1.33146C1.58927 1.33146 2.16681 0.753922 2.91596 0.43706C2.91596 0.43706 3.69185 0.108887 4.54083 0.108887ZM4.54083 0.834823C4.54083 0.834823 3.83906 0.834823 3.19875 1.10565C3.19875 1.10565 2.58001 1.36736 2.10259 1.84478C2.10259 1.84478 1.62517 2.3222 1.36346 2.94094C1.36346 2.94094 1.09263 3.58124 1.09264 4.28302C1.09264 4.28302 1.09264 4.98479 1.36346 5.6251C1.36346 5.6251 1.62517 6.24384 2.10259 6.72126C2.10259 6.72126 2.58001 7.19868 3.19875 7.46039C3.19875 7.46039 3.83906 7.73121 4.54083 7.73121C4.54083 7.73121 5.2426 7.73121 5.88291 7.46039C5.88291 7.46039 6.50165 7.19868 6.97907 6.72126C6.97907 6.72126 7.4565 6.24384 7.7182 5.6251C7.7182 5.6251 7.98903 4.98479 7.98903 4.28302C7.98903 4.28302 7.98903 3.58125 7.7182 2.94094C7.7182 2.94094 7.4565 2.3222 6.97907 1.84478C6.97907 1.84478 6.50165 1.36735 5.88291 1.10565C5.88291 1.10565 5.24261 0.834823 4.54083 0.834823Z"
												fill="#4F4F4F" />
                                                    <path
												d="M9.18407 9.43957C9.25214 9.50764 9.34465 9.54607 9.44092 9.54607C9.53718 9.54607 9.62951 9.50783 9.69757 9.43976C9.76564 9.37169 9.80389 9.27937 9.80389 9.1831C9.80389 9.08684 9.76564 8.99452 9.69757 8.92645L7.49259 6.72146C7.42447 6.65335 7.33215 6.61511 7.23589 6.61511C7.13962 6.61511 7.04714 6.65319 6.97907 6.72126C6.911 6.78933 6.87292 6.88181 6.87292 6.97807C6.87292 7.07434 6.91116 7.16666 6.97923 7.23473L9.18407 9.43957Z"
												fill="#4F4F4F" />
                                                </svg>
									</button>
								</div>
							</div>
							<div class="col-sm-7 px-1">
								<div class="select-bar">
									<select class="form-control">
										<option>Product</option>
										<option>Product</option>
										<option>Product</option>
										<option>Product</option>
									</select> <select class="form-control">
										<option>Distance</option>
										<option>Distance</option>
										<option>Distance</option>
										<option>Distance</option>
									</select>
									<button type="button">
										Sort By
										<svg width="18" height="13" viewBox="0 0 18 13" fill="none"
											xmlns="http://www.w3.org/2000/svg">
                                                    <path
												d="M5.18845 1.06692C4.94333 0.821804 4.54592 0.821804 4.30081 1.06692L0.306406 5.06132C0.0612893 5.30644 0.0612893 5.70385 0.306406 5.94897C0.551522 6.19408 0.948934 6.19408 1.19405 5.94897L4.74463 2.39839L8.29521 5.94897C8.54032 6.19408 8.93774 6.19408 9.18285 5.94897C9.42797 5.70385 9.42797 5.30644 9.18285 5.06132L5.18845 1.06692ZM4.11697 11.1139C4.11697 11.4606 4.39798 11.7416 4.74463 11.7416C5.09128 11.7416 5.37229 11.4606 5.37229 11.1139H4.11697ZM4.11697 1.51074L4.11697 11.1139H5.37229L5.37229 1.51074H4.11697Z"
												fill="#4838CD" />
                                                    <path
												d="M12.4605 12.1855C12.7056 12.4306 13.103 12.4306 13.3481 12.1855L17.3425 8.19112C17.5876 7.946 17.5876 7.54859 17.3425 7.30348C17.0974 7.05836 16.7 7.05836 16.4549 7.30348L12.9043 10.8541L9.35372 7.30348C9.1086 7.05836 8.71119 7.05836 8.46607 7.30348C8.22096 7.54859 8.22096 7.946 8.46607 8.19112L12.4605 12.1855ZM13.532 2.13851C13.532 1.79186 13.2509 1.51085 12.9043 1.51085C12.5577 1.51085 12.2766 1.79186 12.2766 2.13851H13.532ZM13.532 11.7417L13.532 2.13851H12.2766L12.2766 11.7417H13.532Z"
												fill="#4838CD" />
                                                </svg>
									</button>
								</div>
							</div>
						</div>
						<div class="prod-list prod-details">
							<ul class="product-list">

							</ul>
						</div>
					</div>
				</div>
			</div>
			<a href="javascript: void(0);" id="create-subtask"
				class="btn btn-blue waves-effect waves-light  mt-2"> <span>Create
					Sub Task</span>
			</a>
		</form>
	</div>
</div>

@endsection

<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"
	type="text/javascript"></script>
<script>
 $(function() {
        $('.select2-multiple').select2();
        
     });
    
 
   
         
         $(document).on('keyup','#product-search',function()
         {
          
           var cat_id = $(".inventory-products").attr('data-id');
           
           var search = $('#product-search').val();
            $.ajax({
                url: "/get-inventory-products",
                type: "get",
                datatype: "html",
                data: {
                    cat_id: cat_id,
                    title:search
                },
                success: (data) => {
                    $(".inventory-products").empty().html(data);
                },
                error: () => {
                    $(".inventory-products").empty().html(data);
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
         
         
         });
         
            $(document).on('keyup','#warehouse-search',function()
         {
           
           var search = $('#warehouse-search').val();
            $.ajax({
                url: "/get-warehouse-data",
                type: "get",
                datatype: "html",
                data: {
                    product_id: product_ids,
                    title:search
                },
                success: (data) => {
                $('.product-list').empty().append(data);
                },
                error: () => {
//                     $(".inventory-products").empty().html(data);
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
         
         
         });
        var arr = [];
        var product_ids = [];
        var vendor_ids = [];
        var category_id = [];
        
       function  getCategory(id){	
        
            var cat_id = id;
            
            $.ajax({
                url: "/get-inventory-products",
                type: "get",
                datatype: "html",
                data: {
                    cat_id: cat_id
                },
                success: (data) => {
                    $(".inventory-products").empty().html(data);
                    $('.inventory_products').attr('data-id',cat_id);
                    
             
                   var th = $('#category-check_'+id), name = th.attr('name'); 
                   if(th.is(':checked')){
                   $(':checkbox[name="'  + name + '"]').not(th).prop('checked',false);   
                     }
                    if (!(product_ids.length === 0))
                    {
                          $.each(product_ids,function(i,e)
                          {
                         	 $('#product_id_'+e).prop('checked',true);
                          
                          });
                    }
                    $('.inventory-products').attr('data-id',id);
                },
                error: () => {
                    $(".inventory-products").empty().html(data);
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
        };
        
        
           $(document).on('keyup','#category-search',function()
         {
          
           var search = $('#category-search').val();
            $.ajax({
                url: "/get-category-list",
                type: "get",
                datatype: "html",
                data: {
                    search:search
                },
                success: (data) => {
                    $(".cat-list").empty().html(data);
                    
                   
                },
                error: () => {
                    $(".cat-list").empty().html("<li>No Result Found</li>");
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
         
         
         });
         
         
          function minus(id,quantity){
				var $input = $('#range_input_'+id);
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			}	
			
			  function plus(id,quantity){
				var $input= $('#range_input_'+id);
				
				if($input.val() < quantity){
				$input.val(parseInt($input.val()) + 1);
				
				$input.change();
				}
				
				return false;
			}
    
   
     function getProductName(id){
        
           $.ajax({
                url: "/get-product-name",
                type: "get",
                datatype: "html",
                data: {
                    id: id
                },
                success: (data) => {
                   
                  if(product_ids.includes(id)){
                  product_ids=  product_ids.filter(e => e !== id);

 
                    }else{
                    product_ids.push(id);
                   }
                   
                   console.log(product_ids);
                   getProductWarehouses(product_ids)
                },
                error: () => {
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
        }
    
-
        
        $("#get-warehouse").click(function()
        {
        
           getProductWarehouses(product_ids)
        
        });

        function getProductWarehouses(data) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/get-selected-warehouses",
                type: "post",
                datatype: "json",
                data: {
                    data: data
                },
                success: (data) => {

                        $(".product-list").empty().append(data);

                },
                error: () => {
                    // $("#selected_inventory_products").append("<option value='" + cat_id + "' selected>" + data + "</option>");
                },
                complete: function(data) {
                    // hideLoader();
                }
            });

        }

        var warehouse_id = [];
        var vendor_id = [];
        var item_count = [];
        var list= [];
        var selected_products = [];
        $(document).on('click','#create-subtask',function(e){



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
             
      
             
          $("input:checkbox[name=vendor_product]:checked").each(function(){
                    selected_products.push({"product_variant_id":parseInt($(this).val()),'quantity':$('#range_input_'+$(this).val()).val(),'vendor_id':$('#vendor_id_'+$(this).val()).val()});
                });
     	
     	
         
            autoWrap.indexOf('addHeader1') === -1 ? autoWrap.push('addHeader1') : '';
            e.preventDefault();

            $.ajax({
                type: "post",
                url: "{{ route('getWarehouseProducts')}}",
                data: {
                    'vendors' : selected_products,
                },
                dataType: 'json',
                success: function(data) {
                    $('.submitTaskHeaderLoader').css('display', 'none');
                    $('#submitTaskHeaderText').text('Submit');
                    $('.submitTaskHeader').removeClass("inactiveLink");

                    $('#task-modal-header #addCardBox').html(data.html);

                    $('#task-modal-header').find('.selectizeInput').selectize();


                    $('.dropify').dropify();
                    $(".newcustomer").hide();
                    $(".searchshow").show();
                    $(".append").show();
                    $('.copyin').remove();

                    $(".addspan").hide();
                    $(".tagspan").hide();
                    $(".tagspan2").hide();
                    $(".searchspan").hide();
                    $(".datenow").hide();

                    $(".pickup-barcode-error").hide();
                    $(".drop-barcode-error").hide();
                    $(".appointment-barcode-error").hide();

                    $('.appoint').hide();

                    loadMapHeader(autoWrap);
                    searchRes();
                    $('#task-modal-header').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    phoneInput();
                    runPicker();

                    $('#task-modal-header .edit-icon-float-right').on('click', function() {
                        $('#task-modal-header .meta_data_task_div').toggle();
                        if ($(this).find('i').hasClass('mdi mdi-chevron-down')) {
                            $(this).find('i').removeClass('mdi mdi-chevron-down');
                            $(this).find('i').addClass('mdi mdi-chevron-up');
                        } else {
                            $(this).find('i').removeClass('mdi mdi-chevron-up');
                            $(this).find('i').addClass('mdi mdi-chevron-down');
                        }
                    });


                },
                error: function(data) {}
            });



        });
        
</script>
