<script type="text/javascript">

    $('.openCategoryModal').click(function(){
        $('#add-category-modal .modal-title').text('Add Category');
        $('#add-category-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.openEditCategoryModal').click(function(){
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
</script>