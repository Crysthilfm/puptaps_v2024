window.onscroll = function() {scrollFunction()};

$( document ).ready(function() {
  document.getElementById("navbar").style.background = "none";
  document.querySelectorAll(".nav-link").forEach(el => el.style.color = "#ffffff");
  document.getElementById("navbar-brand").style.color = "#ffffff";
  document.getElementById("navbar-brand-sm").style.color = "#ffffff";
  document.getElementById("navbar").style.boxShadow = "none";
});

function scrollFunction() {
  if (document.body.scrollTop > 5 || document.documentElement.scrollTop > 5) {
    document.getElementById("navbar").style.background = "#ffffff";
    document.querySelectorAll(".nav-link").forEach(el => el.style.color = "#000000");
    document.getElementById("navbar-brand").style.color = "#000000";
    document.getElementById("navbar-brand-sm").style.color = "#000000";
    document.getElementById("navbar").style.boxShadow = "rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px";
  } else {
    document.getElementById("navbar").style.background = "none";
    document.querySelectorAll(".nav-link").forEach(el => el.style.color = "#ffffff");
    document.getElementById("navbar-brand").style.color = "#ffffff";
    document.getElementById("navbar-brand-sm").style.color = "#ffffff";
    document.getElementById("navbar").style.boxShadow = "none";
  }
}
