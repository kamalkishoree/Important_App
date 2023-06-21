
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"
	type="text/javascript"></script>
<script>
 $(function() {
        $('.select2-multiple').select2();
        
     });
    
    $(document).ready(function(){
    
      $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
    });
    
         var product_variant =[];
          var arr = [];
         var product_ids = [];
        var vendor_ids = [];
        var category_id = [];
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
                    
                    if (!(product_ids.length === 0))
                    {
                          $.each(product_ids,function(i,e)
                          {
                         	 $('#product_id_'+e).prop('checked',true);
                          
                          });
                    }
                },
                error: () => {
                 
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
                url: "/get-selected-warehouses",
                type: "post",
                datatype: "html",
                data: {
                    data: product_ids,
                    title:search
                },
                success: (data) => {
                $('.product-list').empty().append(data.html);
                
                  if (!(product_variant.length === 0))
                    {
                          $.each(product_variant,function(i,e)
                          {
                         	 $('#vendor_product_'+e).prop('checked',true);
                          
                          });
                    }
                },
                error: () => {
//                     $(".inventory-products").empty().html(data);
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
         
         
         });
     
        
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
                    
                                           category_id.length = 0;
                       
                    if ($('#category-check_'+id).is(':checked')) {
                        category_id.push(id);
                    } else {
                        category_id.splice($.inArray(id, category_id), 1);
                    } 
                    
                       
                   var th = $('#category-check_'+id), name = th.attr('name'); 
                   if(th.is(':checked')){
                     $(':checkbox[name="'  + name + '"]').not(th).prop('checked',false);   
                  }else
                  {
                   $('.inventory-products').empty().html("<li>No Result Found</li>");
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
                     if (!(category_id.length === 0))
                    {
                          $.each(category_id,function(i,e)
                          {
                         	 $('#category-check_'+e).prop('checked',true);
                          
                          });
                    }
                   
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
                   product_variant.length=0;
                 
                    $('#create-subtask').addClass('disabled');
                     $('#create-subtask').on('click', function(event) {
                        event.preventDefault();
                    });

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


            $.ajax({
                url: "/get-selected-warehouses",
                type: "post",
                datatype: "json",
                data: {
                    data: data
                },
                success: (data) => {

                 $(".product-list").empty().append(data.html);
                 $('.product-geo').empty().append(data.item);
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
        
           $(document).ready(function(){   
   const myLink = $('#create-subtask');
if (product_variant.length == 0) {
    // Disable the link
    myLink.addClass('disabled');
    myLink.on('click', function(event) {
        event.preventDefault();
    });
} else {
    // Enable the link
    myLink.removeClass('disabled');
    myLink.off('click');
}
   
   });
        
        $(document).on('click','#create-subtask',function(e){


                
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

                    phoneInput();
                },
                error: function(data) {}
            });



        });
        
        $(document).on('click','.sort-by',function()
        {
        
            $.ajax({
                url: "/sort-products",
                type: "post",
                datatype: "html",
                data: {
                    data: product_ids,
                    toggle:1
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
        
  
        
        function getProductVariant(id)
        {
        selected_products.length = 0;
   if ($('#vendor_product_'+id).prop("checked")) {
    product_variant.push(id);
  } else {
    let index = product_variant.indexOf(id);
    if (index > -1) {
      product_variant.splice(index, 1);
    }
  }

    
    if (product_variant.length > 0) {
    
     $('#create-subtask').removeClass('disabled');
     $('#create-subtask').off('click');
    
} else {
    $('#create-subtask').addClass('disabled');
     $('#create-subtask').on('click', function(event) {
        event.preventDefault();
    });
}
    
        }
        
        $(document).on('change','.product-geo',function()
        {
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            
          var val = $(this).val();
          
            $.ajax({
                url: "/get-selected-warehouses",
                type: "post",
                datatype: "html",
                data: {
                    data: product_ids,
                    title:'',
                    filter:val
                },
                success: (data) => {
                $('.product-list').empty().append(data.html);
                
                  if (!(product_variant.length === 0))
                    {
                          $.each(product_variant,function(i,e)
                          {
                         	 $('#vendor_product_'+e).prop('checked',true);
                          
                          });
                    }
                },
                error: () => {
//                     $(".inventory-products").empty().html(data);
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
         
        
        });
        
</script>