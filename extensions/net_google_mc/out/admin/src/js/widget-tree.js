$(document).ready(function () {
    $('#jstree_demo_div').jstree({
        "checkbox" : {
            "keep_selected_style" : false,
            "two_state" : false,
            "whole_node" : true
        },
        "plugins" : [ "checkbox" ],
        core: {
            "themes": {
                "icons": false
            }
        }
    });
    $('#jstree_demo_div').jstree(true).open_all();
    $('li[data-checkstate="checked"]').each(function() {
        $('#jstree_demo_div').jstree('check_node', $(this));
    });
    $('#jstree_demo_div').jstree(true).close_all();




    $("#myedit").submit(function (e) {

        var checked_ids = $("#jstree_demo_div").jstree('get_checked');
        $.each(checked_ids, function(index, value) {
            $('#dynamic-hidden-fields').append("<input type='hidden' name='editval[" + value + "]' value='1'>");
        });
    });

});