const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;


$(document).ready(function () {

    $(".sidenav").sidenav();
    $('.datepicker').datepicker();
    $('.materialboxed').materialbox();

    $("body").on('click','.fetch-cclaims',function (){

        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-cclaims',
            data: {},
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
    $("body").on('click','.fetch-portfolio',function (){

        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-portfolio',
            data: {},
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
    $("body").on('click','.fetch-customers',function (){

        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-customers',
            data: {},
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
    $("body").on('click','.fetch-payments',function (){

        $("#loader-wrapper").removeClass('hideLoader');

        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-payments',
            data: {},
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
    $("body").on('click','.fetch-cpayments',function (){
        $("#loader-wrapper").removeClass('hideLoader');
        // var ci_code = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-cpayments',
            data: {
                // ci_code : ci_code
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
    $("body").on('click','#fetch-customer-payments',function (){
        $("#loader-wrapper").removeClass('hideLoader');
        var ci_code = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-customer-payments',
            data: {
                ci_code : ci_code
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
    $("body").on('click','#fetch-policy-details',function (){
        $("#loader-wrapper").removeClass('hideLoader');
        var ci_code = $(this).data("id");
        var email = $(this).data("id2");
        var phone = $(this).data("id3");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-policy-details',
            data: {
                ci_code : ci_code,
                email : email,
                phone : phone
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


    $("body").on('click','#sendPolicyDocument',function (){
        var email = $(this).data("id");
        var policyNumber = $(this).data("id2");
        var name = $(this).data("id3");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/sendPolicyDocument',
            data: {
                email : email,
                policyNumber : policyNumber,
                name : name
            },
            success: function (data) {
                var result = $.parseJSON(data);
                if (result.STATUS_CODE == SUCCESS_CODE) {
                    Swal.fire({
                        icon: 'success',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            }

        });
    });

    $('body').on('focus',".dropdown-trigger", function(){
        $(this).dropdown({
            hover: false, constrainWidth: false
        });
    });
    $(".sidenav-link").on('click',function (e){
        e.preventDefault();
        $(".sidenav-link").removeClass('active');
        $(this).addClass('active');
    });
    $("body").on('click','#contactUsTrigger',function (e){
        e.preventDefault();
        const elem = document.getElementById('contactUs');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
});
