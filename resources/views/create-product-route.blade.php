@extends('layouts.vertical', ['title' => 'Customize']) @section('css')

@endsection @section('content')

 @include('tasks.create-product')

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
									<div class="form-check">
										<input type="checkbox" class="form-check-input"
											onchange="getCategory({{$cat->id}})" name="category-check"
											id="category-check_{{$cat->id}}"> <label class="label-check" for="category-check_{{$cat->id}}">{{ $cat->translation->name}}</label>
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
							<ul class="inventory-products" data-id="" >

    							<li> No Results Found</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-xl-6">
					<label>Products Details</label>
					<div class="prod-search">
						<div class="row">
							<div class="col-sm-8 px-1">
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
							<div class="col-sm-4 px-1">
								<div class="select-bar">
									<select class="form-control product-geo" >
									<option>Select Products</option>
									</select>
									<button type="button" class="sort-by d-none" >
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
                              
                             <li> No Results Found</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<a href="javascript: void(0);" id="create-subtask"
				class="btn btn-blue waves-effect waves-light  mt-2" readonly="readonly"> <span>Create
					Sub Task</span>
			</a>
		</form>
	</div>
</div>

@endsection
 @include('tasks.productpagescript')

