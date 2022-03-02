function toggleRepDisplay() {
  let bio = document.getElementById("repDisplay");
  if (bio.style.display === "none") {
    bio.style.display = "flex";
  } else {
    bio.style.display = "none";
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
  } else {
    bio.style.display = "none";
    show.style.display = "block";
    hide.style.display = "none";
  }
}

$(document).on('click', function (e) {
  e.stopPropagation();
  alert('test');
});
