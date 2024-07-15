function openBox() {
  document.getElementById("overlay").style.display = "block";
}

function closeBox() {
  document.getElementById("overlay").style.display = "none";
}

function cancel() {
  alert("Cancelled");
  closeBox();
}

function confirm() {
  alert("Confirmed");
  closeBox();
}