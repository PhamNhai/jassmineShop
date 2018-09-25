jQuerOs(document).ready(function () {

  //if (jQuerOs("[rel=tooltip]").length) {jQuerOs("[rel=tooltip]").tooltip();}
 // jQuerOs('button').addClass('btn');
// ____________________________________________________________________________________________ resize display

        // var myWidth = 0;
        // myWidth = window.innerWidth;
        // jQuerOs('body').prepend('<div id="size" style="background:#000;padding:5px;position:fixed;z-index:999;color:#fff;">Width = '+myWidth+'</div>');
        // jQuerOs(window).resize(function(){
        //     var myWidth = 0;
        //     myWidth = window.innerWidth;
        //     jQuerOs('#size').remove();
        //     jQuerOs('body').prepend('<div id="size" style="background:#000;padding:5px;position:fixed;z-index:999;color:#fff;">Width = '+myWidth+'</div>');
        // });


// ____________________________________________________________________________________________ responsive menu

	var mainMenu = jQuerOs('.main_menu ul.nav');
  mainMenu.find('li.parent > a, li.parent > .nav-header').append('<span class="arrow"></span>');
  mainMenu.find(' > li').last().addClass('lastChild');
// ____________________________________________________________________________________________
 
    jQuerOs('.icon-calendar').removeClass('icon-calendar').addClass('fa fa-calendar');
    jQuerOs('.icon-arrow-left').removeClass('icon-arrow-left icon-white').addClass('fa fa-arrow-left');
 // ____________________________________________________________________________________________footer

  var wrapheight = jQuerOs(window).outerHeight() - jQuerOs(".header").outerHeight(true) - jQuerOs("#footer").outerHeight(true);
  jQuerOs("#wrapper").css("min-height" , wrapheight);


  jQuerOs(".userdata div h2, #com-form-login+h2,.vm-orders-list>h1").replaceWith(function(index, oldHTML){
  return jQuerOs("<h3>").html(oldHTML);
  });

  jQuerOs("#com-form-login").prev("h1").replaceWith(function(index, oldHTML){
  return jQuerOs("<h3>").html(oldHTML);
  });

  jQuerOs(".order-view>h2, .userdata div h3").replaceWith(function(index, oldHTML){
  return jQuerOs("<h4>").html(oldHTML);
  });

  jQuerOs('#logo').viewportChecker({

        classToAdd: 'animated zoomIn',
        repeat: false,

    });


//   jQuerOs(".inputbox_serach_top").focus(function() {
//  alert( "Handler for .change() called." );
// });
jQuerOs(".inputbox_serach_top").focus(function() {
   console.log( "Handler for .focus() called." );

   jQuerOs(this).val("");


});


 });