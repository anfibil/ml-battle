/**
 This is our custom JS file. Please edit only this file
 */
jQuery(document).ready(function () {


    // Setup the datatable
    var table = jQuery('#resultstable').DataTable({
        "searchDelay": 500,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "ajax": 'super-secure-database.json',
        "bDestroy": true,
        "iDisplayLength": 50,
        "processing": true,
        "serverSide": false,
        "oLanguage": {
            "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
        },
        "columns": [
            {
                "data": "studentName",
                "orderable": true,
                "searchable": true,
                "defaultContent": 'Unknown'
            },
            {
                "data": "date",
                "orderable": true,
                "searchable": true
            },
            {
                "data": "dataset",
                "orderable": true,
                "searchable": true
            },
            {
                "data": "accuracy",
                "orderable": true,
                "render": function (data, type, full, meta) {
                    if (data) {
                        return '<div class="txtcenter"><span class="scorenumber" style="color:' + hsl_col_perc(data, 0, 120) + '">' + data.toFixed(4) + '%</span></div>';
                    } else {
                        return '<div class="txtcenter">Not Available</div>';
                    }
                }
            },   
            {
                "data": "ip",
                "orderable": true,
                "searchable": true
            }         
        ],
        "pagingType": "full_numbers",
        "order": [[2, 'asc'], [3, 'desc'], [1, 'asc']],
        "fnDrawCallback": function (oSettings) {
        }
    });


});

function ucfirst(str) {
    str += '';
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1);
}

/*
 * Based in a percentage, create a custom color gradient
 * Change the start and end values to reflect the hue map
 * Quick ref: http://www.ncl.ucar.edu/Applications/Images/colormap_6_3_lg.png
 * 0 – red
 * 60 – yellow
 * 120 – green
 * 180 – turquoise
 * 240 – blue
 * 300 – pink
 * 360 – red
 */
function hsl_col_perc(percent, start, end) {

    var a = percent / 100;
    var b = end * a;
    c = b + start;

    //Return a CSS HSL string
    return 'hsl(' + c + ',100%,35%)';
}
