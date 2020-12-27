$(document).ready(function () {
   $(".userBtn").on("click", function () {

   });

   $(".fas.fa-copy").on("click", function () {
      $(".copiedBlock").removeClass('d-none')
      setTimeout(function () {
         $(".copiedBlock").addClass('d-none')
      }, 3000)
   })
   $("#qorilar, #qorilar2").change(function () {
      document.cookie = "qori=" + this.value;
      setTimeout("window.location.reload()", 1000);
      $(".saved").removeClass('d-none')
   })
   $("#darkMode").change(function () {
      if (this.checked == true) {
         document.cookie = "darkMode=on"
         $("body").addClass('darkMode')
      } else {
         document.cookie = "darkMode=off"
         $("body").removeClass('darkMode')
      }
      $(".saved").removeClass('d-none')
      setTimeout(function () {
         $(".saved").addClass('d-none')
      }, 1000);
   })
   $("#headerSlider").change(function () {
      if (this.checked == true) {
         document.cookie = "headerSlider=on"
      } else {
         document.cookie = "headerSlider=off"
      }
      $(".saved").removeClass('d-none')
      setTimeout(function () {
         $(".saved").addClass('d-none')
      }, 1000);
   })
   $("#headerImg").change(function () {
      if (this.checked == true) {
         document.cookie = "headerImg=on"
      } else {
         document.cookie = "headerImg=off"
      }
      $(".saved").removeClass('d-none')
      setTimeout(function () {
         $(".saved").addClass('d-none')
      }, 1000);
   })
   $("#globalColor").change(function () {
      document.cookie = "globalColor=" + this.value;
      $(".saved").removeClass('d-none')
      setTimeout("window.location.reload()", 1000);
   })
   $("#resetGbColor").on("click", function () {
      document.cookie = "globalColor=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
      $(".saved").removeClass('d-none')
      setTimeout("window.location.reload()", 1000);
   })

   $("#spage").on("click", function () {
      $("#snumber").addClass('d-none')
   })

   $(window).scroll(function () {
      if ($(window).scrollTop() > 300) {
         $(".btnUp").css('display', 'block');
      } else {
         $(".btnUp").css('display', 'none');
      }
   })
   if ($(document).height() > 7000) {
      $(window).scroll(function () {
         if ($(window).scrollTop() > 300) {
            $(".up-down").css('display', 'block');
         } else {
            $(".up-down").css('display', 'none');
         }
      });
   }
   $(".up-down .fa-angle-down").on('click', function (e) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: $(document).height() }, '300');
   });

   $(".up-down .fa-angle-up").on('click', function (e) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, 500);
   });

   $(".my-modal-confirm, #my-modal-cancel").on("click", function () {
      $(".my-modal-container").css("display", "none");
   });

   $("#first-owl.owl-carousel, #second-owl.owl-carousel").owlCarousel({
      center: true,
      items: 2,
      loop: true,
      margin: 10,
      // nav:true,
      autoplay: true,
      autoplayTimeout: 3000,
      autoplayHoverPause: true,
      responsive: {
         0: {
            items: 1
         },
         600: {
            items: 2
         },
         960: {
            items: 3
         },
         1200: {
            items: 4
         }
      }
   });
   $("#header .owl-carousel").owlCarousel({
      items: 1,
      loop: true,
      autoplay: true,
      autoplayTimeout: 7000,
      autoplayHoverPause: true,
      nav: true,
      autoHeight: true,
      dots: true
   })

   // $("#play").on("click",function() {
   //     var file = this.onclick;
   //     var myAudio = document.getElementById(file);
   //     alert(file)
   //     if (file.played){
   //         $("#pause").removeClass('d-none');
   //     }else{
   //         $("#pause").addClass('d-none');
   //     }
   // })

   $(".fa-user").on("click", function () {
      $(".right-profile").animate({ width: "show" }, 150);
   })
   $("#close-right-profile").on("click", function () {
      $(".right-profile").animate({ width: "hide" }, 150);
   })

   $(document).mouseup(function (e) {
      if (!$(".right-profile").is(e.target) && ($(".right-profile").has(e.target).length === 0)) {
         $(".right-profile").animate({ width: "hide" }, 150);
      }
   });
   $(".fa-cog").on("click", function () {
      $(".right-setting").animate({ width: "show" }, 150);
   })
   $("#close-right-setting").on("click", function () {
      $(".right-setting").animate({ width: "hide" }, 150);
   })

   $(document).mouseup(function (e) {
      if (!$(".right-setting").is(e.target) && ($(".right-setting").has(e.target).length === 0)) {
         $(".right-setting").css("display", "none")
      }
   });


   $(".searchBtn").on("click", function () {
      $(this).addClass("d-none");
      $('.search-close').removeClass("d-none");
      $(".search-box input").removeClass("d-none");
      // $(".search-box input").addClass("d-none");
   });
   $(".search-close").on("click", function () {
      $(this).addClass("d-none");
      $('.searchBtn').removeClass("d-none");
      $(".search-box input").addClass("d-none");
      // $(".search-box input").addClass("d-none");
   });
   $(".searchBtnClose").on("click", function () {
      $(".searchForm").css("display", "none")
   })

   $(function () {
      $('[data-toggle="tooltip"]').tooltip()
   });

   $('.grid').isotope({});
});


function copytext(el) {
   var $tmp = $("<textarea>");
   $("body").append($tmp);
   $tmp.val($(el).text()).select();
   document.execCommand("copy");
   $tmp.remove();
}