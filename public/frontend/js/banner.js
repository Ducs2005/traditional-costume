document.addEventListener('DOMContentLoaded', function () {
    console.log(baseUrl);
    let bannerIndex = 0; // Start at the first banner
    const banners = [
        baseUrl + "/frontend/img/banner/56.jpg",
        baseUrl + "/frontend/img/banner/banner7.png",
        baseUrl + "/frontend/img/banner/banner8.png",
        baseUrl + "/frontend/img/banner/banner6.png"
    ];
 
    const bannerTexts = [
        {
            title: 'Ao Dai - A Symbol of Vietnamese Culture',
            description: 'The Ao Dai is a traditional Vietnamese garment known for its elegance and graceful silhouette, typically worn on formal occasions and national celebrations.',
        },
        {
            title: 'Elegance in Modern Ao Dai',
            description: 'Modern Ao Dai blends tradition with contemporary styles, showcasing the beauty of Vietnamese culture through vibrant colors and innovative designs.',
        },
        {
            title: 'Ao Dai - Timeless Beauty',
            description: 'The timeless beauty of Ao Dai has remained a symbol of Vietnamese culture, evolving with modern fashion while retaining its classic charm.',
        },
        {
            title: 'Ao Dai - Timeless Beauty',
            description: 'The timeless beauty of Ao Dai has remained a symbol of Vietnamese culture, evolving with modern fashion while retaining its classic charm.',
        }
    ];

    function changeBanner() {
        bannerIndex = (bannerIndex + 1) % banners.length; // Cycle through the banners
        const bannerImage = document.querySelector('div.bannerList img');
        bannerImage.src = banners[bannerIndex]; // Update the image source
        updateBannerText(); // Update text
        updateDots(); // Update dots
    }

    function updateBannerText() {
        const titleElement = document.querySelector('.banner-content h1');
        const descriptionElement = document.querySelector('.banner-content p');

        titleElement.textContent = bannerTexts[bannerIndex].title;
        descriptionElement.textContent = bannerTexts[bannerIndex].description;
    }

    function updateDots() {
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === bannerIndex);
        });
    }

    function goToBanner(index) {
        bannerIndex = index;
        document.querySelector('.bannerList img').src = banners[bannerIndex];
        updateBannerText(); // Update text for the clicked dot
        updateDots(); // Update dot indicator
    }

    document.querySelectorAll('.dot').forEach((dot, index) => {
        dot.addEventListener('click', () => goToBanner(index));
    });

    setInterval(changeBanner, 3000); // Automatically change banner every 5 seconds

    updateDots(); // Initialize dot indicator
    updateBannerText(); // Initialize banner text
});
