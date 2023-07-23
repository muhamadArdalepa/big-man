import PerfectScrollbar from 'perfect-scrollbar';


"use strict";
(function () {
  var isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

  if (isWindows) {
    // if we are on windows OS we activate the perfectScrollbar function
    if (document.getElementsByClassName('main-content')[0]) {
      var mainpanel = document.querySelector('.main-content');
      var ps = new PerfectScrollbar(mainpanel);
    };

    if (document.getElementsByClassName('sidenav')[0]) {
      var sidebar = document.querySelector('.sidenav');
      var ps1 = new PerfectScrollbar(sidebar);
    };

    if (document.getElementsByClassName('navbar-collapse')[0]) {
      var fixedplugin = document.querySelector('.navbar:not(.navbar-expand-lg) .navbar-collapse');
      var ps2 = new PerfectScrollbar(fixedplugin);
    };
    
    if (document.getElementsByClassName('modal-body')[0]) {
      var fixedplugin = document.querySelector('.modal-body');
      var ps2 = new PerfectScrollbar(fixedplugin);
    };
  };
})();

if (document.getElementById('alert')) {
  let alertDiv = document.getElementById('alert')
  setTimeout(() => {
    alertDiv.remove();
  }, 5000);
}
  
// initialization of Tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})


// when input is focused add focused class for style
window.focused = function (el) {
  if (el.parentElement.classList.contains('input-group')) {
    el.parentElement.classList.add('focused');
  }
}

// when input is focused remove focused class for style
window.defocused = function (el) {
  if (el.parentElement.classList.contains('input-group')) {
    el.parentElement.classList.remove('focused');
  }
}

// helper for adding on all elements multiple attributes
function setAttributes(el, options) {
  Object.keys(options).forEach(function (attr) {
    el.setAttribute(attr, options[attr]);
  })
}

// adding on inputs attributes for calling the focused and defocused functions
if (document.querySelectorAll('.input-group').length != 0) {
  var allInputs = document.querySelectorAll('input.form-control');
  allInputs.forEach(el => setAttributes(el, {
    "onfocus": "focused(this)",
    "onfocusout": "defocused(this)"
  }));
}



