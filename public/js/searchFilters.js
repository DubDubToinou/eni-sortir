const filterButton = document.querySelector('.filterButton');
const filters = document.querySelector('.filters');

filterButton.addEventListener("click", () => {
    filters.classList.toggle('active');
})