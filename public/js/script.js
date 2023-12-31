const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;
$(document).ready(function () {

    $("#policyRenewlLink").on('click', function (e) {

       e.preventDefault();
       $.ajaxSetup({

           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
       $.ajax({

           type: 'GET',

           url: '/policy-renewals',

           success: function (data) {

            //    $("#main").html(data);
               // $('.datepicker').datepicker();
               // $('#data-table-simple').DataTable({
               //     dom: 'Bfrtip',
               //     buttons: [
               //         'copy', 'csv', 'excel', 'pdf', 'print'
               //     ],
               //     "pageLength": 25
               // });
           }

       });

   });
    $("#main").on("click","#validateStepOne",function (){
        var validModel = $("#validModel").val();
        if(validModel == '')
        {
            $("#modelLabel").addClass('error');
        }else
        {
            $("#modelLabel").removeClass('error');
        }
    });
    $(".toggle-fullscreen").click(function() {
        document.fullScreenElement && null !== document.fullScreenElement || !document.mozFullScreen && !document.webkitIsFullScreen ? document.documentElement.requestFullScreen ? document.documentElement.requestFullScreen() : document.documentElement.mozRequestFullScreen ? document.documentElement.mozRequestFullScreen() : document.documentElement.webkitRequestFullScreen ? document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : document.documentElement.msRequestFullscreen && (document.msFullscreenElement ? document.msExitFullscreen() : document.documentElement.msRequestFullscreen()) : document.cancelFullScreen ? document.cancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen && document.webkitCancelFullScreen();
    });
    $('.materialboxed').materialbox();
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
        var garageID = $("#garageID");
        var originalSumInsured = $("#originalSumInsured");
        var originalExcess = $("#originalExcess");
        var carMakeCode = $("#carMakeCode");
        var carModelCode = $("#carModelCode");
        var yom = $("#yom");
        var engineNumber = $("#engineNumber");
        var chassisNumber = $("#chassisNumber");
        var safRegex=/^(?:(?:254|0|)(?:(?:(?:11(?:[0-5]))(?:[0-9]{6})|(?:(?:7|2)(?:(?:(?:[0-2]{1})(?:[0-9]{7}))|(?:(?:4(?:[0-35-68]{1}))|(?:57|58|59)|(?:68|69)|(?:9(?:[0-35-9]{1})))(?:[0-9]{6}))))))$/i;
        var airtelRegex=/^((?:254|0|)(?:(?:[7](?:[38]{1}(?:[0-9]{7})|(?:(?:(?:5(?:[0-6]{1}))|(?:[6][2]{1}))(?:[0-9]{6})))|(?:(?:10(?:[0-5])))(?:[0-9]{6}))))$/i;
        var telkomRegex=/^(25477|020|040|050|060|066)([0-9]{7})$/i;
        if(garageID.val() != '')
        {
            MSISDN = MSISDN.val();
            MSISDN= MSISDN.replace(/\s+/g, '');
            var firstChar = MSISDN[0];
            if (firstChar == '+') {
                MSISDN = MSISDN.substr(1);
            }
            var code = MSISDN.substring(0, 3);
            if(firstChar != 0 && code !=254)
            {
                MSISDN = "0"+MSISDN;
            }
            if(safRegex.test(MSISDN) || airtelRegex.test(MSISDN) || telkomRegex.test(MSISDN))
            {
                if(excess.val() >= originalExcess.val()) {
                    addLoadingButton();

                    $.ajaxSetup({

                        headers: {

                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                        }

                    });
                    $.ajax({

                        type: 'POST',

                        url: '/adjuster/addClaim',

                        data: {
                            claimNo: claimNo.val(),
                            customerCode: customerCode.val(),
                            claimType: claimType.val(),
                            sumInsured: sumInsured.val(),
                            excess: excess.val(),
                            vehicleRegNo: vehicleRegNo.val(),
                            policyNo: policyNo.val(),
                            branch: branch.val(),
                            loseDate: loseDate.val(),
                            intimationDate: intimationDate.val(),
                            email: email.val(),
                            fullName: fullName.val(),
                            MSISDN: MSISDN,
                            garageID: garageID.val(),
                            originalExcess: originalExcess.val(),
                            originalSumInsured: originalSumInsured.val(),
                            carMakeCode: carMakeCode.val(),
                            carModelCode: carModelCode.val(),
                            yom: yom.val(),
                            engineNumber: engineNumber.val(),
                            chassisNumber: chassisNumber.val()
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
                            removeLoadingButton();
                        }

                    });
                }else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Excess must be greater than '+originalExcess.val(),
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            }else
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Provide a valid Mobile Number',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'You have not the specified Garage',
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
        var garageID = $("#garageID");
        var claimID = $("#claimID");
        var oldExcess = $("#oldExcess");
        var oldSumInsured = $("#oldSumInsured");
        var image_upload = new FormData();

        var imagesArray = $("#imagesArray").val();
        if(excess.val() >= oldExcess.val()) {
            if (typeof imagesArray === 'undefined') {

            } else {
                var imageParse = JSON.parse(imagesArray);
                var counter = 0;

                var imgArray = [];

                $(".uploaded-image img").each(function () {
                    var imgsrc = this.src;
                    var n = imgsrc.split("/");
                    var result = n[n.length - 1];
                    imgArray.push(result);
                });
                imageParse = imageParse.filter(function (obj) {
                    return imgArray.includes(obj.name);
                });

                console.log(imageParse);

                var files = $('input[type=file]')[0].files;
                var totalImages = files.length; //Total Images
                let images = $('input[type=file]')[0];
                var img = [];
                for (let i = 0; i < totalImages; i++) {
                    var increment = imageParse.length + i;
                    image_upload.append('images' + increment, images.files[i]);
                }
                $.each(imageParse, function (key, value) {
                    async function createFile() {
                        let response = await fetch('/documents/' + value.name);
                        let data = await response.blob();
                        let metadata = {
                            type: 'image/jpeg'
                        };
                        let file = new File([data], value.name, metadata);
                        img.push(file);
                    }

                    createFile();
                    counter++;
                });
            }
            setTimeout(function () {
                if (typeof imagesArray === 'undefined') {
                    image_upload.append('totalImages', '');
                } else {
                    for (let i = 0; i < img.length; i++) {
                        image_upload.append('images' + i, img[i]);

                    }
                    image_upload.append('totalImages', imageParse.length + totalImages);
                }
                image_upload.append('sumInsured', sumInsured.val());
                image_upload.append('excess', excess.val());
                image_upload.append('garageID', garageID.val());
                image_upload.append('claimID', claimID.val());
                image_upload.append('oldExcess', oldExcess.val());
                image_upload.append('oldSumInsured', oldSumInsured.val());
                $.ajaxSetup({

                    headers: {

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                });
                addLoadingButton();

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
                        removeLoadingButton();
                    }

                });
            }, 1000);
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'Excess must be greater than '+oldExcess.val(),
                showConfirmButton: false,
                timer: 3000
            })
        }
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
        $("#mainLoader").removeClass('hideLoader');
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
                $("#mainLoader").addClass('hideLoader');
            }

        });
    });
    $("body").on('click','#headAssessorClaims',function (e){
        e.preventDefault();
        $("#mainLoader").removeClass('hideLoader');
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
    $("body").on('click','.manager-claims',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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

            url: '/manager/claims',

            success: function (data) {
                $("#main").html(data);
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
    $("body").on('click','.assistant-head-assessor-claims',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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

            url: '/assistant-head-assessor/claims',

            success: function (data) {
                $("#main").html(data);
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
    $("body").on('click','#filter-assessment-manager-claims,.assessment-manager-claims',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $("#mainLoader").removeClass('hideLoader');
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
                regNumber : regNumber
            },

            url: '/assessment-manager/claims',

            success: function (data) {
                $("#main").html(data);
                $('#data-table-simple').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "pageLength": 25
                });
                $('.datepicker').datepicker();
                $("#mainLoader").addClass('hideLoader');
            }

        });
    });
    $("body").on('click','.head-assessor-claims',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
    $("body").on('click','#assessorAssessments',function (e){
        e.preventDefault();
        $("#mainLoader").removeClass('hideLoader');
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
    $("body").on('click','.assessment-manager-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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


    $("body").on('click','#user_switch',function (){
        var status=$(this).prop("checked");
        var id=$("#u_id").text();



        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
         });
         $.ajax({
         type:"POST",
         url:'/admin/set-status',
         data:{
         status:status,
         id:id
         },
         success:function(data){
          if(data.status=="successful")
          {
          Swal.fire({
              icon: 'success',
              title: "Successful",
              showConfirmButton: false,
              timer: 3000
              })
          }

         },
         error:function(data){
//         var result = $.parseJSON(data);

         }


         });


    });



    $("#main").on('click','#filter-assessment-manager-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/assessment-manager/assessments',

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
    $("body").on('click','#fillAssessmentReport',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        var claimID = $(this).data('claimid');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',
            data : {
                claimID : claimID
            },
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
                // $('select').formSelect();
            }

        });
    });
    $("body").on('click','#fillSupplementaryReport',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/assessor/fillSupplementaryReport/'+id,

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
                var imagesCount = $("#imagesCount").val();
                var stepper = document.querySelector('.stepper');
                var stepperInstace = new MStepper(stepper, {
                    // options
                    firstActive: 0 // this is the default
                });
                var finalArray = [];
                if(imagesCount > 0) {
                    var imagesArray = $("#imagesArray").val();
                    $.each(JSON.parse(imagesArray), function (key, value) {
                        var imgData = {id: value.id, src: "documents/" + value.name}
                        finalArray.push(imgData);
                    });
                }
                $('.input-images').imageUploader({
                    label: "Drag & Drop Images here or click to browse",
                    preloaded: finalArray
                });
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
        $("#mainLoader").removeClass('hideLoader');
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
    $("body").on('click','#filter-theft-assessments,.fetch-theft-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/common/fetch-theft-assessments',

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
    $("#main").on('click','#filter-adjuster-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/adjuster/assessments',

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
    $("body").on('click','#filter-claims-by-type,.fetch-claims-by-type',function (e){
        e.preventDefault();
        var assessmentTypeID = $(this).data("id");
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'assessmentTypeID' : assessmentTypeID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/common/fetch-claims-by-type',

            success: function (data) {
                $("#main").html(data);
                $('#data-table-simple').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "pageLength": 25
                });
                $('.datepicker').datepicker();
                $("#mainLoader").addClass('hideLoader');
            }

        });
    });
    $("body").on('click','.fetch-claims',function (e){
        e.preventDefault();
        var claimStatusID = $(this).data("id");
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $("#mainLoader").removeClass('hideLoader');
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
                regNumber : regNumber
            },
            url: '/adjuster/fetch-claims',

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



    $(".fetch-claim-types").on('click',function (e){
        e.preventDefault();
        var claimType = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'claimType' : claimType
            },
            url: '/adjuster/fetch-claim-types',

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
    $("body").on('click','#filter-claim-types',function (e){
        e.preventDefault();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        var claimType = $("#claimType").val();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                claimType : claimType,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/adjuster/fetch-claim-types',

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
    $(".fetch-theft-claims").on('click',function (e){
        e.preventDefault();
        var claimType = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                'claimType' : claimType
            },
            url: '/common/fetch-theft-claims',

            success: function (data) {
                $("#main").html(data);
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
    $(".re-inspections").on('click',function (e){
        e.preventDefault();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({
            type: 'POST',
            url: '/common/fetch-re-inspections',

            success: function (data) {
                $("#main").html(data);
                $('.datepicker').datepicker();
                $('#data-table-simple').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "columnDefs": [
                        { 'visible': false, 'targets': [2] },
                        { 'visible': false, 'targets': [3] },
                        { 'visible': false, 'targets': [4] },
                        { 'visible': false, 'targets': [5] },
                        { 'visible': false, 'targets': [6] },
                        { 'visible': false, 'targets': [7] }
                    ],
                    "pageLength": 25
                });
                $("#mainLoader").addClass('hideLoader');
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
    $("#main").on('click','#filter-assessor-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/assessor/assessments',

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
    $(".assessor-fetch-supplementaries").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
            url: '/assessor/supplementaries',

            success: function (data) {
                $("#main").html(data);
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
    $(".head-assessor-fetch-supplementaries").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
            url: '/head-assessor/supplementaries',

            success: function (data) {
                $("#main").html(data);
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
    $(".assistant-head-assessor-fetch-supplementaries").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
            url: '/assistant-head-assessor/supplementaries',

            success: function (data) {
                $("#main").html(data);
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
    $(".adjuster-fetch-supplementaries").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
            url: '/adjuster/supplementaries',

            success: function (data) {
                $("#main").html(data);
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
    $(".manager-fetch-supplementaries").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
            url: '/manager/supplementaries',

            success: function (data) {
                $("#main").html(data);
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
    $(".assessment-manager-fetch-supplementaries").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
            url: '/assessment-manager/supplementaries',

            success: function (data) {
                $("#main").html(data);
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
    $(".head-assessor-assessments").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
    $("#main").on('click','#filter-head-assessor-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/head-assessor/assessments',

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
    $(".manager-assessments").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
            url: '/manager/assessments',

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
    $("#main").on('click','#filter-manager-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/manager/assessments',

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
    $(".assistant-head-assessor-assessments").on('click',function (e){
        e.preventDefault();
        var assessmentStatusID = $(this).data("id");
        $("#mainLoader").removeClass('hideLoader');
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
    $("#main").on('click','#filter-assistant-head-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/assistant-head-assessor/assessments',

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
                // $("#main").html(data);

                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#subrogate-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/common/subrogation-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);

                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#PTVReport',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/common/PTVReport',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);

                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
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
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
                // $("#main").html(data);
            }

        });
    });
    $("body").on('click','#reInspectionLetter',function (e){
        e.preventDefault();
        // var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/re-inspection-letter/'+assessmentID,

            success: function (data) {
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
                // $("#main").html(data);
            }

        });
    });
    $("body").on('click','#salvage-release-letter',function (e){
        e.preventDefault();
        var salvageRegisterID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'post',
            data: {
               salvageRegisterID : salvageRegisterID
            },
            url: '/common/salvage-release-letter',

            success: function (data) {
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
                // $("#main").html(data);
            }

        });
    });
    $("body").on('click','#viewLPOReport',function (e){
        e.preventDefault();
        var claimID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'post',
            data: {
                claimID : claimID
            },
            url: '/common/viewLPOReport',

            success: function (data) {
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
                // $("#main").html(data);
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
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#view-assessor-supplementary-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assessor/supplementary-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#view-head-assessor-supplementary-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/head-assessor/supplementary-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#view-assistant-head-assessor-supplementary-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assistant-head-assessor/supplementary-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#view-adjuster-supplementary-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/adjuster/supplementary-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#view-manager-supplementary-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/manager/supplementary-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#view-assessment-manager-supplementary-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assessment-manager/supplementary-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#view-assessor-assessment-report',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/assessor/edit-assessment-report/'+id,

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
    $("body").on('click','#edit-supplementary-report',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/assessor/edit-supplementary-report/'+id,

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
    $("body").on('click','#submit-edited-assessment',function (e){
        e.preventDefault();
        var counter = $('td input:checkbox').length;
        var partIDs=$("input[id*='checkbox_']");
        var vehicleParts=$("select[id*='vehiclePart_']");
        var quantitys=$("input[id*='quantity_']");
        var partPrices=$("input[id*='partPrice_']");
        var contributions=$("input[id*='contribution_']");
        var discounts=$("input[id*='discount_']");
        var totals=$("input[id*='total_']");
        var remarkss=$("select[id*='remarks_']");
        var categorys=$("select[id*='category_']");
        var claimID = $("#claimID").val();

        var i;
        var partsData = [];
        addLoadingButton();
        for(i =0 ; i<counter; i++)
        {
            var vehiclePart = $('#'+(vehicleParts[i].id)).val();
            var quantity = $('#'+(quantitys[i].id)).val();
            var total = $('#'+(totals[i].id)).val();

            var cost =$('#'+(partPrices[i].id)).val();

            var contribution = $('#'+(contributions[i].id)).val();
            var discount =$('#'+(discounts[i].id)).val();
            var remarks = $('#'+(remarkss[i].id)).val();
            var category = $('#'+(categorys[i].id)).val();
            var partData = {vehiclePart : vehiclePart,quantity : quantity.length > 0 ? quantity : 0,total:total.length > 0 ? total : 0,cost:cost.length > 0 ? cost : 0,contribution:contribution.length > 0 ? contribution : 0,discount:discount.length > 0 ? discount : 0,remarks:remarks,category: category};
            partsData.push(partData);
        }
        var isDraft = $("#isDraft").is(':checked') ? 1 : 0;
        var isScrap = $("#isScrap").prop("checked") == true ? 1 : 0;
        var scrapValue= $("#isScrap").prop("checked") == true ? $("#scrapValue").val():0;
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
        var sumTotal = $("#sumTotal");
        var pav = $("#PAV");
        var chassisNumber = $("#chassisNumber");
        var carMake = $("#carMake");
        var carModel = $("#carModel");
        var garageID = $("#garageID");
        var YOM = $("#YOM");
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
            scrapValue : scrapValue,
            isScrap : isScrap,
            sumTotal : sumTotal.val(),
            pav : pav.val(),
            chassisNumber : chassisNumber.val(),
            carMake : carMake.val(),
            carModel : carModel.val(),
            garageID : garageID.val(),
            YOM : YOM.val(),
            salvage : salvage.val(),
            totalLoss : totalLoss.val(),
            cause : cause,
            note : note
        };

        var image_upload = new FormData();
        if(drafted.val() != 1) {
            var files = $('input[type=file]')[0].files;
            let totalImages = files.length; //Total Images
            let images = $('input[type=file]')[0];
            for (let i = 0; i < totalImages; i++) {
                image_upload.append('images' + i, images.files[i]);
            }
            if($('#invoice').val()!=undefined){
                var invoice = $('#invoice').prop('files')[0];

                image_upload.append('invoice', invoice);
            }
            image_upload.append('totalImages', totalImages);
            image_upload.append('assessmentID', assessmentID.val());
            image_upload.append('assessmentType', assessmentType.val());
            image_upload.append('isDraft', isDraft);
            image_upload.append('claimID', claimID);
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
                url: '/assessor/submit-edited-assessment',
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
                    removeLoadingButton();
                }

            });
        }else
        {

            var files = $('input[type=file]')[0].files;
            let totalImages = files.length; //Total Images
            let images = $('input[type=file]')[0];
            if(totalImages>0) {
                for (let i = 0; i < totalImages; i++) {
                    image_upload.append('images' + i, images.files[i]);
                }
            }
            setTimeout(function () {
                if($('#invoice').val()!=undefined){
                    var invoice = $('#invoice').prop('files')[0];

                    image_upload.append('invoice', invoice);
                }
                image_upload.append('totalImages', totalImages);
                image_upload.append('claimID', claimID);
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
                    url: '/assessor/submit-edited-assessment',
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
                        removeLoadingButton();
                    }

                });
            },1000);
        }
    });
    $("body").on('click','#submit-edited-supplementary',function (e){
        e.preventDefault();
        // var counter = $("#counter").val();
        var counter = $('td input:checkbox').length;
        var vehicleParts=$("select[id*='vehiclePart_']");
        var quantitys=$("input[id*='quantity_']");
        var partPrices=$("input[id*='partPrice_']");
        var contributions=$("input[id*='contribution_']");
        var discounts=$("input[id*='discount_']");
        var totals=$("input[id*='total_']");
        var remarkss=$("select[id*='remarks_']");
        var categorys=$("select[id*='category_']");
        var claimID = $("#claimID");
        addLoadingButton();
        var i;
        // Array
        var partsData = [];
        for(i =0 ; i<counter; i++)
        {



            var vehiclePart = $('#'+(vehicleParts[i].id)).val();

            var quantity = $('#'+(quantitys[i].id)).val();
            console.log('quantity.val()');
            console.log(quantity);
            console.log('quantity.val()');
            var total = $('#'+(totals[i].id)).val();


            var cost =$('#'+(partPrices[i].id)).val();

            var contribution = $('#'+(contributions[i].id)).val();
            var discount =$('#'+(discounts[i].id)).val();
            var remarks = $('#'+(remarkss[i].id)).val();
            var category = $('#'+(categorys[i].id)).val();

            var partData = {vehiclePart : vehiclePart,quantity : quantity.length > 0 ? quantity : 0,total:total.length > 0 ? total : 0,cost:cost.length > 0 ? cost : 0,contribution:contribution.length > 0 ? contribution : 0,discount:discount.length > 0 ? discount : 0,remarks:remarks,category: category};
            partsData.push(partData);
        }
        // alert(JSON.stringify(partsData));
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
        var sumTotal = $("#sumTotal");
        var pav = $("#PAV");
        var chassisNumber = $("#chassisNumber");
        var salvage = $("#salvage");
        var totalLoss = $("#total_loss");
        var note = CKEDITOR.instances['notes'].getData();
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
            sumTotal : sumTotal.val(),
            pav : pav.val(),
            chassisNumber : chassisNumber.val(),
            salvage : salvage.val(),
            totalLoss : totalLoss.val(),
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
            image_upload.append('claimID', claimID.val());
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
                url: '/assessor/submit-edited-supplementary',
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
                    removeLoadingButton();
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
                    let response = await fetch('/documents/' + value.name);
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
                image_upload.append('claimID', claimID.val());
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
                    url: '/assessor/submit-edited-supplementary',
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
                        removeLoadingButton();
                    }

                });
            },1000);
        }
    });

    $("body").on('click','#assessor-view-re-inspection-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assessor/re-assessment-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
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
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#manager-assessment-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/manager/assessment-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#assistant-head-assessor-assessment-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assistant-head-assessor/assessment-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
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
    $('body').on('keyup','.current',function (){
        var t=$(this).attr('id');
        var val=t.split("_").pop();
        var quantity="quantity_"+val;
        var partPrice="partPrice_"+val;
        var current="current_"+val;
        var difference="difference_"+val;
        var contribution = "contribution_"+val;
        var currenttotal = "currenttotal_"+val;
        var discount = "discount_"+val;
        var total = "total_"+val;
        var totalDiff = "totaldifference_"+val;
        var diff=parseInt($('#'+current).val())-parseInt($('#'+partPrice).val());
        if($('#'+contribution).val() > 0)
        {
            diff = diff*((100 - $('#'+contribution).val())/100);
        }
        if($('#'+discount).val() > 0)
        {
            diff = diff*((100 - $('#'+discount).val())/100);
        }

        if(!($('#'+current).val()))
        {

            $('#'+difference).val('');
        }else{
            $('#'+difference).val(diff);
        }
        var totalDifference = diff*parseInt($('#'+quantity).val())
        $("#"+totalDiff).val(totalDifference);
        $('#'+currenttotal).val((diff*parseInt($('#'+quantity).val()))+parseInt($('#'+total).val()));




    });
    $("body").on('click','#submit-price-change',function (e){
        e.preventDefault();
        var counter = $('td.checks').length;
        var i;
        // Array
        var partsData = [];
        for(i =0 ; i<counter; i++)
        {
            var vehiclePart = $('.vehiclePart_'+i).val();
            var difference = $('#difference_'+i).val();
            var partsID=$("input[class*='vehiclePart_']");
            var partID=partsID[i].id;
            var quantity = $('#quantity_'+i).val();
            var current = $('#current_'+i).val();
            var totalDifference = $('#totaldifference_'+i).val();
            var assessmentID = $("#assessmentID").val()
            // var partData = {quantity : quantity.length > 0 ? quantity : 0,costcost : cost.length > 0 ? cost : 0,current:current.length > 0 ? current : 0,remarks:remarks,vehicleParts:vehiclePart,assessmentID:assessmentID,partID:partID,difference : difference.length > 0 ? difference : 0};
            // var partData = {current:current.length > 0 ? current : 0,assessmentID:assessmentID,partID:partID,difference:difference.length > 0 ? difference : 0 };
            var partData = {
                current: current.length > 0 ? current : 0,
                partID: partID,
                difference: difference.length > 0 ? difference : 0,
                quantity : quantity,
                totalDifference : totalDifference
            };
            partsData.push(partData);
        }
        var image_upload = new FormData();
        image_upload.append('partsData', JSON.stringify(partsData));
        // var isDraft = $("#isDraft").is(':checked') ? 1 : 0;
        // var drafted = $("#drafted");
        // var assessmentType = $('input[name="assessmentType"]:checked');
        var assessmentID = $("#assessmentID");
        var drafted = $("#drafted");
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
            // image_upload.append('assessmentType', assessmentType.val());
            // image_upload.append('isDraft', isDraft);
            image_upload.append('drafted', drafted.val());
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
                url: '/assessor/submitPriceChange',
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
                    let response = await fetch('/documents/' + value.name);
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
                // image_upload.append('isDraft', isDraft);
                // image_upload.append('drafted', drafted.val());
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
                    url: '/assessor/submitPriceChange',
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
    $("body").on('click','#assessor-price-change',function (e){
        e.preventDefault();
        var id = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/assessor/view-price-change/'+id,

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

                });
                $('select').formSelect();
            }

        });
    });

    $("body").on('click','#head-assessor-view-price-change',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/head-assessor/price-change-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#assistant-head-assessor-view-price-change',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assistant-head-assessor/price-change-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#assessor-view-price-change',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assessor/price-change-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
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
    $("body").on('click','#assignPermission',function (e){
        e.preventDefault();
        var userID = $("#userID").val();
        var permissions = [];
        $('input[name="permissions[]"]:checked').each(function() {
            permissions.push(this.value);
        });
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/assignPermission',

            data: {
                permissions : permissions,
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
    $("body").on('click','#fetchPermissions',function (e){
        e.preventDefault();
        var userID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/permissions',
            data : {
                userID : userID
            },
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
    $(".listParts").on('click',function (e){
        e.preventDefault();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/listParts',
            success: function (data) {
                $("#main").html(data);
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
    $("body").on('click','.fetch-vendors',function (e){
        e.preventDefault();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/fetch-vendors',
            success: function (data) {
                $("#main").html(data);
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
    $("body").on('click','#addVendorForm',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/addVendorForm',
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
    $("body").on('click','#addVendor',function (e){
        e.preventDefault();
        var firstName = $("#firstName");
        var lastName = $("#lastName");
        var email = $("#email");
        var MSISDN = $("#MSISDN");
        var idNumber = $("#idNumber");
        var kraPin = $("#kraPin");
        var location = $("#location");
        var vendorType = $("#vendorType");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'post',
            data : {
                firstName : firstName.val(),
                lastName : lastName.val(),
                email : email.val(),
                MSISDN : MSISDN.val(),
                idNumber : idNumber.val(),
                kraPin : kraPin.val(),
                location : location.val(),
                vendorType : vendorType.val()
            },
            url: '/admin/addVendor',
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
    $("body").on("click",'#searchClaim',function (){
        $("#mainLoader").removeClass('hideLoader');
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
                $("#mainLoader").addClass('hideLoader');
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
        var claimID = $(this).data("id");
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
        console.log('1');
        var counter = $('td input:checkbox').length;
        var vehicleParts=$("select[id*='vehiclePart_']");
        var quantitys=$("input[id*='quantity_']");
        var partPrices=$("input[id*='partPrice_']");
        var contributions=$("input[id*='contribution_']");
        var discounts=$("input[id*='discount_']");
        var totals=$("input[id*='total_']");
        var remarkss=$("select[id*='remarks_']");
        var categorys=$("select[id*='category_']");
        var claimID = $("#claimID").val();
        var isScrap = $("#isScrap").is(':checked') ? 1 : 0;
        var scrapValue= $("#isScrap").prop("checked") == true ? $("#scrapValue").val() : 0;

        addLoadingButton();
        var i;
        var partsData = [];
        for(i =0 ; i<counter; i++)
        {
            var vehiclePart = $('#'+(vehicleParts[i].id)).val();
            var quantity = $('#'+(quantitys[i].id)).val();
            var total = $('#'+(totals[i].id)).val();
            var cost =$('#'+(partPrices[i].id)).val();
            var contribution = $('#'+(contributions[i].id)).val();
            var discount =$('#'+(discounts[i].id)).val();
            var remarks = $('#'+(remarkss[i].id)).val();
            var category = $('#'+(categorys[i].id)).val();
            var partData = {vehiclePart : vehiclePart,quantity : quantity.length > 0 ? quantity : 0,total:total.length > 0 ? total : 0,cost:cost.length > 0 ? cost : 0,contribution:contribution.length > 0 ? contribution : 0,discount:discount.length > 0 ? discount : 0,remarks:remarks,category: category};
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
        var sumTotal = $("#sumTotal");
        var pav = $("#PAV");
        var chassisNumber = $("#chassisNumber");
        var carMake = $("#carMake");
        var carModel = $("#carModel");
        var garageID=$("#garageID");
        var YOM = $("#YOM");
        var salvage = $("#salvage");
        var totalLoss = $("#total_loss");
        var note = CKEDITOR.instances['notes'].getData();
        var cause = CKEDITOR.instances['cause'].getData();
        var assessmentStatusID = $(this).data("id");
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
            scrapValue : scrapValue,
            isScrap : isScrap,
            sumTotal : sumTotal.val(),
            pav : pav.val(),
            chassisNumber : chassisNumber.val(),
            carMake : carMake.val(),
            carModel : carModel.val(),
            garageID : garageID.val(),
            YOM : YOM.val(),
            salvage : salvage.val(),
            totalLoss : totalLoss.val(),
            cause : cause,
            note : note
        };

        var image_upload = new FormData();
        console.log('2');
        if(drafted.val() != 1) {
            console.log('3');
            var files = $('input[type=file]')[0].files;
            let totalImages = files.length; //Total Images
            let images = $('input[type=file]')[0];
            for (let i = 0; i < totalImages; i++) {
                image_upload.append('images' + i, images.files[i]);
            }

            if($('#invoice').val()!=undefined){
                var invoice = $('#invoice').prop('files')[0];

                image_upload.append('invoice', invoice);
            }
            image_upload.append('totalImages', totalImages);
            image_upload.append('assessmentID', assessmentID.val());
            image_upload.append('claimID', claimID);
            image_upload.append('assessmentType', assessmentType.val());
            image_upload.append('isDraft', isDraft);
            image_upload.append('drafted', drafted.val());
            image_upload.append('jobsData', JSON.stringify(jobsData));
            image_upload.append('partsData', JSON.stringify(partsData));
            console.log('4');
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
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: result.STATUS_MESSAGE,
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                    removeLoadingButton();
                }

            });
        }else
        {

            var files = $('input[type=file]')[0].files;
            let totalImages = files.length; //Total Images
            let images = $('input[type=file]')[0];
            // var img = [];
            if(totalImages>0) {
                for (let i = 0; i < totalImages; i++) {
                    image_upload.append('images' + i, images.files[i]);
                }
            }

            setTimeout(function () {
                if($('#invoice').val()!=undefined){
                    var invoice = $('#invoice').prop('files')[0];

                    image_upload.append('invoice', invoice);
                }
                image_upload.append('totalImages', totalImages);
                image_upload.append('assessmentID', assessmentID.val());
                image_upload.append('claimID', claimID);
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
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: result.STATUS_MESSAGE,
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                        removeLoadingButton();
                    }

                });
            },1000);
        }
    });
    $("body").on('click','#submitSupplementary',function (e){
        e.preventDefault();
        addLoadingButton();
        var assessmentStatusID = $(this).data("id");
        // var counter = $("#counter").val();
        var counter = $('td input:checkbox').length;

        var vehicleParts=$("select[id*='vehiclePart_']");
        var quantitys=$("input[id*='quantity_']");
        var partPrices=$("input[id*='partPrice_']");
        var contributions=$("input[id*='contribution_']");
        var discounts=$("input[id*='discount_']");
        var totals=$("input[id*='total_']");
        var remarkss=$("select[id*='remarks_']");
        var categorys=$("select[id*='category_']");

        var i;
        // Array
        var partsData = [];
        for(i =0 ; i<counter; i++)
        {

            var vehiclePart = $('#'+(vehicleParts[i].id)).val();
            var quantity = $('#'+(quantitys[i].id)).val();
            console.log('quantity.val()');
            console.log(quantity);
            console.log('quantity.val()');
            var total = $('#'+(totals[i].id)).val();
            var cost =$('#'+(partPrices[i].id)).val();
            var contribution = $('#'+(contributions[i].id)).val();
            var discount =$('#'+(discounts[i].id)).val();
            var remarks = $('#'+(remarkss[i].id)).val();
            var category = $('#'+(categorys[i].id)).val();
            var partData = {vehiclePart : vehiclePart,quantity : quantity.length > 0 ? quantity : 0,total:total.length > 0 ? total : 0,cost:cost.length > 0 ? cost : 0,contribution:contribution.length > 0 ? contribution : 0,discount:discount.length > 0 ? discount : 0,remarks:remarks,category: category};
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
        var sumTotal = $("#sumTotal");
        var pav = $("#PAV");
        var chassisNumber = $("#chassisNumber");
        var salvage = $("#salvage");
        var totalLoss = $("#total_loss");
        var note = CKEDITOR.instances['notes'].getData();
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
            sumTotal : sumTotal.val(),
            pav : pav.val(),
            chassisNumber : chassisNumber.val(),
            salvage : salvage.val(),
            totalLoss : totalLoss.val(),
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
                url: '/assessor/submitSupplementary',
                success: function (data) {
                    var result = $.parseJSON(data);
                    if (result.STATUS_CODE == SUCCESS_CODE) {
                        Swal.fire({
                            icon: 'success',
                            title: result.STATUS_MESSAGE,
                            showConfirmButton: false,
                            timer: 3000
                        });
                        $.ajax({

                            type: 'POST',
                            data : {
                                'assessmentStatusID' : assessmentStatusID
                            },
                            url: '/assessor/supplementaries',

                            success: function (data) {
                                $("#main").html(data);
                            }

                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: result.STATUS_MESSAGE,
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                    removeLoadingButton();
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
                    let response = await fetch('/documents/' + value.name);
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
                    url: '/assessor/submitSupplementary',
                    success: function (data) {
                        var result = $.parseJSON(data);
                        if (result.STATUS_CODE == SUCCESS_CODE) {
                            Swal.fire({
                                icon: 'success',
                                title: result.STATUS_MESSAGE,
                                showConfirmButton: false,
                                timer: 3000
                            });
                            $.ajax({

                                type: 'POST',
                                data : {
                                    'assessmentStatusID' : assessmentStatusID
                                },
                                url: '/assessor/supplementaries',

                                success: function (data) {
                                    $("#main").html(data);
                                }

                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: result.STATUS_MESSAGE,
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                        removeLoadingButton();
                    }

                });
            },1000);
        }
    });
    $("body").on('click','#submitReinspection',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID");
        var additionalLabour = $("#additionalLabour").val();
        var lessLabour = $("#lessLabour").val();
        var inspectionID = $("#inspectionID").val();
        var notes = CKEDITOR.instances['notes'].getData();
        var assessmentIds = $("#assessmentIds").val();
        var repaired = [];
        var replaced = [];
        var cil = [];
        var reused = [];
        addLoadingButton();
        $.each($("input[name='repaired[]']:checked"), function(){
            repaired.push($(this).val());
        });
        $.each($("input[name='replacePart[]']:checked"), function(){
            replaced.push($(this).val());
        });
        $.each($("input[name='cil[]']:checked"), function(){
            cil.push($(this).val());
        });
        $.each($("input[name='reused[]']:checked"), function(){
            reused.push($(this).val());
        });
        var image_upload = new FormData();
        if(inspectionID == 0) {
            var files = $('input[type=file]')[0].files;
            var totalImages = files.length; //Total Images
            let images = $('input[type=file]')[0];
            for (let i = 0; i < totalImages; i++) {
                image_upload.append('images' + i, images.files[i]);
            }
            image_upload.append('totalImages', totalImages);
        }else
        {

            var files = $('input[type=file]')[0].files;
            var totalImages = files.length;
            let images = $('input[type=file]')[0];

            for (let i = 0; i < totalImages; i++) {
                image_upload.append('images' + i, images.files[i]);
            }
        }
        setTimeout(function () {
            if (inspectionID == 0) {
                image_upload.append('totalImages', totalImages);
            } else {
                image_upload.append('totalImages',  totalImages);
            }
            image_upload.append('assessmentID', assessmentID.val());
            image_upload.append('repaired',JSON.stringify(repaired));
            image_upload.append('replaced',JSON.stringify(replaced));
            image_upload.append('cil',JSON.stringify(cil));
            image_upload.append('reused',JSON.stringify(reused));
            image_upload.append('add_labor',additionalLabour);
            image_upload.append('labor',lessLabour);
            image_upload.append('notes',notes);
            image_upload.append('assessmentIds',assessmentIds);
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
                url: '/assessor/submitReInspection',
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
                    removeLoadingButton();
                }

            });
        }, 1000);
    });
    $('select').formSelect();
    $("body").on('click','#uploadDocuments',function (e) {
        e.preventDefault();
        var claimID = $("#claimID").val();
        var isSubrogate = $(".subrogation").is(':checked') ? 1 : 0;
        var companyID = $("#company").val();
        var thirdPartyDriver = $("#thirdPartyDriver").val();
        var thirdPartyPolicy = $("#thirdPartyPolicy").val();
        var thirdPartyVehicleRegNo = $("#thirdPartyVehicleRegNo").val();
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
        image_upload.append('isSubrogate', isSubrogate);
        image_upload.append('companyID', companyID);
        image_upload.append('thirdPartyDriver', thirdPartyDriver);
        image_upload.append('thirdPartyPolicy', thirdPartyPolicy);
        image_upload.append('thirdPartyVehicleRegNo', thirdPartyVehicleRegNo);
        image_upload.append('claimForm', claimForm);
        if(isSubrogate == 0)
        {
            Swal.fire({
                title: "Are you sure the claim doesn't have subrogation?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                }
            }).then((result) => {
                if (result.isConfirmed) {
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
                }
            })
        }else
        {
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
        }
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
    $("body").on('click','#triggerAddpart',function (e){
        e.preventDefault();
        const elem = document.getElementById('addPartModal');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click','#triggerAddpermission',function (e){
        e.preventDefault();
        const elem = document.getElementById('addPermissionModal');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click','#triggerPTVModal',function (e){
        e.preventDefault();
        const elem = document.getElementById('PTVModal');
        const instance = M.Modal.init(elem, {dismissible: true});
        var assessmentID = $(this).data("id");
        $("#assessmentID").val(assessmentID);
        instance.open();
    });


    $("body").on('click','#user_status',function (e){
        e.preventDefault();

        var _this=$(this).parents('tr');


        var id=_this.find('.userID').text();

        $("#email").text(_this.find('.email').text());
        $("#u_id").text(_this.find('.userID').text());
        $("#sn").text(_this.find('.sn').text());
        $("#name").text(_this.find('.name').text());

        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

             $.ajax({

                        type: 'GET',
                        data: {
                            id : id
                        },
                        url: '/admin/fetch-user-status',
                        success: function (data) {
                                if(data.status=='1')
                                {
                                 $("#user_switch").prop("checked",true);
                                }else{
                                $("#user_switch").prop("checked",false

                                );
                                }

                               const elem = document.getElementById('userStatus');
                               const instance = M.Modal.init(elem, {dismissible: true});
                               instance.open();
                        }

                    });

    });

    $("body").on('click','#processSalvage',function (e){
        e.preventDefault();
        const elem = document.getElementById('processSalvageModal');
        const instance = M.Modal.init(elem, {dismissible: true});
        var claimID = $(this).data("id");
        $("#claimID").val(claimID);
        $('select').formSelect();
        $('.datepicker').datepicker();
        instance.open();
    });
    $("body").on('click','#triggerSaleSalvageModal',function (e){
        e.preventDefault();
        const elem = document.getElementById('saleSalvageModal');
        const instance = M.Modal.init(elem, {dismissible: true});
        var salvageID = $(this).data("id");
        $("#salvageID").val(salvageID);
        $('select').formSelect();
        instance.open();
    });
    $("body").on('click','#triggerNotification',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data: {
                assessmentID : assessmentID
            },
            url: '/common/getUsers',
            success: function (data) {
                const elem = document.getElementById('genericNotification');
                const instance = M.Modal.init(elem, {dismissible: true});
                instance.open();
                $("#users").html(data);
                $("#assessmentID").val(assessmentID);
                $('.multiple-emails').select2();
            }

        });
    });
    $("body").on('click','#triggerRepairAuthority',function (e){
        e.preventDefault();
        const elem = document.getElementById('repairAuthority');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click','#triggerSendReport',function (e){
        e.preventDefault();
        const elem = document.getElementById('subrogationReport');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click','#triggerSendReleaseLetter',function (e){
        e.preventDefault();
        const elem = document.getElementById('releaseLetter');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
    });
    $("body").on('click', '#triggerChangeRequests, #triggerInvestigation', function (e) {
        e.preventDefault();
        const elem = document.getElementById('changeRequest');
        const instance = M.Modal.init(elem, { dismissible: true });

        const modalText = document.querySelector('#changeRequest .modal-body span');
        const dynamicIdButton = document.querySelector('#changeRequest .dynamic-id');
        let buttonIdToShow;

        if (dynamicIdButton){
            if (this.id === 'triggerInvestigation') {
                modalText.textContent = 'Submit for Investigation';
                buttonIdToShow = 'head-assessor-request-investigation';
                dynamicIdButton.innerHTML = `
            <a href="#" class="btn blue lighten-2 waves-effect" id="${buttonIdToShow}">Submit</a>
            <a href="#" class="modal-action modal-close btn red darken-2 waves-effect">Cancel</a>`;
            } else {
                modalText.textContent = 'Request Changes On Report';
                buttonIdToShow = 'head-assessor-request-change';
                dynamicIdButton.innerHTML = `
            <a href="#" class="btn blue lighten-2 waves-effect" id="${buttonIdToShow}">Submit</a>
            <a href="#" class="modal-action modal-close btn red darken-2 waves-effect">Cancel</a>`;
            }
        }


        instance.open();
    });

    $("body").on('click','#archiveClaimTrigger',function (e){
        e.preventDefault();
        const elem = document.getElementById('archiveClaim');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
        var claimID = $(this).data("id");
        $("#claimID").val(claimID);
    });
    $("body").on('click','#addLPOModalTrigger',function (e){
        e.preventDefault();
        const elem = document.getElementById('addLPOModal');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();
        var claimID = $(this).data("id");
        $("#claimID").val(claimID);
    });

    $("body").on('click','#editLPOModalTrigger',function (e){
        e.preventDefault();
        const elem = document.getElementById('editLPOModal');
        const instance = M.Modal.init(elem, {dismissible: true});
        instance.open();

        var claimID = $(this).data("id");
        $("#claim_ID").val(claimID);
    });

    $("body").on('click','#reviewAssessment',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var assessmentReviewType = $("input[name='assessmentReviewType']:checked").val();
        var report = CKEDITOR.instances['report'].getData();
        var pav = $("#pav").val();
        var grandTotal = $("#grandTotal").val();
        var assessmentTypeID = $("#assessmentTypeID").val();
        var isSubrogate = $(".subrogation").is(':checked') ? 1 : 0;
        var companyID = $("#company").val();
        var thirdPartyDriver = $("#thirdPartyDriver").val();
        var thirdPartyPolicy = $("#thirdPartyPolicy").val();
        var thirdPartyVehicleRegNo = $("#thirdPartyVehicleRegNo").val();
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
                report : report,
                grandTotal : grandTotal,
                assessmentTypeID : assessmentTypeID,
                pav : pav,
                isSubrogate : isSubrogate,
                companyID : companyID,
                thirdPartyDriver : thirdPartyDriver,
                thirdPartyPolicy : thirdPartyPolicy,
                thirdPartyVehicleRegNo : thirdPartyVehicleRegNo

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
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
                $("#approve").modal('close');
            }

        });


    });
    $("body").on('click','#review-assessment-manager-supplementary',function (e){
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
            url: '/assessment-manager/review-supplementary',
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
    $("body").on('click','#review-head-assessor-supplementary',function (e){
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
            url: '/head-assessor/review-supplementary',
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
    $("body").on('click','#review-assistant-head-assessor-supplementary',function (e){
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
            url: '/assistant-head-assessor/review-supplementary',
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
        var isSubrogate = $(".subrogation").is(':checked') ? 1 : 0;
        var companyID = $("#company").val();
        var thirdPartyDriver = $("#thirdPartyDriver").val();
        var thirdPartyPolicy = $("#thirdPartyPolicy").val();
        var thirdPartyVehicleRegNo = $("#thirdPartyVehicleRegNo").val();
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
                report : report,
                isSubrogate : isSubrogate,
                companyID : companyID,
                thirdPartyDriver : thirdPartyDriver,
                thirdPartyPolicy : thirdPartyPolicy,
                thirdPartyVehicleRegNo : thirdPartyVehicleRegNo
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
                $("#approve").modal('close');
            }

        });


    });
    $("body").on('click','#review-assistant-head-assessor-assessment',function (e){
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
            url: '/assistant-head-assessor/review-assessment',
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
                $("#approve").modal('close');
            }

        });


    });
    $("body").on('click','#head-assessor-review-price-change',function (e){
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
            url: '/head-assessor/review-price-change',
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
    $("body").on('click','#assistant-head-assessor-review-price-change',function (e){
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
            url: '/assistant-head-assessor/review-price-change',
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
    $("#changeRequest").on('click','#head-assessor-request-change',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var changes = CKEDITOR.instances['changes'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                changes : changes
            },
            url: '/head-assessor/request-assessment-change',
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
                $("#changeRequest").modal('close');
            }

        });


    });
    $("#changeRequest").on('click','#head-assessor-request-investigation',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var changes = CKEDITOR.instances['changes'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                reviewNote : changes
            },
            url: '/head-assessor/request-assessment-investigation',
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
                $("#changeRequest").modal('close');
            }

        });


    });
    $("#changeRequest").on('click','#assistant-head-assessor-request-change',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var changes = CKEDITOR.instances['changes'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                changes : changes
            },
            url: '/assistant-head-assessor/request-assessment-change',
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
                $("#changeRequest").modal('close');
            }

        });


    });
    $("#changeRequest").on('click','#assessment-manager-request-change',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var changes = CKEDITOR.instances['changes'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                changes : changes
            },
            url: '/assessment-manager/request-assessment-change',
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
                $("#changeRequest").modal('close');
            }

        });


    });
    $("body").on('click','#assessment-manager-review-price-change',function (e){
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
            url: '/assessment-manager/assessment-manager-review-price-change',
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
    $("body").on('click','#assessment-manager-view-price-change',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/assessment-manager/price-change-report',
            data : {
                assessmentID : assessmentID
            },
            success: function (data) {
                // $("#main").html(data);
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });

    $("#changeRequest").on('click','#head-assessor-supplementary-request-change',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var changes = CKEDITOR.instances['changes'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                changes : changes
            },
            url: '/head-assessor/request-supplementary-change',
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
    $("#changeRequest").on('click','#assistant-head-assessor-supplementary-request-change',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var changes = CKEDITOR.instances['changes'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                changes : changes
            },
            url: '/assistant-head-assessor/request-supplementary-change',
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
    $("#changeRequest").on('click','#assessment-manager-supplementary-request-change',function (e){
        e.preventDefault();
        var assessmentID = $("#assessmentID").val();
        var changes = CKEDITOR.instances['changes'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
                changes : changes
            },
            url: '/assessment-manager/request-supplementary-change',
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
    $("body").on('click','#send-discharge-voucher',function (){
        var claimID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',
            url: '/adjuster/send-discharge-voucher/'+claimID,
            success: function (data) {
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#send-release-letter',function (){
        var claimID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',
            url: '/adjuster/send-release-letter/'+claimID,
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
    $("body").on('click','#emailReleaseletter',function (){
        var claimID = $(this).data("id");
        var email = $("#email");
        if(email.val() != '')
        {
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                    email : email.val(),
                    claimID : claimID
                },
                url: '/adjuster/emailReleaseletter',
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
                title: "Provide an email address",
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
    $("#main").on('click','#filterReInspections',function (){
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/common/fetch-re-inspections',
            data: {
                fromDate: fromDate,
                toDate: toDate,
                regNumber : regNumber
            },
            success: function (data) {
                $("#main").html(data);
                $('.datepicker').datepicker();
                $('#data-table-simple').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "columnDefs": [
                        { 'visible': false, 'targets': [2] },
                        { 'visible': false, 'targets': [3] },
                        { 'visible': false, 'targets': [4] },
                        { 'visible': false, 'targets': [5] },
                        { 'visible': false, 'targets': [6] },
                        { 'visible': false, 'targets': [7] }
                    ],
                    "pageLength": 25
                });
            }

        });
    });
    $("body").on('click','#SendRepairAuthority',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        var email = $("#email");
        if(email.val() != '')
        {
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',

                url: '/adjuster/SendRepairAuthority',
                data : {
                    assessmentID : assessmentID,
                    email : email.val()
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
            {
                Swal.fire({
                    icon: 'error',
                    title: 'You have not the specified Garage',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        }
    });
    $("body").on('click','#sendSubrogationReport',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',

                url: '/common/sendSubrogationReport',
                data : {
                    assessmentID : assessmentID,
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
    $("body").on('click','#send-release-letter',function (){
        var claimID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',
            url: '/adjuster/send-release-letter/'+claimID,
            success: function (data) {
                var w = window.open('about:blank');
                w.document.open();
                w.document.write(data);
                w.document.close();
            }

        });
    });
    $("body").on('click','#dashboard',function (){

        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/dashboard',
            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#sendNotification',function (){
        var email = $("#email");
        var ccEmail = $("#cc_emails");
        var assessmentID = $("#assessmentID").val();
        var message = CKEDITOR.instances['message'].getData();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        if(email.val() != '')
        {
            if(message != '')
            {
                addLoadingButton();
                $.ajax({

                    type: 'POST',
                    url: '/common/sendNotification',
                    data: {
                        assessmentID: assessmentID,
                        message: message,
                        emails: email.val(),
                        ccEmails: ccEmail.val(),
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
                        removeLoadingButton();
                    }

                });
            }else
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Message required',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'Email required',
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
    $("body").on('click','#showActivityLog',function (){
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/common/showActivityLog',
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



    $("body").on('click','#showEscalations',function (){
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/common/fetchEscalations',
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



    $("body").on('click','.fetchLogDetails',function (){

        var activityLogID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/common/fetchLogDetails',
            data: {
                activityLogID : activityLogID
            },
            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("#main").on('click','#filter-logs',function (e){
        e.preventDefault();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/common/filter-logs',

            success: function (data) {
                $("#table-data").html(data);
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
    $(".flagged-assessments").on('click',function (e){
        e.preventDefault();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {},
            url: '/common/flagged-assessments',

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
    $(".flagged-supplementaries").on('click',function (e){
        e.preventDefault();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {},
            url: '/common/flagged-supplementaries',

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
    $("#main").on('click','#filter-flagged-assessments',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/common/flagged-assessments',

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
    $("#main").on('click','#filter-flagged-supplementaries',function (e){
        e.preventDefault();
        var assessmentStatusID = $("#assessmentStatusID").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var regNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentStatusID : assessmentStatusID,
                fromDate : fromDate,
                toDate : toDate,
                regNumber : regNumber
            },
            url: '/common/flagged-supplementaries',

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
    $("#main").on('click','#fetchDMSDocuments',function (e){
        e.preventDefault();
        var claimNo= $(this).data("id");
        var policyNo= $(this).data("id2");
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                claimNo : claimNo,
                policyNo : policyNo
            },
            url: '/common/fetchDMSDocuments',

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
    $("#main").on('click','#submitClaimArchival',function (e){
        e.preventDefault();
        var archiveNote= CKEDITOR.instances['archiveNote'].getData();
        var claimID= $("#claimID").val();
        if(archiveNote != '')
        {
            addLoadingButton();
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                    archiveNote : archiveNote,
                    claimID : claimID
                },
                url: '/adjuster/archiveClaim',

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
                    removeLoadingButton();
                }

            });
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'You have not provided a note',
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
    $("#main").on('click','#submitAddLPORequest',function (e){
        e.preventDefault();
        var claimID= $("#claimID").val();
        var amount = $("#amount").val();
        if(amount != '')
        {
            addLoadingButton();
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                    amount : amount,
                    claimID : claimID
                },
                url: '/adjuster/addLPO',

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
                    removeLoadingButton();
                }

            });
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'You have not provided an amount',
                showConfirmButton: false,
                timer: 3000
            })
        }
    });


    $("#main").on('click','#updateEditLPORequest',function (e){
        e.preventDefault();
        var claimID= $("#claim_ID").val();
        var amount = $("#edit_amount").val();

        // alert(amount);
        if(amount != '')
        {
            addLoadingButton();
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                    amount : amount,
                    claimID : claimID
                },
                url: '/adjuster/edit-lpo-amount',

                success: function (data) {
                    var result = $.parseJSON(data);
                    if (result.STATUS_CODE == SUCCESS_CODE) {
                        Swal.fire({
                            icon: 'success',
                            title: result.STATUS_MESSAGE,
                            showConfirmButton: false,
                            timer: 3000
                        })
                        const elem = document.getElementById('editLPOModal');
                        const instance = M.Modal.init(elem, {dismissible: true});
                        instance.close();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: result.STATUS_MESSAGE,
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                    removeLoadingButton();
                }

            });
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'You have not provided an amount',
                showConfirmButton: false,
                timer: 3000
            })
        }
    });


    $("#main").on('click','#submitPTVRequest',function (e){
        e.preventDefault();
        var assessmentID= $("#assessmentID").val();
        var amount = $("#amount").val();
        if(amount != '')
        {
            addLoadingButton();
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                    amount : amount,
                    assessmentID : assessmentID
                },
                url: '/assessor/submitPTVRequest',

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
                    removeLoadingButton();
                }

            });
        }else
        {
            Swal.fire({
                icon: 'error',
                title: 'You have not provided an amount',
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
    $("#main").on('click','#submitSalvageRequest',function (e){
        e.preventDefault();
        var claimID= $("#claimID").val();
        var logbookReceived = $("#logbookReceived").val();
        var documentsReceived = $("#documentsReceived").val();
        var dateRecovered = $("#dateRecovered").val();
        var location = $("#location").val();
        var insuredInterestedWithSalvage = $("#insuredInterestedWithSalvage").val();
            addLoadingButton();
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                    logbookReceived : logbookReceived,
                    documentsReceived : documentsReceived,
                    claimID : claimID,
                    dateRecovered : dateRecovered,
                    location : location,
                    insuredInterestedWithSalvage : insuredInterestedWithSalvage
                },
                url: '/common/submitSalvageRequest',

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
                    removeLoadingButton();
                }

            });
    });
    $("#main").on('click','#submitSaleSalvageRequest',function (e){
        e.preventDefault();
        var salvageID= $("#salvageID").val();
        var vendor = $("#vendor").val();
        var cost = $("#cost").val();
        var logbookReceivedByRecoveryOfficer = $("#logbookReceivedByRecoveryOfficer").val();
        // var irs = $("#insuredRetainedSalvage").val();
        var insuredRetainedSalvage = $("#insuredRetainedSalvage").val();


            addLoadingButton();
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                    salvageID : salvageID,
                    vendor : vendor,
                    cost : cost,
                    logbookReceivedByRecoveryOfficer : logbookReceivedByRecoveryOfficer,
                    // insuredRetainedSalvage:irs
                    insuredRetainedSalvage:insuredRetainedSalvage
                },
                url: '/common/submitSaleSalvageRequest',

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
                    removeLoadingButton();
                }

            });
    });
    $("body").on('click','.fetch-salvage-register',function (e){
        e.preventDefault();
            addLoadingButton();
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            $.ajax({

                type: 'POST',
                data : {
                },
                url: '/common/fetch-salvage-register',

                success: function (data) {
                    $("#main").html(data);
                    $('.datepicker').datepicker();
                    $('#data-table-simple').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
                        "columnDefs": [
                            { 'visible': false, 'targets': [2] },
                            { 'visible': false, 'targets': [3] },
                            { 'visible': false, 'targets': [4] },
                            { 'visible': false, 'targets': [5] },
                            { 'visible': false, 'targets': [6] },
                            { 'visible': false, 'targets': [10] },
                            { 'visible': false, 'targets': [11] }
                        ],
                        "pageLength": 25
                    });
                    $("#mainLoader").addClass('hideLoader');
                }
            });
    });
    $("body").on('change','#carMake',function (e){
        e.preventDefault();
        var carMakeCode = $("#carMake").val();
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                carMakeCode : carMakeCode,
            },
            url: '/common/fetchModelsByMake',

            success: function (data) {
                $("#carModel").html(data);
                $("#mainLoader").addClass('hideLoader');
            }

        });
    });
    $("body").on('click','#send-assessment-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
            },
            url: '/common/reports/assessment-report',
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
    $("body").on('click','#send-LPO-report',function (e){
        e.preventDefault();
        var claimID = $(this).data("id");
        addLoadingButton();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                claimID : claimID,
            },
            url: '/common/reports/LPO-report',
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
                removeLoadingButton();
            }

        });
    });
    $("body").on('click','#send-reinspection-report',function (e){
        e.preventDefault();
        var assessmentID = $(this).data("id");
        addLoadingButton();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                assessmentID : assessmentID,
            },
            url: '/common/reports/re-inspection-report',
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
                removeLoadingButton();
            }

        });
    });
    $("body").on('change','#carModel',function (e){
        e.preventDefault();
        var carModelCode = $("#carModel").val();
        if(carModelCode !='')
        {
            $('#validModel').val(1);
            $("#modelLabel").removeClass('error');
            $("#custom-step-one").removeClass('wrong');
            $("#custom-step-one").addClass('done');
        }else
        {
            $('#validModel').val('');
            $("#modelLabel").addClass('error');
            $("#custom-step-one").addClass('wrong');
            $("#custom-step-one").removeClass('done');
        }

    });
    $("#main").on('click','.userType',function (){
        var checkedValue =$("input[type='radio']").val();
    });
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
    $("body").on('click','#addPartMaster',function (e){
        e.preventDefault();
        var name = $("#name").val();
        addLoadingButton();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                name : name,
            },
            url: '/admin/add-part',
            success: function (data) {
                var result = $.parseJSON(data);
                if (result.STATUS_CODE == SUCCESS_CODE) {
                    Swal.fire({
                        icon: 'success',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                    $("#addPartModal").modal('close');
                    removeLoadingButton();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                    $("#addPartModal").modal('close');
                    removeLoadingButton();
                }
            }

        });


    });


    $("body").on('click','#triggeraddCourtesyCarFirmModal',function (e){
        e.preventDefault();
        const elem = document.getElementById('addCourtesyCarFirmModal');

        const instance = M.Modal.init(elem, {dismissible: true});
        var claimID = $(this).data("id");
        $("#claimID").val(claimID);
        instance.open();

    });


    $('body').on('change keyup', '#numberOfDays', function(e) {
        e.preventDefault();
        var numberOfDays = $("#numberOfDays").val();
        var charge = $("#charge").val();
        var totalCharge=  numberOfDays * charge;

        $("#totalCharge").val(totalCharge);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/adjuster/addDays",
            data:{
                numberOfDays:numberOfDays
            },
            success:function(data){

                $("#returnDate").val(data);
            }
        })

    });


    $('#main').on('change', '#vendorID', function (e) {
        e.preventDefault();

        var vendorID=$('#vendorID').val();


        $.ajaxSetup({
           headers:{
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
        });
        $.ajax({
            type: 'POST',
            url: '/adjuster/getCharge',
            data: {
                vendorID:vendorID
            },
            success: function (data){
                var result = $.parseJSON(data);
                $('#charge').val(result.charge);
                var numberOfDays = $("#numberOfDays").val();
                var charge = $("#charge").val();
                var totalCharge=  numberOfDays * charge;

                $("#totalCharge").val(totalCharge);
            }
        });
    });
    $("body").on('click','#addCourtesyCar',function (e){

        e.preventDefault();

        var claimID = $("#claimID").val();
        var vendorID =$("#vendorID").val();
        var numberOfDays = $('#numberOfDays').val();
        var returnDate = $("#returnDate").val();
        var charge = $('#charge').val();
        var totalCharge= $('#totalCharge').val();


        addLoadingButton();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/adjuster/processCourtesy',

            data: {
                claimID : claimID,
                vendorID: vendorID,
                nofdays: numberOfDays,
                rdate: returnDate,
                charge: charge,
                totalCharge: totalCharge
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
                removeLoadingButton();
            }


        });
    });
    $("body").on('click', '#showCourtesyCar', function () {
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/adjuster/showCourtesyCar',
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
    $("body").on('click', '#subrogationRegister', function () {
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({
            type: 'POST',
            url: '/adjuster/subrogationRegister',
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

    $("body").on('click','#addPermission',function (e){
        e.preventDefault();
        var name = $("#name").val();
        addLoadingButton();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            data : {
                name : name,
            },
            url: '/admin/add-permission',
            success: function (data) {
                var result = $.parseJSON(data);
                if (result.STATUS_CODE == SUCCESS_CODE) {
                    Swal.fire({
                        icon: 'success',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                    $("#addPartModal").modal('close');
                    removeLoadingButton();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                    $("#addPartModal").modal('close');
                    removeLoadingButton();
                }
            }

        });


    });

    $("#main").on('change','#subrogation',function (e){
        e.preventDefault();
        var isSubrogate = $(".subrogation").is(':checked') ? 1 : 0;
        if(isSubrogate)
        {
            $(".subrogationSelect,.thirdPartyDetails").removeClass('hideSubrogation');
        }else
        {
            $("#company").val(0);
            $(".subrogationSelect,.thirdPartyDetails").addClass('hideSubrogation');
        }

    });
    $('.datepicker').datepicker();
    $('.collapsible').collapsible();
    $(".sidenav").sidenav();

    $("body").on('click','#changeTracker',function (){
        $("#mainLoader").removeClass('hideLoader');
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',
            url: '/common/changeTracker',
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

function assignAssessor(id,claimType) {
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
                assessor: assessor.val(),
                claimType : claimType
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
    var indexArray = [];
    $('.dynamicVehiclePart').each(function(index, item){
        jQuery(':checkbox', this).each(function () {
            if ($(this).is(':checked')) {
                $(item).remove();
                indexArray.push(index);
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

function printDiv() {

    var printContents = document.getElementById('printableArea').innerHTML;

    var originalContents = document.body.innerHTML;



    document.body.innerHTML = printContents;



    window.print();



    document.body.innerHTML = originalContents;

}
