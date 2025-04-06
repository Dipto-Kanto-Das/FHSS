// script.js
document.addEventListener("DOMContentLoaded", function () {
    console.log("Custom JS loaded!");
    // Add custom JS logic here
  });

  function toggleReadMore() {
    const shortText = document.getElementById("short-text");
    const fullText = document.getElementById("full-text");
    const button = event.target;

    if (fullText.classList.contains("d-none")) {
      shortText.classList.add("d-none");
      fullText.classList.remove("d-none");
      button.textContent = "Read Less";
    } else {
      shortText.classList.remove("d-none");
      fullText.classList.add("d-none");
      button.textContent = "Read More";
    }
 
  }