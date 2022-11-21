<script type="text/javascript">
    $('.openCategoryModal').click(function(){
        $('#add-category-modal .modal-title').text('Add Category');
        $('#add-category-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(document).on('click','.openEditCategoryModal', function(){
        var cat_name = $(this).data('name');
        var cat_status = $(this).data('status');
        var cat_id = $(this).data('id');
        $('#add-category-modal #name').val(cat_name);
        $('#add-category-modal #cat_id').val(cat_id);
        $('#add-category-modal #cat_status').val(cat_status);
        $('#add-category-modal .modal-title').text('Edit Category');
        $('#add-category-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.openAddProductModal').click(function() {
        $('#add-product').modal({
            keyboard: false
        });
    });
    
    var regexp = /^[a-zA-Z0-9-_]+$/;
    function setSkuFromName() {
        var n1 = $('#product_name').val();
        var sku_start = "{{ $sku_url }}" + ".";
        var total_sku = sku_start + n1;
        $('#sku').val(sku_start + n1);
        if (regexp.test(n1)) {
            var n1 = $('#product_name').val();
            $('#url_slug').val(n1);
            slugify();
        } else {
            $('#sku').val(total_sku.split(' ').join(''));
        }
        
        alplaNumeric();
    }
    
    function alplaNumeric() {
        var n1 = $('#sku').val();
        if (regexp.test(n1)) {
            var n1 = $('#sku').val();
            $('#url_slug').val(n1);
            slugify();
        } else {
            $('#sku').val(n1.split(' ').join(''));
        }
    }

    function slugify() {
        var string = $('#url_slug').val();
        var slug = string.toString().trim().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(/\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "");
        $('#url_slug').val(slug);
    }

    $('#db_name').on('change', function() {
        $('#db_form').submit(); 
    });

    $(document).ready(function() {
        $('#category-datatable').DataTable({
            "dom": '<"toolbar">Bfrtip',
            "scrollX": true,
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "iDisplayLength": 10,
            language: {
                search: "",
                paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                searchPlaceholder: "{{__('Search Category')}}",
                'loadingRecords': '&nbsp;',
                // 'processing': '<div class="spinner"></div>'
                'processing':function(){
                    spinnerJS.showSpinner();
                    spinnerJS.hideSpinner();
                }
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            ajax: {
                url: "{{url('category/filter')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.imgproxyurl = '{{$imgproxyurl}}';
                    d.order_panel_id = $('#db_name').val();
                }
            },
            columns: [
                {data: 'name', name: 'name', orderable: true, searchable: false},
                {data: 'status', name: 'status', orderable: true, searchable: false},
                {data: 'created_at', name: 'created_at', orderable: true, searchable: false},
                {data: 'total_products', name: 'total_products', orderable: true, searchable: false},
                {data: 'action', name: 'action', orderable: true, searchable: false}
            ]
        });
    });

    function ajaxCheckSync(sync_status, order_panel_id){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url : "{{ url('check-sync-status') }}",
            data : {'sync_status' : sync_status, 'order_panel_id': order_panel_id},
            type : 'POST',
            dataType : 'json',
            success : function(result){
                if(result == 2){
                    $(".syncProcessing").hide();
                    location.reload();
                }
            }
        });
    }

    $(document).ready(function(){
        var sync_status = '{{$order_panel->sync_status ?? 0}}';
        var order_panel_id = '{{app('request')->input('order_panel_id') ?? ''}}';
        if(sync_status == 1 && order_panel_id != ''){
            setTimeout(function() {
                ajaxCheckSync(sync_status, order_panel_id);
            }, 2000);
        }
        setTimeout(function() {
            $('#syncCompleted').hide();
        }, 3000);
    }); 
</script>