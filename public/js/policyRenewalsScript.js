const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;



$(document).ready(function () {

$(".sidenav").sidenav();
    $('.datepicker').datepicker();
    $('.materialboxed').materialbox();
    $(".fetch-renewals").on('click', function (e) {
        e.preventDefault();

        var period = $(this).data("id");
        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({

            type: 'POST',

            url: '/fetchRenewals',

            data: {period: period},

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

 $("body").on('click','.renewals-loader',function(){
     $("#main").html(
         `<div class="preloader-wrapper big active" style="margin-top:20%; margin-left:40%;">
    <div class="spinner-layer spinner-green-only">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div><div class="gap-patch">
        <div class="circle"></div>
      </div><div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>`

  );



 });






$("body").on('click','#filterRenewals', function (e) {
        e.preventDefault();

         $(".filteredPolicyData").html(
            `<div class="preloader-wrapper big active" style="margin-top:10%; margin-left:43%;">
       <div class="spinner-layer spinner-green-only">
         <div class="circle-clipper left">
           <div class="circle"></div>
         </div><div class="gap-patch">
           <div class="circle"></div>
         </div><div class="circle-clipper right">
           <div class="circle"></div>
         </div>
       </div>
     </div>`

     );

        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var vehicleRegNumber = $("#vehicle_reg_no").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/filterRenewals',

            data: {
                fromDate: fromDate,
                toDate : toDate,
                vehicleRegNumber : vehicleRegNumber
            },

            success: function (data) {
                $(".filteredPolicyData").html(data);
                $('.datepicker').datepicker();
                $('.collapsible').collapsible();
                $('.tbl-accordion-nested').each(function(){
                    var thead = $(this).find('thead');
                    var tbody = $(this).find('tbody');

                    tbody.hide();

                    thead.click(function(e){
                        e.preventDefault();
                        $(this).next().slideToggle();

                    });
                    });


             }

              });

              });

              $('body').on('click','.approveParentDetails', function(e){
                e.preventDefault();

                var obj=$(this);

                var id = $(this).parent().prev().prev().children().data('id');
                var id2 = $(this).parent().prev().prev().children().data('id2');



                $.ajaxSetup({

                    headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                    });
                    $.ajax({

                    type: 'POST',


                    url: '/approveParentDetails',

                    data: {id:id,id2:id2},

                    success: function (data) {
                        if(data=="true")
                        {
                            obj.parent().parent().parent().parent().find('.test').children().html(

                                `<div class="preloader-wrapper small active">
                                    <div class="spinner-layer spinner-green-only">
                                      <div class="circle-clipper left">
                                        <div class="circle"></div>
                                      </div><div class="gap-patch">
                                        <div class="circle"></div>
                                      </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                      </div>
                                    </div>
                                  </div>`
                                  );
                            var val=1;
                            var id3=obj.parent().parent().parent().parent().find('.test').children().data('id');
                           var statuses = obj.parent().parent().parent().parent().find('.test').children()[0];


                           approveAllParents(id3,val,statuses);
                        }else{

                        }

                        // console.log(data);
                        // child.html('<i class="material-icons " style="color: green;">checked</i>');



                    }

                    });



              });



$("body").on('click','#approveAll', function (e) {
e.preventDefault();
// console.log($('.cd:checkbox:checked').parentsUntil('.tb').next().find('.rp'));
//  console.log($('.cd:checkbox:checked').parentsUntil('.tb').next().find('.rp'));

$('.cd:checkbox:checked').parentsUntil('.ths').next().find('.test').each(function(){
     $(this).children().html(

`<div class="preloader-wrapper small active">
    <div class="spinner-layer spinner-green-only">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div><div class="gap-patch">
        <div class="circle"></div>
      </div><div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>`
  );
});

var count = $('.cd:checkbox:checked').parentsUntil('.tb').next().find('.rp').length;

var i=1;
var counter=[];

for(let a of $('.cd:checkbox:checked').parentsUntil('.tb').next().find('.rp'))
{
   var statuses =a.parentNode.nextElementSibling.children[0];


   var id= a.dataset.id;
   var val=a.value;
   var count = count;
//    console.log(count);
   approveAll(id,val, statuses, count,i++, counter);
}






//   $('.cd:checkbox:checked').parentsUntil('.tb').next().find('.rp').map(function(i,data){
//     var statuses = $(this).parent().next().children();
//     var id= $(this).data('id');
//     var val =$(this).val();
//     count= count - 1;
//     // console.log($(this));
//     console.log($('.cd:checkbox:checked').parentsUntil('.tb').next().find('.rp')[0]);

//     approveAll(id,val, statuses, count);
//     }).get();







    // if(index == ARRAY.length -1) {
    //     // do your special thing
    //   }




// $('.cd:checkbox:checked').parentsUntil('.tb').next().find('.rp').each(function(){
    // var status = $(this).parent().next().children();
    // var id= $(this).data('id');
    // var val =$(this).val();
    // approveAll(id,val, status);
// });


// console.log($('.cd:checkbox:checked').parentsUntil('.ths').next().find('.test'));

//====================
// $('.cd:checkbox:checked').parentsUntil('.ths').next().find('.test').map(function(){
//     var status = $(this).children();
//     var id= $(this).children().data('id2');
//     var val =$(this).val();
//     approveAllParents(id,val, status);
//     }).get();


//===========================


// $('.cd:checkbox:checked').parentsUntil('.ths').next().find('.test').each(function(){
//     // $(this).html("");
//     var status = $(this).children();
//     var id= $(this).children().data('id2');
//     var val =$(this).val();
//     approveAllParents(id,val, status);
// });

});


         function approveAllParents(id,val,statuses){
                var val = val;
                var id = id;

                var child = statuses;



                  $.ajaxSetup({

                headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

                });
                $.ajax({

                type: 'POST',


                url: '/approveAllParents',

                data: {val: val, id:id},

                success: function (data) {
                    child.innerHTML='<b style="color:green;">checked</b>';


                }

                });


        }


 function approveAll(id,val,statuses, count,i,counter){

                var val = val;
                var id = id;
                var child = statuses;
                var count = count;

                  $.ajaxSetup({

                headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

                });
                $.ajax({

                type: 'POST',


                url: '/updatingRenewalPremium',

                data: {val: val, id:id},

                success: function (data) {
                    child.innerHTML='<b style="color:green;">checked</b>';

                    counter.push(i);

                    // console.log(i+"    "+count);
                    console.log(counter.length +"     counter"+count);

                    if(count == counter.length)
                    {
                        console.log("main");
                        for(let a of $('.cd:checkbox:checked').parentsUntil('.ths').next().find('.test'))
                            {
                                    var statuses =a.children[0];
                                    console.log(statuses);
                                    var id= a.children[0].dataset.id;
                                    var val=9;
                                    approveAllParents(id,val, statuses);
                            }


                        // for(let a of $('.cd:checkbox:checked').parentsUntil('.ths').next().find('.test'))
                        // {
                        //          var statuses =a.children[0];
                        //          var id= a.children[0].dataset.id;
                        //          var val=9;
                        //         approveAllParents(id,val, statuses);
                        // }




                        // $('.cd:checkbox:checked').parentsUntil('.ths').next().find('.test').map(function(){
                        //     var statuses = $(this);
                        //     var id= $(this).children().data('id2');
                        //     var val =$(this).val();


                        //     console.log(id+"******"+statuses);
                        //     approveAllParents(id,val, statuses);
                        //     }).get();


                    }


                }

                });

        }





        $("body").on('click','#findRenewals', function (e) {
        e.preventDefault();


         $(".filteredPolicyData").html(
            `<div class="preloader-wrapper big active" style="margin-top:10%; margin-left:43%;">
       <div class="spinner-layer spinner-green-only">
         <div class="circle-clipper left">
           <div class="circle"></div>
         </div><div class="gap-patch">
           <div class="circle"></div>
         </div><div class="circle-clipper right">
           <div class="circle"></div>
         </div>
       </div>
     </div>`

     );
        $('#approveAll').hide();

        var period = $("#period").val();

        var val1= $('#filter').val();
        var val2= $('#filterBy').val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();

         if(val2=="lossRatio_below_60")
          {
              val2="lossRatio_below_60";
          }
          if(val2=="lossRatio_above_60")
          {
              val2="lossRatio_above_60";
          }



        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }
            });
        $.ajax({

            type: 'POST',

            url: '/filterBy',

            data: {
                val1:val1,
                val2:val2,
                period:period,
                fromDate:fromDate,
                toDate: toDate

            },

            success: function (data) {
                $(".filteredPolicyData").html(data);
                $('.datepicker').datepicker();
                $('.collapsible').collapsible();
                $('.tbl-accordion-nested').each(function(){
                    var thead = $(this).find('thead');
                    var tbody = $(this).find('tbody');

                    tbody.hide();

                    thead.click(function(e){
                        e.preventDefault();
                        $(this).next().slideToggle();

                    });
                    });


             }

              });

              });


    $("body").on('click','.fetchClaimDetail', function (e) {
        e.preventDefault();
        var renewalID = $(this).data("id");
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/fetchPolicyDetail',

            data: {renewalID: renewalID},

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','.approve', function (e) {
        e.preventDefault();
        var renewalID = $(this).data("id");
        var period = $("#period").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/approveRenewalPolicy',

            data: {
                renewalID: renewalID,
                period : period
            },

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $("body").on('click','#editPolicy', function (e) {
        e.preventDefault();
        var renewalID = $("#renewalID").val();
        var totalPremium = $("#totalPremium").val();
        var insuranceTrainingLavy = $("#insuranceTrainingLavy").val();
        var PolicyHoldersFund = $("#PolicyHoldersFund").val();
        var customerName = $("#customerName").val();
        var assuredName = $("#assuredName").val();
        var MSISDN = $("#MSISDN").val();
        var customerEmail = $("#customerEmail").val();
        var sumInsured = $("#sumInsured").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/updatePolicyRenewal',

            data: {
                renewalID: renewalID,
                totalPremium : totalPremium,
                insuranceTrainingLavy : insuranceTrainingLavy,
                PolicyHoldersFund : PolicyHoldersFund,
                customerName : customerName,
                assuredName : assuredName,
                MSISDN : MSISDN,
                customerEmail : customerEmail,
                sumInsured : sumInsured
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


$('body').on('click','.pagination a', function(e){
    e.preventDefault();
     var flag=$("#flag").val();

     $($(this)).html(
        `<div class="preloader-wrapper small active" style="margin-top:auto; ">
   <div class="spinner-layer spinner-blue-only" ">
     <div class="circle-clipper left">
       <div class="circle"></div>
     </div><div class="gap-patch">
       <div class="circle"></div>
     </div><div class="circle-clipper right">
       <div class="circle"></div>
     </div>
   </div>
 </div>`

 );


    var page= $(this).attr('href').split("page=")[1];
    if(flag=="index")
    {

        getMoreRenewals(page)
    }
    if(flag=="frenewal")
    {

        getMoreFilteredRenewals(page)
    }
    if(flag=="fby")
    {


        moreFilterBy(page)

    }
    if(flag=="fbyRange")
    {


        moreFilterByRange(page)

    }





})

function getMoreRenewals(page)
{
    $.ajaxSetup({

    headers: {

    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

    });
    $.ajax({

    type: 'GET',

    url: '/getMoreRenewals'+ '?page=' + page,

    success: function (data) {
    $(".filteredPolicyData").html(data);

    }

    });

}

function getMoreFilteredRenewals(page)
{

    var fromDate = $("#from_date").val();
    var toDate = $("#to_date").val();
    var vehicleRegNumber = $("#vehicle_reg_no").val();;
    $.ajaxSetup({

    headers: {

    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

    });
    $.ajax({

    type: 'GET',

    url: '/getMoreFilteredRenewals'+ '?page=' + page,
    data: {
        fromDate: fromDate,
        toDate : toDate,
        vehicleRegNumber : vehicleRegNumber
    },

    success: function (data) {
    $(".filteredPolicyData").html(data);

    }

    });

}


function moreFilterBy(page)
{

    var period = $("#period").val();

        var val1= $('#filter').val();
        var val2= $('#filterBy').val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
    $.ajaxSetup({

    headers: {

    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

    });
    $.ajax({

    type: 'GET',

    url: '/moreFilterBy'+ '?page=' + page,
    data: {
        val1:val1,
        val2:val2,
        period:period,
        fromDate:fromDate,
        toDate: toDate

    },

    success: function (data) {
    $(".filteredPolicyData").html(data);

    }

    });

}

function moreFilterByRange(page)
{

    var period = $("#period").val();

        var val1= $('#filter').val();
        var val2= $('#filterBy').val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
    $.ajaxSetup({

    headers: {

    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

    });
    $.ajax({

    type: 'GET',

    url: '/moreFilterByRange'+ '?page=' + page,
    data: {
        val1:val1,
        val2:val2,
        period:period,
        fromDate:fromDate,
        toDate: toDate

    },

    success: function (data) {
    $(".filteredPolicyData").html(data);

    }

    });

}
