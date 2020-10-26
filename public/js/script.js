const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;
$(document).ready(function () {
    $("#login-btn").on('click', function (e) {
        e.preventDefault();
        var email = $("#email");
        var password = $("#password");
        if (isNotEmpty(email) && isNotEmpty(password)) {
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',

                url: '/loginUser',

                data: {password: password.val(), email: email.val()},

                success: function (data) {
                    var result = $.parseJSON(data);
                    if (result.STATUS_CODE == SUCCESS_CODE) {
                        window.location.href = '/dashboard';
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
        }
    });
    /**
     * Function to send a password rest email
     */
    $("#validatepasswordreset").on('click', function (e) {
        e.preventDefault();
        var email = $("#email");
        if (isNotEmpty(email)) {
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',

                url: '/password/email',

                data: {email: email.val()},

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

        }

    });
    $("#resetpassword").on('click', function (e) {
        e.preventDefault();
        var password = $("#password");
        var password_confirmation = $("#password_confirmation");
        var email = $("#email");
        var token = $("#reset_token");
        if (isNotEmpty(password) && isNotEmpty(password_confirmation) && isNotEmpty(email)) {
            if (password.val() == password_confirmation.val()) {
                $.ajaxSetup({

                    headers: {

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                });
                $.ajax({

                    type: 'POST',

                    url: '/completePasswordRest',

                    data: {
                        password: password.val(),
                        password_confirmation: password_confirmation.val(),
                        email: email.val(),
                        token: token.val()
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
                            window.location.href = '/';
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
            } else {
                Swal.fire({
                    icon: 'error',
                    title: "Password and confirm password does not match",
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: "Oops! provide all the requested data",
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
    $("#email,#password").on("keyup", function () {
        isNotEmpty($(this));
    });
    $("body").on('click','#addClaim', function (e) {
        e.preventDefault();
        var claimNo = $("#claimNo");
        var customerCode = $("#customerCode");
        var claimType = $("#claimType");
        var sumInsured = $("#sumInsured");
        var excess = $("#excess");
        var vehicleRegNo = $("#vehicleRegNo");
        var policyNo = $("#policyNo");
        var branch = $("#branch");
        var loseDate = $("#loseDate");
        var intimationDate = $("#intimationDate");
        var email = $("#email");
        var fullName = $("#fullName");
        var MSISDN = $("#MSISDN");
        var location = $("#location");
        var originalSumInsured = $("#originalSumInsured");
        var originalExcess = $("#originalExcess");
        var carMakeCode = $("#carMakeCode");
        var carModelCode = $("#carModelCode");
        var yom = $("#yom");
        var engineNumber = $("#engineNumber");
        var chassisNumber = $("#chassisNumber");
        if(location.val() != '')
        {
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',

                url: '/adjuster/addClaim',

                data: {
                    claimNo : claimNo.val(),
                    customerCode : customerCode.val(),
                    claimType : claimType.val(),
                    sumInsured : sumInsured.val(),
                    excess : excess.val(),
                    vehicleRegNo : vehicleRegNo.val(),
                    policyNo : policyNo.val(),
                    branch : branch.val(),
                    loseDate : loseDate.val(),
                    intimationDate : intimationDate.val(),
                    email : email.val(),
                    fullName : fullName.val(),
                    MSISDN : MSISDN.val(),
                    location : location.val(),
                    originalExcess : originalExcess.val(),
                    originalSumInsured : originalSumInsured.val(),
                    carMakeCode : carMakeCode.val(),
                    carModelCode : carModelCode.val(),
                    yom : yom.val(),
                    engineNumber : engineNumber.val(),
                    chassisNumber : chassisNumber.val()
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
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'You have not the specified location',
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
    $("#assignRole").on('click',function (e){
        var userID = $("#userID");
        var roleID = $("#roleID");
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/assignRole',

            data: {
                userID : userID.val(),
                roleID : roleID.val(),
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
    $("body").on('click', '#updateClaim', function (e) {
        e.preventDefault();
        var sumInsured = $("#sumInsured");
        var excess = $("#excess");
        var location = $("#location");
        var claimID = $("#claimID");
        var oldExcess = $("#oldExcess");
        var oldSumInsured = $("#oldSumInsured");
        var image_upload = new FormData();

        var imagesArray = $("#imagesArray").val();
        var imageParse = JSON.parse(imagesArray);
        var counter = 0;

        var imgArray = [];

        $(".uploaded-image img").each(function() {
            var imgsrc = this.src;
            var n = imgsrc.split("/");
            var result = n[n.length - 1];
            imgArray.push(result);
        });
        imageParse = imageParse.filter(function( obj ) {
            return imgArray.includes(obj.name);
        });

        console.log(imageParse);

        var files = $('input[type=file]')[0].files;
        let totalImages = files.length; //Total Images
        let images = $('input[type=file]')[0];
        var img = [];
        for (let i = 0; i < totalImages; i++) {
            var increment = imageParse.length+i;
            image_upload.append('images' + increment, images.files[i]);
        }
        $.each(imageParse, function (key, value) {
            async function createFile() {
                let response = await fetch('http://127.0.0.1:8001/documents/' + value.name);
                let data = await response.blob();
                let metadata = {
                    type: 'image/jpeg'
                };
                let file = new File([data],value.name, metadata);
                img.push(file);
            }
            createFile();
            counter++;
        });
        setTimeout(function () {
            for (let i = 0; i < img.length; i++) {
                image_upload.append('images' + i, img[i]);

            }
        image_upload.append('totalImages', imageParse.length+totalImages);
        image_upload.append('sumInsured', sumInsured.val());
        image_upload.append('excess', excess.val());
        image_upload.append('location', location.val());
        image_upload.append('claimID', claimID.val());
        image_upload.append('oldExcess', oldExcess.val());
        image_upload.append('oldSumInsured', oldSumInsured.val());
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/adjuster/updateClaim',
            processData: false,
            contentType: false,
            data: image_upload,
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
        },1000);
    });
    $('body').on('focus',".dropdown-trigger", function(){
        $(this).dropdown({
                 hover: false, constrainWidth: false
            });
    });
    $('body').on('focus',".yearpicker", function(){
        $(this).datepicker({
            yearRange: [1950,2005],
            changeYear: true,
            dateFormat: 'yyyy'
        });
    });
    $('.datepicker').datepicker();
    $('.collapsible').collapsible();
    $(".sidenav-link").on('click',function (e){
        e.preventDefault();
        $(".sidenav-link").removeClass('active');
        $(this).addClass('active');
    });
    $(".collapsible-header").on('click',function (e){
        e.preventDefault();
        $('.collapsible-header').removeClass('ahover');
        $(this).addClass('ahover');
    });
    $("#uploadClaims").on('click',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/uploadClaims',

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
    $("body").on('click','#headAssessorClaims',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/head-assessor/claims',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','.head-assessor-claims',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'claimStatusID' : claimStatusID
            },

            url: '/head-assessor/claims',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#assessorAssessments',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/assessor/assessments',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','.assessment-manager-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'assessmentStatusID' : assessmentStatusID
            },
            url: '/assessment-manager/assessments',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#fillAssessmentReport',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/assessor/fillAssessmentReport/'+id,

            success: function (data) {
                $("#main").html(data);
                var drafted = $("#drafted").val();
                var stepper = document.querySelector('.stepper');
                var stepperInstace = new MStepper(stepper, {
                    // options
                    firstActive: 0 // this is the default
                });
                var finalArray = [];
                if(drafted == 1) {
                    var imagesArray = $("#imagesArray").val();
                    $.each(JSON.parse(imagesArray), function (key, value) {
                        var imgData = {id: value.id, src: "documents/" + value.name}
                        finalArray.push(imgData);
                    });
                }
                $('.input-images').imageUploader({
                    label : "Drag & Drop Images here or click to browse",
                    preloaded : finalArray
                });
                $('select').formSelect();
            }

        });
    });
    $("body").on('click','#fillReInspectionReport',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/assessor/fillReInspectionReport/'+id,

            success: function (data) {
                $("#main").html(data);
                $('select').formSelect();
                var stepper = document.querySelector('.stepper');
                var stepperInstace = new MStepper(stepper, {
                    // options
                    firstActive: 0 // this is the default
                });
                $('.input-images').imageUploader({label : "Drag & Drop Images here or click to browse"});
            }

        });
    });
    $("body").on('click','#editClaimForm',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/editClaimForm/'+id,

            success: function (data) {
                $("#main").html(data);
                var finalArray = [];
                var imagesArray = $("#imagesArray").val();
                $.each(JSON.parse(imagesArray), function (key, value) {
                    var imgData = {id: value.id, src: "documents/" + value.name}
                    finalArray.push(imgData);
                });
                $('.input-images').imageUploader({
                    label: "Drag & Drop Images here or click to browse",
                    preloaded: finalArray,
                    imagesInputName: 'images',
                    preloadedInputName: 'old'
                });
            }

        });
    });
    $("body").on('click','#uploadDocumentsForm',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/uploadDocumentsForm/'+id,

            success: function (data) {
                $("#main").html(data);
                $('.input-images').imageUploader({label : "Drag & Drop Images here or click to browse"});
            }

        });
    });
    $(".fetch-assessments").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'assessmentStatusID' : assessmentStatusID
            },
            url: '/adjuster/assessments',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $(".fetch-claims").on('click',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'claimStatusID' : claimStatusID
            },
            url: '/adjuster/fetch-claims',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $(".assessor-fetch-assessments").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'assessmentStatusID' : assessmentStatusID
            },
            url: '/assessor/assessments',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $(".head-assessor-assessments").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'assessmentStatusID' : assessmentStatusID
            },
            url: '/head-assessor/assessments',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $(".assistant-head-assessor-assessments").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'assessmentStatusID' : assessmentStatusID
            },
            url: '/assistant-head-assessor/assessments',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#assessmentReport',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/adjuster/assessmentReport',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#assessment-manager-assessment-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assessment-manager/assessment-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#assessor-assessment-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assessor/assessment-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#head-assessor-assessment-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/head-assessor/assessment-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#assessmentDetails',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/assessment-details/'+id,

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#claimDetails',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/claim-details/'+id,

            success: function (data) {
                $("#main").html(data);
                $('.imageZoom').imageZoom();
            }

        });
    });
    $("body").on('click','#claimForm',function (e){
        e.preventDefault();
        var data = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/adjuster/claim-form',
            data: JSON.stringify(data),

            success: function (data) {
                $("#main").html(data);
                $('select').formSelect();
            }

        });
    });
    $("body").on('click','#assignRole',function (e){
        var id = $(this).data("id");
        var userID = $(this).attr("data-user");
        var roles = [];
        $.each($("input[name='roles_"+id+"']:checked"), function(){
            roles.push($(this).val());
        });
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/assignRole',

            data: {
                roles : roles,
                userID : userID
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
    $(".listUsers").on('click',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/listUsers',
            success: function (data) {
                $("#main").html(data);
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
    $("body").on('click','#registerUserForm',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/admin/registerUserForm',
            success: function (data) {
                $("#main").html(data);
                $('select').formSelect();
            }

        });
    });
    $("body").on('click','#registerUser',function (e){
        e.preventDefault();
        var firstName = $("#firstName");
        var middleName = $("#middleName");
        var lastName = $("#lastName");
        var userType = $("#userType");
        var MSISDN = $("#MSISDN");
        var idNumber = $("#idNumber");
        var email = $("#email");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'post',
            data : {
                firstName : firstName.val(),
                middleName : middleName.val(),
                lastName : lastName.val(),
                userType : userType.val(),
                MSISDN : MSISDN.val(),
                idNumber : idNumber.val(),
                email : email.val()
            },
            url: '/admin/registerUser',
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
                $('select').formSelect();
            }

        });
    });
    $("body").on("click",'#searchClaim',function (){
        var fromDate = $("#fromDate");
        var toDate = $("#toDate");
        var vehicleRegNo = $("#vehicleRegNo");
        var url = '/adjuster/filterPremia11ClaimsByDate';
        var data = {
            fromDate : fromDate.val(),
            toDate : toDate.val(),
            vehicleRegNo : vehicleRegNo.val()
        };
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: url,
            data: data,

            success: function (data) {
                $("#main").html(data);
                $('.datepicker').datepicker();
            }

        });
    });
    $("body").on('click','.assessmentType',function (){
        var radioValue = $("input[name='assessmentType']:checked").val();
        if(radioValue == '3')
        {
            $(".totalLose").removeClass('hideTotalLose');
            $("#authorityToGarage").addClass('hideTotals');
        }else
        {
            $(".totalLose").addClass('hideTotalLose');
            $("#authorityToGarage").removeClass('hideTotals');
        }
    });
    $("body").on('click','#claimExceptionDetail',function (e){
        e.preventDefault();
        var claimID = $(this).data("id");;
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/adjuster/claimExceptionDetail',
            data: {claimID : claimID},

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $('.tooltipped').tooltip();
    $(".notification").on('click',function (e){
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/markNotification',
            data: {id : id},
            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#submitAssessment',function (e){
        e.preventDefault();
        var counter = $("#counter").val();
        var i;
        // Array
        var partsData = [];
        for(i =0 ; i<=counter; i++)
        {
            var vehiclePart = $("#vehiclePart_"+i);
            var quantity = $("#quantity_"+i);
            var total = $("#total_"+i);
            var cost = $("#partPrice_"+i);
            var contribution = $("#contribution_"+i);
            var discount = $("#discount_"+i);
            var remarks = $("#remarks_"+i);
            var category = $("#category_"+i);
            var partData = {vehiclePart : vehiclePart.val(),quantity : quantity.val(),total:total.val(),cost:cost.val(),contribution:contribution.val(),discount:discount.val(),remarks:remarks.val(),category: category.val()};
            partsData.push(partData);
        }
        var isDraft = $("#isDraft").is(':checked') ? 1 : 0;
        var drafted = $("#drafted");
        var assessmentType = $('input[name="assessmentType"]:checked');
        var assessmentID = $("#assessmentID");
        var total = $('#total');
        var labour = $('#labour');
        var paint = $('#painting');
        var miscellaneous = $('#miscellaneous');
        var primer = $('#2kprimer');
        var jigging = $('#jigging');
        var reconstruction = $('#reconstruction');
        var gas = $('#acgas');
        var welding = $('#weldinggas');
        var dam = $('#damkit');
        var bumper = $('#bumperfibre');
        var sumTotal = $("#sumTotal");
        var pav = $("#pav");
        var salvage = $("#salvage");
        var totalLoss = $("#total_loss");
        var note = CKEDITOR.instances['notes'].getData();
        var cause = CKEDITOR.instances['cause'].getData();
        var jobsData = {
            total : total.val(),
            labour : labour.val(),
            paint : paint.val(),
            miscellaneous : miscellaneous.val(),
            primer : primer.val(),
            jigging : jigging.val(),
            reconstruction : reconstruction.val(),
            gas : gas.val(),
            welding : welding.val(),
            dam : dam.val(),
            bumper : bumper.val(),
            sumTotal : sumTotal.val(),
            pav : pav.val(),
            salvage : salvage.val(),
            totalLoss : totalLoss.val(),
            cause : cause,
            note : note
        };

        var image_upload = new FormData();
        // Attach file
        // formData.append('image', $('input[type=file]')[0].files[0]);
        if(drafted.val() != 1) {
            var files = $('input[type=file]')[0].files;
            let totalImages = files.length; //Total Images
            let images = $('input[type=file]')[0];
            for (let i = 0; i < totalImages; i++) {
                image_upload.append('images' + i, images.files[i]);
            }
            image_upload.append('totalImages', totalImages);
            image_upload.append('assessmentID', assessmentID.val());
            image_upload.append('assessmentType', assessmentType.val());
            image_upload.append('isDraft', isDraft);
            image_upload.append('drafted', drafted.val());
            image_upload.append('jobsData', JSON.stringify(jobsData));
            image_upload.append('partsData', JSON.stringify(partsData));
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
                url: '/assessor/submitAssessment',
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
        }else
        {
            var imagesArray = $("#imagesArray").val();
            var imageParse = JSON.parse(imagesArray);
            var counter = 0;

            var imgArray = [];

            $(".uploaded-image img").each(function() {
                var imgsrc = this.src;
                var n = imgsrc.split("/");
                var result = n[n.length - 1];
                imgArray.push(result);
            });
            console.log(imgArray);
            imageParse = imageParse.filter(function( obj ) {
                return imgArray.includes(obj.name);
            });

            console.log(imageParse);

            var files = $('input[type=file]')[0].files;
            let totalImages = files.length; //Total Images
            let images = $('input[type=file]')[0];
            var img = [];
            for (let i = 0; i < totalImages; i++) {
                var increment = imageParse.length+i;
                image_upload.append('images' + increment, images.files[i]);
            }
            $.each(imageParse, function (key, value) {
                async function createFile() {
                    let response = await fetch('http://127.0.0.1:8001/documents/' + value.name);
                    let data = await response.blob();
                    let metadata = {
                        type: 'image/jpeg'
                    };
                    let file = new File([data],value.name, metadata);
                    img.push(file);
                }
                createFile();
                counter++;
            });
            setTimeout(function () {
                for (let i = 0; i < img.length; i++) {
                    image_upload.append('images' + i, img[i]);

                }
                image_upload.append('totalImages', imageParse.length+totalImages);
                image_upload.append('assessmentID', assessmentID.val());
                image_upload.append('assessmentType', assessmentType.val());
                image_upload.append('isDraft', isDraft);
                image_upload.append('drafted', drafted.val());
                image_upload.append('jobsData', JSON.stringify(jobsData));
                image_upload.append('partsData', JSON.stringify(partsData));
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
                    url: '/assessor/submitAssessment',
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
            },1000);
        }
    });
    $("body").on('click','#submitReinspection',function (e){
        e.preventDefault();
        var counter = $("#counter").val();
        var i;
        // Array
        var partsData = [];
        for(i =0 ; i<=counter; i++)
        {
            var vehiclePart = $("#vehiclePart_"+i);
            var quantity = $("#quantity_"+i);
            var total = $("#total_"+i);
            var cost = $("#partPrice_"+i);
            var contribution = $("#contribution_"+i);
            var discount = $("#discount_"+i);
            var remarks = $("#remarks_"+i);
            var category = $("#category_"+i);
            var partData = {vehiclePart : vehiclePart.val(),quantity : quantity.val(),total:total.val(),cost:cost.val(),contribution:contribution.val(),discount:discount.val(),remarks:remarks.val(),category: category.val()};
            partsData.push(partData);
        }
        var isDraft = $("#isDraft").is(':checked') ? 1 : 0;
        var drafted = $("#drafted");
        var jobsData = {
            total : total.val()
        };

        var image_upload = new FormData();
        // Attach file
        // formData.append('image', $('input[type=file]')[0].files[0]);
        var files = $('input[type=file]')[0].files;
        let totalImages = files.length; //Total Images
        let images = $('input[type=file]')[0];
        for (let i = 0; i < totalImages; i++) {
            image_upload.append('images' + i, images.files[i]);
        }
        image_upload.append('totalImages', totalImages);
        image_upload.append('assessmentID', assessmentID.val());
        image_upload.append('assessmentType', assessmentType.val());
        image_upload.append('isDraft', isDraft);
        image_upload.append('drafted', drafted.val());
        image_upload.append('jobsData',JSON.stringify(jobsData));
        image_upload.append('partsData',JSON.stringify(partsData));
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
            url: '/assessor/submitAssessment',
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
    $('select').formSelect();
    $("body").on('click','#uploadDocuments',function (e) {
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
        image_upload.append('totalImages', totalImages);
        image_upload.append('claimID', claimID);
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
            url: '/assessor/uploadDocuments',
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

    $("body").on('click','#triggerApprove',function (e){
        e.preventDefault();
        const elem = document.getElementById('approve');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click','#triggerDiscount',function (e){
        e.preventDefault();
        const elem = document.getElementById('discount');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click','#triggerChangeRequests',function (e){
        e.preventDefault();
        const elem = document.getElementById('changeRequest');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click','#reviewAssessment',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var assessmentReviewType = $("input[name='assessmentReviewType']:checked").val();
        var report = CKEDITOR.instances['report'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                assessmentReviewType : assessmentReviewType,
                report : report
            },
            url: '/assessment-manager/review-assessment',
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
    $("body").on('click','#review-head-assessor-assessment',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var assessmentReviewType = $("input[name='assessmentReviewType']:checked").val();
        var report = CKEDITOR.instances['report'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                assessmentReviewType : assessmentReviewType,
                report : report
            },
            url: '/head-assessor/review-assessment',
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
});

function isNotEmpty(caller) {
    if (caller.val() == '') {
        caller.css('borderBottom', '3px solid red');
        return false
    } else {
        caller.css('borderBottom', '');
        return true
    }
}

function assignAssessor(id) {
    var claimID = $("#claimID" + id);
    var assessor = $("#assessor" + id);
    if (assessor.val() != '') {
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

        });
        $.ajax({

            type: 'POST',

            url: '/head-assessor/assignAssessor',

            data: {
                claimID: claimID.val(),
                assessor: assessor.val()
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
    } else {
        Swal.fire({
            icon: 'error',
            title: "You have to select an assessor before proceeding",
            showConfirmButton: false,
            timer: 3000
        })
    }
}

function reAssignAssessor(id) {
    var claimID = $("#claimID" + id);
    var assessor = $("#assessor" + id);
    if (assessor.val() != '') {
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

        });
        $.ajax({

            type: 'POST',

            url: '/head-assessor/reAssignAssessor',

            data: {
                claimID: claimID.val(),
                assessor: assessor.val()
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
    } else {
        Swal.fire({
            icon: 'error',
            title: "You have to select an assessor before proceeding",
            showConfirmButton: false,
            timer: 3000
        })
    }
}

function resizeStepper() {
    newHeight = 0;
    padding   = 200;

    $('#stepper').find('.step.active').find('.step-content > div').each(function()
    {
        newHeight += parseInt($(this).css('height'));
    });

    newHeight += padding;

    $('#stepper').animate({
            height: newHeight},
        300);
}
var count= 1;
function addMore() {
    $(".dynamicVehiclePart:last").clone().insertAfter(".dynamicVehiclePart:last");
    $('.dynamicVehiclePart').find('select').attr('id', 'select_'+count);
    $('.dynamicVehiclePart').find('input').attr('name', 'quantity_'+count)
    count++;
}
function deletePart() {
    $('.dynamicVehiclePart').each(function(index, item){
        jQuery(':checkbox', this).each(function () {
            if ($(this).is(':checked')) {
                $(item).remove();
            }
        });
    });
}
function resizeStepper() {
    newHeight = 0;
    padding   = 200;

    $('#stepper').find('.step.active').find('.step-content > div').each(function()
    {
        newHeight += parseInt($(this).css('height'));
    });

    newHeight += padding;

    $('#stepper').animate({
            height: newHeight},
        300);
}
$(window).load(function()
{
    resizeStepper();
});
$('#stepper').on('stepchange', function()
{
    resizeStepper();
});
