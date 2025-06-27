document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contact-form");
  const messageBox = document.getElementById("form-message");

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    messageBox.textContent = "Terima kasih! Pesanmu telah dikirim (simulasi).";
    form.reset();
  });
});
