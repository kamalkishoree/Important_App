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
    });
</script>