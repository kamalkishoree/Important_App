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

</script>