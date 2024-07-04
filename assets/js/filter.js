const filterList = document.querySelectorAll(".filter-list .filter-btn");
const productList = document.querySelectorAll(".product-list .product-item");

const filterCards = (e) => {
    const filter = e.target.dataset.name;
    document.querySelector(".filter-btn.active").classList.remove("active");
    e.target.classList.add("active");

    productList.forEach((item) => {
        if (filter === "all" || item.dataset.category === filter) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
};

filterList.forEach(button => button.addEventListener("click", filterCards));