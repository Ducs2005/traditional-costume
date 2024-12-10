document.addEventListener("DOMContentLoaded", () => {
    const brand = document.querySelector(".brand_container");
    const brandLogo = Array.from(brand.children);

    brandLogo.forEach((item) => {
        const duplicateNode = item.cloneNode(true);
        brand.appendChild(duplicateNode);
    });
});
