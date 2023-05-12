const password_eye = document.querySelector("#password_eye");
password_eye.addEventListener("click", () => {
  const password = document.querySelector("#emp_password");
  if (password.type === "password") {
    password.type = "text";
    password_eye.classList.remove("fa-eye");
    password_eye.classList.add("fa-eye-slash");
  } else {
    password.type = "password";
    password_eye.classList.remove("fa-eye-slash");
    password_eye.classList.add("fa-eye");
  }
});
