var order_stock_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: o_url+"/point/ajax.orderstock.php",
        cache: false,
        async: false,
        success: function(data) {
            result = data;
        }
    });
    return result;
}