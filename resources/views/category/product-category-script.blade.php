<script type="text/javascript">
    $(document).ready(function() {
        $('#product-category-datatable').DataTable({
            "dom": '<"toolbar">Bfrtip',
            "scrollX": true,
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "iDisplayLength": 10,
            searching: false,
            language: {
                search: "",
                paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                searchPlaceholder: "{{__('Search Product')}}",
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
                url: "{{route('category.product.filter', $catId)}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                }
            },
            columns: [
                {data: 'name', name: 'name', orderable: true, searchable: false},
                {data: 'category', category: 'category', orderable: true, searchable: false},
                {data: 'quantity', quantity: 'quantity', orderable: true, searchable: false},
                {data: 'price', price: 'price', orderable: true, searchable: false},
                {data: 'bar_code', bar_code: 'bar_code', orderable: true, searchable: false},
                {data: 'status', status: 'status', orderable: true, searchable: false},
                {data: 'expiry_date', expiry_date: 'expiry_date', orderable: true, searchable: false},
                {data: 'is_new', is_new: 'is_new', orderable: true, searchable: false},
                {data: 'is_featured', is_featured: 'is_featured', orderable: true, searchable: false}
            ]
        });

        $('.openAddProductModal').click(function() {
            $('#add-product').modal({
                keyboard: false
            });
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
</script>