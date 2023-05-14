const openModal = document.getElementById("open-modal");
const closeModal = document.getElementById("close-modal");
const modal = document.getElementById("modal");

openModal.addEventListener("click", (event) => {
  event.stopPropagation(); // クリックイベントの伝播を停止
  modal.classList.remove("hidden");
  modal.classList.add("open");
});

closeModal.addEventListener("click", () => {
  modal.classList.remove("open");
  modal.classList.add("hidden");
});
