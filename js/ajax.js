function d_day_change(obj) {
  var value = obj.value;

  // AJAXリクエストを作成
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "disaster_info.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // レスポンスが受信された時の処理
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // レスポンスを取得
        var response = xhr.responseText;
        // HTML全体からtbody要素を抽出
        var tempDiv = document.createElement("div");
        tempDiv.innerHTML = response;
        var tbody = tempDiv.querySelector("#table-body");

        console.log(tbody);
        if (tbody) {
          // tbody要素を適切な場所に表示する
          var existingTbody = document.getElementById("table-body");
          existingTbody.innerHTML = tbody.innerHTML;
        }
      } else {
        // エラーを表示
        alert("エラーが発生しました");
      }
    }
  };
  // リクエストを送信
  xhr.send("d_selected_date=" + value);
}

function e_day_change(obj) {
  var value = obj.value;

  // AJAXリクエストを作成
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "everyday_life_info.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // レスポンスが受信された時の処理
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // レスポンスを取得
        var response = xhr.responseText;
        // HTML全体からtbody要素を抽出
        var tempDiv = document.createElement("div");
        tempDiv.innerHTML = response;
        var tbody = tempDiv.querySelector("#table-body");

        console.log(tbody);
        if (tbody) {
          // tbody要素を適切な場所に表示する
          var existingTbody = document.getElementById("table-body");
          existingTbody.innerHTML = tbody.innerHTML;
        }
      } else {
        // エラーを表示
        alert("エラーが発生しました");
      }
    }
  };
  // リクエストを送信
  xhr.send("e_selected_date=" + value);
}