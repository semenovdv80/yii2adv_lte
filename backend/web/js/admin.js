$(function () {
    $('.pagination-select').on('change', function () {
        this.form.submit();
    });

    /*order by table's fields*/

    $('#itemsTable').on('click', 'th', function () {
        var inputCol = $('input[name=orderCol]');
        var inputDir = $('input[name=orderDir]');

        var column = $(this).data('col');
        console.log(column);
        if (column == undefined) {
            return false;
        }

        var orderClass = $(this).attr('class');
        var orderDir = (orderClass === 'order_asc' || orderClass === 'order_') ? 'desc' : 'asc';
        inputCol.val(column);
        inputDir.val(orderDir);
        $('#filterForm').submit();
    });
});