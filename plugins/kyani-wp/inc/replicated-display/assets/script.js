function toggleRepDisplay() {
  let bio = document.getElementById("repDisplay");
  let content = document.getElementById("main");
  let newHeight = document.getElementById("repDisplay").clientHeight;
  if (bio.style.display === "none") {
    bio.style.display = "flex";
    newHeight = document.getElementById("repDisplay").clientHeight;
    content.style.marginTop = newHeight + "px";
  } else {
    bio.style.display = "none";
    newHeight = document.getElementById("repDisplay").clientHeight;
    content.style.marginTop = newHeight + "px";
  }
}

function toggleBio() {
  let bio = document.getElementById("repBio");
  let show = document.getElementById("viewBio");
  let hide = document.getElementById("hideBio");
  if (bio.style.display === "none") {
    bio.style.display = "block";
    show.style.display = "none";
    hide.style.display = "block";
    newHeight = document.getElementById("repDisplay").clientHeight;
    content.style.marginTop = newHeight + "px";
  } else {
    bio.style.display = "none";
    show.style.display = "block";
    hide.style.display = "none";
    newHeight = document.getElementById("repDisplay").clientHeight;
    content.style.marginTop = newHeight + "px";
  }
}

$(document).on('click', function (e) {
  e.stopPropagation();
});
