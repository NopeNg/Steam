// Khởi tạo mảng ảnh trống (sẽ được map dữ liệu khi trang web load xong)
let galleryImages = [];
let currentIndex = 0;
let slideTimer = null;

const thumbContainer = document.getElementById('steam-thumb-container');
const sliderTrack = document.getElementById('steam-slider-track');
const sliderHandle = document.getElementById('steam-slider-handle');

let isDragging = false;
let startX = 0;
let startLeft = 0;

function updateGallery() {
    const mainImage = document.getElementById('main-game-image');
    const thumbnails = document.querySelectorAll('.screenshot-thumbnail');

    if (!mainImage || galleryImages.length === 0) return;

    mainImage.src = galleryImages[currentIndex];

    thumbnails.forEach((img, index) => {
        if (index === currentIndex) {
            img.classList.remove('opacity-70', 'border-transparent');
            img.classList.add('opacity-100', 'border-white');
            img.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else {
            img.classList.remove('opacity-100', 'border-white');
            img.classList.add('opacity-70', 'border-transparent');
        }
    });
}

// Xử lý kéo thả thanh cuộn (Scrollbar)
if (thumbContainer && sliderTrack && sliderHandle) {
    thumbContainer.addEventListener('scroll', () => {
        if (isDragging) return;
        const maxScrollLeft = thumbContainer.scrollWidth - thumbContainer.clientWidth;
        if (maxScrollLeft > 0) {
            const scrollPercentage = thumbContainer.scrollLeft / maxScrollLeft;
            const maxTrackLeft = sliderTrack.clientWidth - sliderHandle.clientWidth;
            sliderHandle.style.left = `${scrollPercentage * maxTrackLeft}px`;
        }
    });

    sliderHandle.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.clientX;
        startLeft = sliderHandle.offsetLeft;
        document.body.style.userSelect = 'none';
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;

        const deltaX = e.clientX - startX;
        let newLeft = startLeft + deltaX;
        const maxTrackLeft = sliderTrack.clientWidth - sliderHandle.clientWidth;

        if (newLeft < 0) newLeft = 0;
        if (newLeft > maxTrackLeft) newLeft = maxTrackLeft;

        sliderHandle.style.left = `${newLeft}px`;

        const scrollPercentage = newLeft / maxTrackLeft;
        const maxScrollLeft = thumbContainer.scrollWidth - thumbContainer.clientWidth;
        thumbContainer.scrollLeft = scrollPercentage * maxScrollLeft;
    });

    document.addEventListener('mouseup', () => {
        if (isDragging) {
            isDragging = false;
            document.body.style.userSelect = '';
            resetAutoPlay();
        }
    });

    sliderTrack.addEventListener('click', (e) => {
        if (e.target === sliderHandle) return;

        const rect = sliderTrack.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        let targetLeft = clickX - (sliderHandle.clientWidth / 2);
        const maxTrackLeft = sliderTrack.clientWidth - sliderHandle.clientWidth;

        if (targetLeft < 0) targetLeft = 0;
        if (targetLeft > maxTrackLeft) targetLeft = maxTrackLeft;

        sliderHandle.style.left = `${targetLeft}px`;

        const scrollPercentage = targetLeft / maxTrackLeft;
        const maxScrollLeft = thumbContainer.scrollWidth - thumbContainer.clientWidth;
        
        thumbContainer.scrollTo({
            left: scrollPercentage * maxScrollLeft,
            behavior: 'smooth'
        });
        resetAutoPlay();
    });
}

function scrollThumbsHandled(direction) {
    if (!thumbContainer) return;
    const step = 150;
    if (direction === 'left') {
        thumbContainer.scrollBy({ left: -step, behavior: 'smooth' });
    } else {
        thumbContainer.scrollBy({ left: step, behavior: 'smooth' });
    }
    resetAutoPlay();
}

function nextImage() {
    if (galleryImages.length === 0) return;
    currentIndex = (currentIndex + 1) % galleryImages.length;
    updateGallery();
    resetAutoPlay();
}

function prevImage() {
    if (galleryImages.length === 0) return;
    currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
    updateGallery();
    resetAutoPlay();
}

function jumpToImage(index) {
    currentIndex = index;
    updateGallery();
    resetAutoPlay();
}

// --- LƯU Ý QUAN TRỌNG VỚI VITE ---
// Vì Vite gom module riêng tư, ta phải treo các hàm onclick lên đối tượng window 
// để các nút HTML ở ngoài Blade có thể kích hoạt được chúng.
window.scrollThumbsHandled = scrollThumbsHandled;
window.nextImage = nextImage;
window.prevImage = prevImage;
window.jumpToImage = jumpToImage;

function startAutoPlay() {
    slideTimer = setInterval(nextImage, 5000);
}

function resetAutoPlay() {
    clearInterval(slideTimer);
    startAutoPlay();
}

// Đợi DOM dựng xong để đọc mảng ảnh từ HTML truyền qua
document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.getElementById('game-gallery-wrapper');
    if (wrapper && wrapper.dataset.images) {
        galleryImages = JSON.parse(wrapper.dataset.images);
    }
    startAutoPlay();
});