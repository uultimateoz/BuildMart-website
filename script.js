// Mobile menu toggle
let menuBtn = document.querySelector('#menu-btn');
let navbar = document.querySelector('.navbar');

if(menuBtn) {
   menuBtn.onclick = () => {
      navbar.classList.toggle('active');
   }
}

// User profile dropdown
let userBtn = document.querySelector('#user-btn');
let profile = document.querySelector('.profile');

if(userBtn) {
   userBtn.onclick = () => {
      profile.classList.toggle('active');
   }
}

// Close message after 3 seconds
setTimeout(() => {
   const messages = document.querySelectorAll('.message');
   messages.forEach(message => {
      message.remove();
   });
}, 3000);