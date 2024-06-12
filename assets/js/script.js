let userBox = document.querySelector('.header .flex .account-box');

document.querySelector('#user-btn').onclick = () =>{
    userBox.classList.toggle('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    userBox.classList.remove('active');
}

window.onscroll = () =>{
    userBox.classList.remove('active');
    navbar.classList.remove('active');
}

// Featured phones auto play and pause
var video = document.getElementById("specialBannerVideo");

function checkVideoVisibility() {
    var rect = video.getBoundingClientRect();
    var windowHeight = window.innerHeight || document.documentElement.clientHeight;

    if (rect.top <= windowHeight && rect.bottom >= 0.6 * windowHeight) {
        video.play();
    } else {
        video.pause();
    }
}

window.addEventListener("scroll", checkVideoVisibility);

checkVideoVisibility();

video.addEventListener("ended", function() {
    video.currentTime = 0;
    video.play();
});
