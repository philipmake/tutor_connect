const hamburger = document.getElementById('hamburger');
const navLinks = document.querySelector('.navlinks');

hamburger.addEventListener('click', () => {
  navLinks.classList.toggle('active');
});
