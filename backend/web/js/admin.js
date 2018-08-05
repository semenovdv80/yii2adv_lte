$(function () {
    $('.pagination-select').on('change', function () {
        this.form.submit();
    });

    /*order by table's fields*/

    $('#itemsTable').on('click', 'th', function () {
        var column = $(this).data('col');
        console.log(column);
        if (column == undefined) {
            return false;
        }

        var orderClass = $(this).attr('class');
        var order = (orderClass == 'order_asc' || orderClass == 'order_') ? 'desc' : 'asc';

        $.cookie("orderCol", column, {
            expires: 10,
            path: '/'
        });
        $.cookie("orderType", order, {
            expires: 10,
            path: '/'
        });
        location.reload();
    });
});