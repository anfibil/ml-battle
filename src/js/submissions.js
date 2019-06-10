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
		"autoWidth": true,
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
                "searchable": true,
				"className": " hidden-xs hidden-sm visible-md visible-lg"				
            },
            {
                "data": "ip",
                "orderable": true,
                "searchable": true,
				"className": " hidden-xs hidden-sm hidden-md visible-lg"
            },
            {
                "data": "dataset",
                "orderable": true,
                "searchable": true,
				"render": function (data, type, full, meta) {
                    if (data) {
                        return '<span style="text-transform: capitalize;">' + data.toLowerCase() + '</span>';
                    } else {
                        return 'Not Available';
                    }
                }
            },
            {
                "data": "modelName",
                "orderable": true,
                "searchable": true
            },
            {
                "data": "accuracy",
                "orderable": true,
                "render": function (data, type, full, meta) {
                    if (data) {
                        return '<div class="txtcenter"><span class="scorenumber" style="color:' + hsl_col_perc(data, 0, 120) + '">' + data.toFixed(4) + '</span></div>';
                    } else {
                        return '<div class="txtcenter">Not Available</div>';
                    }
                }
            },
            {
                "data": "metric",
                "orderable": true,
                "searchable": true
            }
        ],
        "pagingType": "full_numbers",
        "order": [[3, 'asc'], [5, 'desc'], [1, 'asc']],
        "fnDrawCallback": function (oSettings) {
        }
    });

    setInterval( function () {
        table.ajax.reload();
    }, 30000 );


  var update_size = function() {
    jQuery('#resultstable').css({ width: jQuery('#resultstable').parent().width() });
    //jQuery('#resultstable').fnAdjustColumnSizing();  
  }

  jQuery(window).resize(function() {
    clearTimeout(window.refresh_size);
    window.refresh_size = setTimeout(function() { update_size(); }, 100);
  });

});

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

    var a = percent;
    var b = end * a;
    c = b + start;

    //Return a CSS HSL string
    return 'hsl(' + c + ',100%,35%)';
}
