const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;


$(document).ready(function () {

    $(".sidenav").sidenav();
    $('.datepicker').datepicker();
    $('.materialboxed').materialbox();
    $("body").on('click','.add-claim-form',function (e){
        e.preventDefault();
        $("#loader-wrapper").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/nhif/add-claim-form',
            data: {
            },
            success: function (data) {
                $("#main").html(data);
                $('.datepicker').datepicker({
                    changeYear: true,
                    yearRange: [1945,2030],
                    // format: 'yyyy-mm-dd'
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
    $("body").on('click','.fetch-nhif-claims',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        // alert(claimStatusID);

        $("#loader-wrapper").removeClass('hideLoader');
        // $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/nhif/fetch-nhif-claims',
            data: {
                'claimStatusID' : claimStatusID
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
                $("#mainLoader").addClass('hideLoader');
            }

        });

    });
    $("body").on('click','.fetch_proportions',function (e){
        e.preventDefault();


        $("#loader-wrapper").removeClass('hideLoader');
        // $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/nhif/fetch_proportions',
            data: {
                // 'claimStatusID' : claimStatusID
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
                $("#mainLoader").addClass('hideLoader');
            }

        });

    });

    $("#parent").click(function() {

        if($(this).is(':checked'))
        {
            $('#approveAll').show();
        }
        else{
            $('#approveAll').hide();
        }

        $(".child").prop("checked", $(this).is(':checked'));
    });
    $('.child').click(function() {
        if ($('.child:checked').length == $('.child').length) {
            $('#parent').prop('checked', true);
        } else {
            $('#parent').prop('checked', false);
        }
    });


    $(".child").click(function(){
        if($(this).is(':checked'))
        {
            $('#approveAll').show()
        }
    });
    $('.tbl-accordion-nested').each(function(){
        var thead = $(this).find('thead');
        var tbody = $(this).find('tbody');
        tbody.hide();
        thead.click(function(e){
            $(this).toggleClass('disp');
            var tbody = $('.tbl-accordion-nested').find('tbody');
            tbody.not(this).hide();
            var tbody = $(this).find('tbody');
            if(thead.hasClass('disp'))
            {
                $(this).next().toggle();
            }else{
                $(this).next().hide();
            }
        })
    });

    $("#main").on('click','#filter_nhif_claims',function (e){
        e.preventDefault();
        var claimStatusID = $("#claimStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var claimNumber = $("#claimNo").val();

        // alert(claimNumber);



        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                claimStatusID : claimStatusID,
                fromDate : fromDate,
                toDate : toDate,
                claimNumber : claimNumber
            },
            url: '/nhif/fetch-nhif-claims',

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
            }

        });
    });

    // $("body").on('click','.claims-in-progress',function (e){
    //     e.preventDefault();
    //     $("#loader-wrapper").removeClass('hideLoader');
    //     // $("#mainLoader").removeClass('hideLoader');
    //     $.ajaxSetup({
    //
    //         headers: {
    //
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //
    //         }
    //
    //     });
    //     $.ajax({
    //
    //         type: 'POST',
    //         url: '/nhif/claims-in-progress',
    //         success: function (data) {
    //             $("#main").html(data);
    //             $('.datepicker').datepicker();
    //             $('#data-table-simple').DataTable({
    //                 dom: 'Bfrtip',
    //                 buttons: [
    //                     'copy', 'csv', 'excel', 'pdf', 'print'
    //                 ],
    //                 "pageLength": 25
    //             });
    //             $("#mainLoader").addClass('hideLoader');
    //         }
    //
    //     });
    //
    // });
    // $("body").on('click','.paid_claims',function (e){
    //     e.preventDefault();
    //     $("#loader-wrapper").removeClass('hideLoader');
    //     // $("#mainLoader").removeClass('hideLoader');
    //     $.ajaxSetup({
    //
    //         headers: {
    //
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //
    //         }
    //
    //     });
    //     $.ajax({
    //
    //         type: 'POST',
    //         url: '/nhif/paid-nhif-claims',
    //         success: function (data) {
    //             $("#main").html(data);
    //             $('.datepicker').datepicker();
    //             $('#data-table-simple').DataTable({
    //                 dom: 'Bfrtip',
    //                 buttons: [
    //                     'copy', 'csv', 'excel', 'pdf', 'print'
    //                 ],
    //                 "pageLength": 25
    //             });
    //             $("#mainLoader").addClass('hideLoader');
    //         }
    //
    //     });
    //
    // });
    // $("body").on('click','.closed_claims',function (e){
    //     e.preventDefault();
    //     $("#loader-wrapper").removeClass('hideLoader');
    //     // $("#mainLoader").removeClass('hideLoader');
    //     $.ajaxSetup({
    //
    //         headers: {
    //
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //
    //         }
    //
    //     });
    //     $.ajax({
    //
    //         type: 'POST',
    //         url: '/nhif/closed-nhif-claims',
    //         success: function (data) {
    //             $("#main").html(data);
    //             $('.datepicker').datepicker();
    //             $('#data-table-simple').DataTable({
    //                 dom: 'Bfrtip',
    //                 buttons: [
    //                     'copy', 'csv', 'excel', 'pdf', 'print'
    //                 ],
    //                 "pageLength": 25
    //             });
    //             $("#mainLoader").addClass('hideLoader');
    //         }
    //
    //     });
    //
    // });
    // $("body").on('click','.rejected_claims',function (e){
    //     e.preventDefault();
    //     $("#loader-wrapper").removeClass('hideLoader');
    //     // $("#mainLoader").removeClass('hideLoader');
    //     $.ajaxSetup({
    //
    //         headers: {
    //
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //
    //         }
    //
    //     });
    //     $.ajax({
    //
    //         type: 'POST',
    //         url: '/nhif/rejected-nhif-claims',
    //         success: function (data) {
    //             $("#main").html(data);
    //             $('.datepicker').datepicker();
    //             $('#data-table-simple').DataTable({
    //                 dom: 'Bfrtip',
    //                 buttons: [
    //                     'copy', 'csv', 'excel', 'pdf', 'print'
    //                 ],
    //                 "pageLength": 25
    //             });
    //             $("#mainLoader").addClass('hideLoader');
    //         }
    //
    //     });
    //
    // });

    $(".follower-link").on('click',function (e){
        e.preventDefault();
        $(".follower-link").removeClass('active');
        $(this).addClass('active');
    });
    $(".collapsible-header").on('click',function (e){
        e.preventDefault();
        $('.collapsible-header').removeClass('ahover');
        $(this).addClass('ahover');
    });

    $('body').on('focus',".dropdown-trigger", function(){
        $(this).dropdown({
            hover: false, constrainWidth: false
        });
    });

    $("body").on('click','#uploadDocumentsForm',function (e){
        e.preventDefault();
        // alert("upload");
        var claimID = $(this).data("id");
        // console.log(id);
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/nhif/uploadDocumentsForm',
            data: {
                claimID : claimID
            },
            success: function (data) {
                $("#main").html(data);
                $('.input-images').imageUploader({label : "Drag & Drop Images here or click to browse"});
            }

        });
    });

    $("body").on('click','#uploadClaimDocuments',function (e) {
        e.preventDefault();
        var claimID = $("#claimID").val();
        var image_upload = new FormData();
        // Attach file
        // formData.append('image', $('input[type=file]')[0].files[0]);
        var files = $('input[type=file]')[0].files;
        let totalImages = files.length; //Total Images
        let images = $('input[type=file]')[0];
        for (let i = 0; i < totalImages; i++) {
            image_upload.append('images' + i, images.files[i]);
        }
        var claimForm = $('#claimFormpdf').prop('files')[0];
        image_upload.append('totalImages', totalImages);
        image_upload.append('claimID', claimID);
        image_upload.append('claimForm', claimForm);
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            contentType: false,
            processData: false,
            data: image_upload,
            url: '/nhif/uploadDocuments',
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

    $("body").on('click','.nhifClaimDetails',function (e){
        e.preventDefault();
        var status = $(this).data("id");
        var claimNo = $(this).attr("id");


        // alert(status);
        // alert(claimNo);
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/nhif/claim-details',
            data: {
                status:status,
                claimNo:claimNo
            },

            success: function (data) {
                $("#main").html(data);
                $('select').formSelect();
            }

        });
    });
    $(Document).on("click", "#addClaim", function (e){
        e.preventDefault();

        var claimant=$("#claimant").val();
        var postalAddress=$("#postalAddress").val();
        var postalCode=$("#postalCode").val();
        var telephone=$("#telephone").val();
        var mobile=$("#mobile").val();
        var email=$("#email").val();
        var occupation=$("#occupation").val();
        var dateOfBirth=$("#dateOfBirth").val();
        var IDNumber=$("#IDNumber").val();
        var placeOfLoss=$("#placeOfLoss").val();
        var causeOfLoss=$("#causeOfLoss").val();
        var dateOfInjury=$("#dateOfInjury").val();
        var dateReceived=$("#dateReceived").val();
        var lossDescription=$("#lossDescription").val();
        var policyType=$("#policyType").val();
        var typeOfInjury=$("#typeOfInjury").val();


        var formData = new FormData();
        var claimForm = $('#injuryClaimFormpdf').prop('files')[0];

        formData.append('file', claimForm);
        formData.append('claimant', claimant);
        formData.append('postalAddress', postalAddress);
        formData.append('postalCode', $("#postalCode").val());
        formData.append('telephone', telephone);
        formData.append('mobile', mobile);
        formData.append('email', email);
        formData.append('occupation', occupation);
        formData.append('dateOfBirth', dateOfBirth);
        formData.append('IDNumber', IDNumber);
        formData.append('placeOfLoss', placeOfLoss);
        formData.append('causeOfLoss', causeOfLoss);
        formData.append('dateOfInjury', dateOfInjury);
        formData.append('dateReceived', dateReceived);
        formData.append('lossDescription', lossDescription);
        formData.append('policyType', policyType);
        formData.append('typeOfInjury', typeOfInjury);

        // addLoadingButton();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({
            type: "POST",
            contentType: false,
            processData: false,
            url: "/nhif/save_nhif_claim",
            data:formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if (result.STATUS_CODE == SUCCESS_CODE) {
                    Swal.fire({
                        icon: 'success',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                // } else if (response.status == 400){
                //     $("#saveform_errList").html("");
                //     $("#saveform_errList").addClass("alert alert-danger");
                //     $.each(response.errors, function (key,err_values) {
                //         $("#saveform_errList").append('<li>'+err_values +'</li>');
                //     });
                //
                //     $("addClaim").text("submit");

                }else {
                    Swal.fire({
                        icon: 'error',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
                // removeLoadingButton();
            }
        });
        })

    $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false,
            gutter: 0, // Spacing from edge
            belowOrigin: true, // Displays dropdown below the button
            alignment: 'left', // Displays dropdown with edge aligned to the left
            stopPropagation: false // Stops event propagation
        }
    );

});
