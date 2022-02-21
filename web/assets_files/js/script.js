var swiper = new Swiper('.swiper-container1', {
    slidesPerView: 3,
    spaceBetween: 50,
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 0
      },
      991: {
        slidesPerView: 2,
        spaceBetween: 20
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 50
      }
    }
  });

  $('.mobMenuBurger').click(function(event) {
    $('.mobMenuBurger,.mobMenuList').toggleClass('active');
    $('body').toggleClass('lock');
  });

function darkMode() {
  var x = document.getElementById("darkmode");
  if (x.innerHTML === "Dark Mode") {
    x.innerHTML = "Light mode";
  } else {
    x.innerHTML = "Dark Mode";
  }
  if(document.documentElement.hasAttribute("theme")){
      document.documentElement.removeAttribute("theme");
  }
  else{
      document.documentElement.setAttribute("theme", "dark");
  }
}

const anchors = document.querySelectorAll('a.scroll-to')

for (let anchor of anchors) {
  anchor.addEventListener('click', function (e) {
    e.preventDefault()
    
    const blockID = anchor.getAttribute('href')
    
    document.querySelector(blockID).scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    })
  })
}

$(".custom-select2").each(function() {
  var classes = $(this).attr("class"),
      id      = $(this).attr("id"),
      name    = $(this).attr("name");
  var template =  '<div class="' + classes + '">';
      template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
      template += '<div class="custom-options">';
      $(this).find("option").each(function() {
        template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
      });
  template += '</div></div>';
  
  $(this).wrap('<div class="custom-select-wrapper"></div>');
  $(this).hide();
  $(this).after(template);
});
$(".custom-option:first-of-type").hover(function() {
  $(this).parents(".custom-options").addClass("option-hover");
}, function() {
  $(this).parents(".custom-options").removeClass("option-hover");
});
$(".custom-select-trigger").on("click", function() {
  $('html').one('click',function() {
    $(".custom-select2").removeClass("opened");
  });
  $(this).parents(".custom-select2").toggleClass("opened");
  event.stopPropagation();
});
$(".custom-option").on("click", function() {
  $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
  $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
  $(this).addClass("selection");
  $(this).parents(".custom-select2").removeClass("opened");
  $(this).parents(".custom-select2").find(".custom-select-trigger").text($(this).text());


  if ($(this).parent().parent().prev().attr('name') == 'sources') {
    location.href = '/shop/products?id='+$(this).data("value");
  }
});

$("#review1").rating({
  "value": 0,
  "click": function (e) {
      console.log(e);
      $("#reviewResult1").val(e.stars);
  }
});

$('#answer').click(function(){
  $('.contentSingleCommentsFormAnswer').slideToggle(300);      
  return false;
});

$('.contentLessonSidebarListItem').hover(function(){
  $(this).children(".contentLessonSidebarListItemHover").slideToggle(300);      
  return false;
});