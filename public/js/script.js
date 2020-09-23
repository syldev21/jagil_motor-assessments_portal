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
                    location : location.val()
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
    // $("#searchClaim").on('click',function (){
    //     var fromDate = $("#from_date");
    //     var toDate = $("#to_date");
    //     var vehicleRegNo = $("#vehicle_reg_no");
    //     if((fromDate.val() != '' && toDate.val() != '') || vehicleRegNo.val() != '')
    //     {
    //         $.ajaxSetup({
    //
    //             headers: {
    //
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //
    //             }
    //
    //         });
    //         $.ajax({
    //
    //             type: 'POST',
    //
    //             url: '/adjuster/search',
    //
    //             data: {
    //                 fromDate : fromDate.val(),
    //                 toDate : toDate.val(),
    //                 vehicleRegNo : vehicleRegNo.val()
    //             },
    //
    //             success: function (data) {
    //                 var result = $.parseJSON(data);
    //                 if (result.STATUS_CODE == SUCCESS_CODE) {
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: result.STATUS_MESSAGE,
    //                         showConfirmButton: false,
    //                         timer: 3000
    //                     })
    //                 } else {
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: result.STATUS_MESSAGE,
    //                         showConfirmButton: false,
    //                         timer: 3000
    //                     })
    //                 }
    //             }
    //
    //         });
    //     }else
    //     {
    //         Swal.fire({
    //             icon: 'error',
    //             title: "You have to provide either date range or vehicle Reg_No",
    //             showConfirmButton: false,
    //             timer: 3000
    //         })
    //     }
    //
    // });
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
    $("body").on('click','#updateClaim',function (e){
        e.preventDefault();
        var sumInsured = $("#sumInsured");
        var excess = $("#excess");
        var location = $("#location");
        var claimID = $("#claimID");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/adjuster/updateClaim',

            data: {
                sumInsured : sumInsured.val(),
                excess : excess.val(),
                location : location.val(),
                claimID : claimID.val()
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
    // $('.dropdown-trigger').dropdown({
    //     hover: false, constrainWidth: false
    // });
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
            }

        });
    });
    $("body").on('click','#fetchUploadedClaims',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/fetchUploadedClaims',

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("#assignedClaims").on('click',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/assignedClaims',

            success: function (data) {
                $("#main").html(data);
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
            }

        });
    });
    $("#fetchAssignedAssessments").on('click',function (e){
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'GET',

            url: '/adjuster/fetchAssignedAssessments',

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
            }

        });
    });
    $("body").on('click','#claimForm',function (e){
        e.preventDefault();
        var data = $(this).data("id");;
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
    $(".shhsrole").on('click',function (e){
        var roleID = $(this).data("id");
        var userID = 1;
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
                userID : userID,
                roleID : roleID,
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
                assessor: assessor.val(),
                garage: garage.val()
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
