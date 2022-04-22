jQuery(document).ready(function($) {

    // DATA TABLE JQUERY STARTS HERE 
    $('#poll-answers-table').DataTable({
        responsive: true,
        aLengthMenu: [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "All"]
        ],
        "pageLength": 10,
        "autoWidth": false,
        "oLanguage": {
            "oPaginate": {
                    "sNext": '<span class="pagination-fa"><i class="fa fa-angle-right" ></i></span>',
                    "sPrevious": '<span class="pagination-fa"><i class="fa fa-angle-left" ></i></span>'
                }
        },
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderable": true, "className": "dt-center", "targets": 1 },
            { "orderable": false, "targets": 2 },
        ]
    });
});