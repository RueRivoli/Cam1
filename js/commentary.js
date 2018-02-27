
function  saveCommentary() {
  var commentary = document.getElementById("valuecom").value;
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
  if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
      alert(xhr.responseText);
  }
}
xhr.open("POST", "script/saveComment.php", false);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.send('com='+commentary);
}