// Debounce Function
// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
  var timeout;
  return function () {
    var context = this,
      args = arguments;
    var later = function () {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

// Toggle Sidenav
const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
const iconSidenav = document.getElementById('iconSidenav');
let body = document.getElementsByTagName('body')[0];
let className = 'g-sidenav-pinned';

if (iconNavbarSidenav) {
  iconNavbarSidenav.addEventListener("click", toggleSidenav);
}

if (iconSidenav) {
  iconSidenav.addEventListener("click", toggleSidenav);
}

function toggleSidenav() {
  if (body.classList.contains(className)) {
    body.classList.remove(className);
    } else {
    body.classList.add(className);
  }
}

let html = document.getElementsByTagName('html')[0];

html.addEventListener("click", function (e) {
  if (body.classList.contains('g-sidenav-pinned') && !e.target.classList.contains('sidenav-toggler-line')) {
    body.classList.remove(className);
  }
});


// Deactivate sidenav type buttons on resize and small screens
window.addEventListener("resize", sidenavTypeOnResize);
window.addEventListener("load", sidenavTypeOnResize);

function sidenavTypeOnResize() {
  let elements = document.querySelectorAll('[onclick="sidebarType(this)"]');
  if (window.innerWidth < 1200) {
    elements.forEach(function (el) {
      el.classList.add('disabled');
    });
  } else {
    elements.forEach(function (el) {
      el.classList.remove('disabled');
    });
  }
}


// Tabs navigation

var total = document.querySelectorAll('.nav-pills');

total.forEach(function (item, i) {
  var moving_div = document.createElement('div');
  var first_li = item.querySelector('li:first-child .nav-link');
  var tab = first_li.cloneNode();
  tab.innerHTML = "-";

  moving_div.classList.add('moving-tab', 'position-absolute', 'nav-link');
  moving_div.appendChild(tab);
  item.appendChild(moving_div);

  var list_length = item.getElementsByTagName("li").length;

  moving_div.style.padding = '0px';
  moving_div.style.width = item.querySelector('li:nth-child(1)').offsetWidth + 'px';
  moving_div.style.transform = 'translate3d(0px, 0px, 0px)';
  moving_div.style.transition = '.5s ease';

  item.onmouseover = function (event) {
    let target = getEventTarget(event);
    let li = target.closest('li'); // get reference
    if (li) {
      let nodes = Array.from(li.closest('ul').children); // get array
      let index = nodes.indexOf(li) + 1;
      item.querySelector('li:nth-child(' + index + ') .nav-link').onclick = function () {
        moving_div = item.querySelector('.moving-tab');
        let sum = 0;
        if (item.classList.contains('flex-column')) {
          for (var j = 1; j <= nodes.indexOf(li); j++) {
            sum += item.querySelector('li:nth-child(' + j + ')').offsetHeight;
          }
          moving_div.style.transform = 'translate3d(0px,' + sum + 'px, 0px)';
          moving_div.style.height = item.querySelector('li:nth-child(' + j + ')').offsetHeight;
        } else {
          for (var j = 1; j <= nodes.indexOf(li); j++) {
            sum += item.querySelector('li:nth-child(' + j + ')').offsetWidth;
          }
          moving_div.style.transform = 'translate3d(' + sum + 'px, 0px, 0px)';
          moving_div.style.width = item.querySelector('li:nth-child(' + index + ')').offsetWidth + 'px';
        }
      }
    }
  }
});


// Tabs navigation resize

window.addEventListener('resize', function (event) {
  total.forEach(function (item, i) {
    item.querySelector('.moving-tab').remove();
    var moving_div = document.createElement('div');
    var tab = item.querySelector(".nav-link.active").cloneNode();
    tab.innerHTML = "-";

    moving_div.classList.add('moving-tab', 'position-absolute', 'nav-link');
    moving_div.appendChild(tab);

    item.appendChild(moving_div);

    moving_div.style.padding = '0px';
    moving_div.style.transition = '.5s ease';

    let li = item.querySelector(".nav-link.active").parentElement;

    if (li) {
      let nodes = Array.from(li.closest('ul').children); // get array
      let index = nodes.indexOf(li) + 1;

      let sum = 0;
      if (item.classList.contains('flex-column')) {
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector('li:nth-child(' + j + ')').offsetHeight;
        }
        moving_div.style.transform = 'translate3d(0px,' + sum + 'px, 0px)';
        moving_div.style.width = item.querySelector('li:nth-child(' + index + ')').offsetWidth + 'px';
        moving_div.style.height = item.querySelector('li:nth-child(' + j + ')').offsetHeight;
      } else {
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector('li:nth-child(' + j + ')').offsetWidth;
        }
        moving_div.style.transform = 'translate3d(' + sum + 'px, 0px, 0px)';
        moving_div.style.width = item.querySelector('li:nth-child(' + index + ')').offsetWidth + 'px';

      }
    }
  });

  if (window.innerWidth < 991) {
    total.forEach(function (item, i) {
      if (!item.classList.contains('flex-column')) {
        item.classList.add('flex-column', 'on-resize');
      }
    });
  } else {
    total.forEach(function (item, i) {
      if (item.classList.contains('on-resize')) {
        item.classList.remove('flex-column', 'on-resize');
      }
    })
  }
});


function getEventTarget(e) {
  e = e || window.event;
  return e.target || e.srcElement;
}

// End tabs navigation

