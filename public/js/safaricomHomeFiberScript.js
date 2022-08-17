const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;


$(document).ready(function () {
    $('.collapsible').collapsible();
    $(".collapsible-header").on('click',function (e){
        e.preventDefault();
        $('.collapsible-header').removeClass('ahover');
        $(this).addClass('ahover');
    });

    $(".sidenav").sidenav();
    $('.datepicker').datepicker();
    $('.materialboxed').materialbox();

    function addLoadingButton()
    {
        $('.loadingButton').addClass("showLoadingButton");
        $('.actionButton').addClass("hideActionButton");
        $('.loadingButton').removeClass("hideLoadingButton");
        $('.actionButton').removeClass("showActionButton");
    }
    function removeLoadingButton()
    {
        $('.loadingButton').removeClass("showLoadingButton");
        $('.actionButton').removeClass("hideActionButton");
        $('.loadingButton').addClass("hideLoadingButton");
        $('.actionButton').addClass("showActionButton");
    }

    $("body").on('click','#fetchCClaims',function (){

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
    $("body").on('click','#fetchPortfolio',function (){

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
            url: '/safaricom-home-fibre/fetch-portfolio',
            data: {
                ci_code: ci_code,
                email: email,
                phone: phone
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
    $("body").on('click','#launch_claim_form',function (){
        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/launch-claim-form',
            data: {},
            success: function (data) {
                $("#main").html(data);
                // $('.input-images').imageUploader({label : "Drag & Drop Images here or click to browse"});
            }

        });
    });
    $("body").on('click','#downloadclaimFormpdf',function (){

        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/download-claim-form',
            data: {},
            success: function (data) {
                $("#main").html(data);
                // window.location.href='/safaricom-home-fibre/download-claim-form'
            }

        });
    });
    $("body").on('click','#validatePageOne',function (){

        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            // type: 'POST',
            type: 'GET',
            url: '/safaricom-home-fibre/claim-form',
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
                // var w = window.open('about:blank');
                // w.document.open();
                // w.document.write(data);
                // w.document.close();
                //
                // window.location.href='/safaricom-home-fibre/claim-form'
            }

        });
    });
    $("body").on('click','.previous-page1',function (){

        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/previous-page1',
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
    $("body").on('click','#submitSafClaim',function (){
        $("#loader-wrapper").removeClass('hideLoader');
        // alertify.set('notifier','position', 'top-right');
        // alertify.error('I am here');

        var lossDescription = $("#lossDescription").val();

        var claim_form = $("#uploadClaimFormpdf").data("id");
        var abstract_form = $("#abstract_file").data("id");
        var handset_certificate = $("#handsetCertificate_file").data("id");
        var proforma_invoice = $("#proformaInvoice_file").data("id");

        var formData = new FormData();
        var claimForm = $('#uploadClaimFormpdf').prop('files')[0];
        var abstract = $('#abstract_file').prop('files')[0];
        var handsetCertificate = $('#handsetCertificate_file').prop('files')[0];
        var proformaInvoive = $('#proformaInvoice_file').prop('files')[0];

        formData.append('uploadClaimFormpdf', claimForm);
        formData.append('abstract_file', abstract);
        formData.append('file2', handsetCertificate);
        formData.append('file3', proformaInvoive);

        formData.append('lossDescription', lossDescription);

        formData.append('claim_form', claim_form);
        formData.append('abstract_form', abstract_form);
        formData.append('handset_certificate', handset_certificate);
        formData.append('proforma_invoice', proforma_invoice);




        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/save-safaricom-claim',
            data: formData,
            processData: false,
            contentType: false,

            // crossDomain: true,
            // dataType: "text/plain",
            success: function (data) {
                var result = $.parseJSON(data);
                if (result.STATUS_CODE == SUCCESS_CODE) {
                    Swal.fire({
                        icon: 'success',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                    // alertify.set('notifier','position', 'bottom-center');
                    // alertify.success(result.STATUS_MESSAGE);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
                removeLoadingButton();
            },
            error:function (error) {
                console.log(error)
                alertify.set('notifier','position', 'bottom-left');
                alertify.error("Kindly insert to all the mandatory fields");
                // alertify.error(error);
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
    $("body").on('click','.fetchClaims',function (){

        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-all-claims',
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
    $("body").on('click','#fetchCPayments',function (){
        $("#loader-wrapper").removeClass('hideLoader');
        var ci_code = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/safaricom-home-fibre/fetch-cpayments',
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
