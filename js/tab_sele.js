function change(event, tabID) {
  let element = event.target;
  // 最初のaタグを取得
  while (element.nodeName !== "A") {
    element = element.parentNode;
  }
  // a要素の親の親要素を取得
  ulElement = element.parentNode.parentNode;
  // 取得した要素の中のliの中から全てのaタグを取得
  aElements = ulElement.querySelectorAll("li > a");
  // 取得したidの中の(tab-content)の下のdiv取得
  tabContents = document.getElementById("tab-id").querySelectorAll(".tab-content > div");
  for (let i = 0; i < aElements.length; i++) {
    aElements[i].classList.remove("text-white", "bg-blue-600");
    aElements[i].classList.add("text-blue-600", "bg-white");
    tabContents[i].classList.add("hidden");
    tabContents[i].classList.remove("block");
  }
  element.classList.remove("text-blue-600", "bg-white");
  element.classList.add("text-white", "bg-blue-600");
  document.getElementById(tabID).classList.remove("hidden");
  document.getElementById(tabID).classList.add("block");
}