// Light Mode / Dark Mode
window.darkMode = function (el) {
  const body = document.getElementsByTagName('body')[0];
  const hr = document.querySelectorAll('div:not(.sidenav) > hr');
  const sidebar = document.querySelector('.sidenav');
  const sidebarWhite = document.querySelectorAll('.sidenav.bg-white');
  const hr_card = document.querySelectorAll('div:not(.bg-gradient-dark) hr');
  const text_btn = document.querySelectorAll('button:not(.btn) > .text-dark');
  const text_span = document.querySelectorAll('span.text-dark, .breadcrumb .text-dark');
  const text_span_white = document.querySelectorAll('span.text-white');
  const text_strong = document.querySelectorAll('strong.text-dark');
  const text_strong_white = document.querySelectorAll('strong.text-white');
  const text_nav_link = document.querySelectorAll('a.nav-link.text-dark');
  const secondary = document.querySelectorAll('.text-secondary');
  const bg_gray_100 = document.querySelectorAll('.bg-gray-100');
  const bg_gray_600 = document.querySelectorAll('.bg-gray-600');
  const btn_text_dark = document.querySelectorAll('.btn.btn-link.text-dark, .btn .ni.text-dark');
  const btn_text_white = document.querySelectorAll('.btn.btn-link.text-white, .btn .ni.text-white');
  const card_border = document.querySelectorAll('.card.border');
  const card_border_dark = document.querySelectorAll('.card.border.border-dark');
  const svg = document.querySelectorAll('g');
  const navbarBrand = document.querySelector('.navbar-brand-img');
  const navbarBrandImg = navbarBrand.src;
  const navLinks = document.querySelectorAll('.navbar-main .nav-link, .navbar-main .breadcrumb-item, .navbar-main .breadcrumb-item a, .navbar-main h6');
  const cardNavLinksIcons = document.querySelectorAll('.card .nav .nav-link i');
  const cardNavSpan = document.querySelectorAll('.card .nav .nav-link span');


  if (!el.getAttribute("checked")) {
    body.classList.add('dark-version');
    if (navbarBrandImg.includes('logo-ct-dark.png')) {
      var navbarBrandImgNew = navbarBrandImg.replace("logo-ct-dark", "logo-ct");
      navbarBrand.src = navbarBrandImgNew;
    }
    for (var i = 0; i < cardNavLinksIcons.length; i++) {
      if (cardNavLinksIcons[i].classList.contains('text-dark')) {
        cardNavLinksIcons[i].classList.remove('text-dark');
        cardNavLinksIcons[i].classList.add('text-white');
      }
    }
    for (var i = 0; i < cardNavSpan.length; i++) {
      if (cardNavSpan[i].classList.contains('text-sm')) {
        cardNavSpan[i].classList.add('text-white');
      }
    }
    for (var i = 0; i < hr.length; i++) {
      if (hr[i].classList.contains('dark')) {
        hr[i].classList.remove('dark');
        hr[i].classList.add('light');
      }
    }
    for (var i = 0; i < hr_card.length; i++) {
      if (hr_card[i].classList.contains('dark')) {
        hr_card[i].classList.remove('dark');
        hr_card[i].classList.add('light');
      }
    }
    for (var i = 0; i < text_btn.length; i++) {
      if (text_btn[i].classList.contains('text-dark')) {
        text_btn[i].classList.remove('text-dark');
        text_btn[i].classList.add('text-white');
      }
    }
    for (var i = 0; i < text_span.length; i++) {
      if (text_span[i].classList.contains('text-dark')) {
        text_span[i].classList.remove('text-dark');
        text_span[i].classList.add('text-white');
      }
    }
    for (var i = 0; i < text_strong.length; i++) {
      if (text_strong[i].classList.contains('text-dark')) {
        text_strong[i].classList.remove('text-dark');
        text_strong[i].classList.add('text-white');
      }
    }
    for (var i = 0; i < text_nav_link.length; i++) {
      if (text_nav_link[i].classList.contains('text-dark')) {
        text_nav_link[i].classList.remove('text-dark');
        text_nav_link[i].classList.add('text-white');
      }
    }
    for (var i = 0; i < secondary.length; i++) {
      if (secondary[i].classList.contains('text-secondary')) {
        secondary[i].classList.remove('text-secondary');
        secondary[i].classList.add('text-white');
        secondary[i].classList.add('opacity-8');
      }
    }
    for (var i = 0; i < bg_gray_100.length; i++) {
      if (bg_gray_100[i].classList.contains('bg-gray-100')) {
        bg_gray_100[i].classList.remove('bg-gray-100');
        bg_gray_100[i].classList.add('bg-gray-600');
      }
    }
    for (var i = 0; i < btn_text_dark.length; i++) {
      btn_text_dark[i].classList.remove('text-dark');
      btn_text_dark[i].classList.add('text-white');
    }
    for (var i = 0; i < sidebarWhite.length; i++) {
      sidebarWhite[i].classList.remove('bg-white');
    }
    for (var i = 0; i < svg.length; i++) {
      if (svg[i].hasAttribute('fill')) {
        svg[i].setAttribute('fill', '#fff');
      }
    }
    for (var i = 0; i < card_border.length; i++) {
      card_border[i].classList.add('border-dark');
    }
    el.setAttribute("checked", "true");
  } else {
    body.classList.remove('dark-version');
    sidebar.classList.add('bg-white');
    if (navbarBrandImg.includes('logo-ct.png')) {
      var navbarBrandImgNew = navbarBrandImg.replace("logo-ct", "logo-ct-dark");
      navbarBrand.src = navbarBrandImgNew;
    }
    for (var i = 0; i < navLinks.length; i++) {
      if (navLinks[i].classList.contains('text-dark')) {
        navLinks[i].classList.add('text-white');
        navLinks[i].classList.remove('text-dark');
      }
    }
    for (var i = 0; i < cardNavLinksIcons.length; i++) {
      if (cardNavLinksIcons[i].classList.contains('text-white')) {
        cardNavLinksIcons[i].classList.remove('text-white');
        cardNavLinksIcons[i].classList.add('text-dark');
      }
    }
    for (var i = 0; i < cardNavSpan.length; i++) {
      if (cardNavSpan[i].classList.contains('text-white')) {
        cardNavSpan[i].classList.remove('text-white');
      }
    }
    for (var i = 0; i < hr.length; i++) {
      if (hr[i].classList.contains('light')) {
        hr[i].classList.add('dark');
        hr[i].classList.remove('light');
      }
    }
    for (var i = 0; i < hr_card.length; i++) {
      if (hr_card[i].classList.contains('light')) {
        hr_card[i].classList.add('dark');
        hr_card[i].classList.remove('light');
      }
    }
    for (var i = 0; i < text_btn.length; i++) {
      if (text_btn[i].classList.contains('text-white')) {
        text_btn[i].classList.remove('text-white');
        text_btn[i].classList.add('text-dark');
      }
    }
    for (var i = 0; i < text_span_white.length; i++) {
      if (text_span_white[i].classList.contains('text-white') && !text_span_white[i].closest('.sidenav') && !text_span_white[i].closest('.card.bg-gradient-dark')) {
        text_span_white[i].classList.remove('text-white');
        text_span_white[i].classList.add('text-dark');
      }
    }
    for (var i = 0; i < text_strong_white.length; i++) {
      if (text_strong_white[i].classList.contains('text-white')) {
        text_strong_white[i].classList.remove('text-white');
        text_strong_white[i].classList.add('text-dark');
      }
    }
    for (var i = 0; i < secondary.length; i++) {
      if (secondary[i].classList.contains('text-white')) {
        secondary[i].classList.remove('text-white');
        secondary[i].classList.remove('opacity-8');
        secondary[i].classList.add('text-dark');
      }
    }
    for (var i = 0; i < bg_gray_600.length; i++) {
      if (bg_gray_600[i].classList.contains('bg-gray-600')) {
        bg_gray_600[i].classList.remove('bg-gray-600');
        bg_gray_600[i].classList.add('bg-gray-100');
      }
    }
    for (var i = 0; i < svg.length; i++) {
      if (svg[i].hasAttribute('fill')) {
        svg[i].setAttribute('fill', '#252f40');
      }
    }
    for (var i = 0; i < btn_text_white.length; i++) {
      if (!btn_text_white[i].closest('.card.bg-gradient-dark')) {
        btn_text_white[i].classList.remove('text-white');
        btn_text_white[i].classList.add('text-dark');
      }
    }
    for (var i = 0; i < card_border_dark.length; i++) {
      card_border_dark[i].classList.remove('border-dark');
    }
    el.removeAttribute("checked");
  }
};

// logut confirmation
const btnLogout = document.getElementById('btn-logout');
btnLogout.addEventListener('click',()=>{
  Swal.fire({
    title: 'Konfirmasi Keluar',
    text: 'Apakah Anda yakin ingin keluar?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#5e72e4',
    cancelButtonColor: '#f5365c',
    confirmButtonText: 'Ya, Keluar!',
    cancelButtonText: 'Batal',
  }).then((result) => {
    // Jika pengguna mengklik tombol "Ya, Logout!"
    if (result.isConfirmed) {
      // Lakukan submit form atau tindakan logout lainnya
      // Gantikan "formID" dengan ID formulir Anda
      document.getElementById('logout-form').submit();
    }
  });
})