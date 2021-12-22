const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;


$(document).ready(function () {

    $(".sidenav").sidenav();
    $('.datepicker').datepicker();
    $('.materialboxed').materialbox();
    $("body").on('click','.fetch-travel-policies',function (e){
        e.preventDefault();
        $("#loader-wrapper").removeClass('hideLoader');
        var status = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/travel/fetch-policies',
            data: {
                status : status
            },
            success: function (data) {
                $("#main").html(data);
                $('.datepicker').datepicker();
                $('#data-table-simple').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "pageLength": 25
                });
                $("#loader-wrapper").addClass('hideLoader');
            }

        });
    });
    $("body").on('click','#filter-travel-policies',function (e){
        e.preventDefault();
        $("#loader-wrapper").removeClass('hideLoader');
        var status = $("#status").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var policyNumber = $("#policyNumber").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/travel/fetch-policies',
            data: {
                status : status,
                fromDate : fromDate,
                toDate : toDate,
                policyNumber : policyNumber
            },
            success: function (data) {
                $("#main").html(data);
                $('.datepicker').datepicker();
                $('#data-table-simple').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "pageLength": 25
                });
                $("#loader-wrapper").addClass('hideLoader');
            }

        });
    });
    $(".sidenav-link").on('click',function (e){
        e.preventDefault();
        $(".sidenav-link").removeClass('active');
        $(this).addClass('active');
    });
});
