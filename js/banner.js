const bannerImages = Array.from(document.querySelectorAll('.banner-image'));

let i = 0;
let id;

function startBanner() {
    id = setInterval(nextImage, 4000); // 4sec
}

function nextImage() {
    bannerImages[i].style.opacity = '0';
    i = (i + 1) % bannerImages.length;
    bannerImages[i].style.opacity = '1';
}

startBanner();