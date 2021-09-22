var TableManagedclass = function () {

    var initTable = function () {

        var table = $('.dulieubang');

        // begin: third table
        table.dataTable({
            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
            
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "language": {
                "lengthMenu": "Hiển thị _MENU_ thông tin",
                "search": "Tìm kiếm:",
                "emptyTable": "Không có thông tin",
                "info": "Hiển thị _START_ đến _END_ trên _TOTAL_ thông tin",
                "infoEmpty": "",
                "infoFiltered": "(tìm kiếm trong _MAX_ thông tin)",
                "zeroRecords": "Không có thông tin"
            },
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "bSort" : false
            //"order": [
            //    [0, "asc"]
            //] // set first column as a default sort by asc
        });

    }



    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            initTable();
        }
    };
}();