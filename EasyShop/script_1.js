
const loginBtn = document.getElementById('loginToggle');
const signupBtn = document.getElementById('signupToggle');
const loginForm = document.getElementById('loginForm');
const signupForm = document.getElementById('signupForm');
const goToSignUp = document.getElementById('goToSignUp');
const goToLogin = document.getElementById('goToLogin');

// Button tab switch
loginBtn.addEventListener('click', () => {
  loginBtn.classList.add('active');
  signupBtn.classList.remove('active');
  loginForm.classList.add('active-form');
  signupForm.classList.remove('active-form');
});

signupBtn.addEventListener('click', () => {
  signupBtn.classList.add('active');
  loginBtn.classList.remove('active');
  signupForm.classList.add('active-form');
  loginForm.classList.remove('active-form');
});

// Text link switch
goToSignUp.addEventListener('click', () => signupBtn.click());
goToLogin.addEventListener('click', () => loginBtn.click());