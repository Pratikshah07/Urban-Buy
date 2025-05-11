// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
  // Mobile Menu Toggle
  const hamburger = document.querySelector('.hamburger');
  const navMenu = document.querySelector('.nav-menu');

  if (hamburger) {
      hamburger.addEventListener('click', function() {
          hamburger.classList.toggle('active');
          navMenu.classList.toggle('active');
      });
  }

  // Close mobile menu when clicking a nav link
  document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function() {
          hamburger.classList.remove('active');
          navMenu.classList.remove('active');
      });
  });

  // Carousel functionality
  const carousel = document.querySelector('.carousel-container');
  const carouselSlides = document.querySelectorAll('.carousel-slide');
  const prevBtn = document.querySelector('.carousel-prev');
  const nextBtn = document.querySelector('.carousel-next');
  let currentIndex = 0;
  let slideWidth = 0;
  let slidesToShow = 4;

  // Adjust slides to show based on screen width
  function updateSlidesToShow() {
      if (window.innerWidth < 768) {
          slidesToShow = 1;
      } else if (window.innerWidth < 992) {
          slidesToShow = 2;
      } else if (window.innerWidth < 1200) {
          slidesToShow = 3;
      } else {
          slidesToShow = 4;
      }
      
      slideWidth = carousel.clientWidth / slidesToShow;
      updateCarousel();
  }

  function updateCarousel() {
      if (!carousel) return;
      
      // Set width for each slide
      carouselSlides.forEach(slide => {
          slide.style.minWidth = `${slideWidth}px`;
      });

      // Transform carousel to show current slides
      carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
  }

  function moveNext() {
      if (currentIndex < carouselSlides.length - slidesToShow) {
          currentIndex++;
      } else {
          currentIndex = 0;
      }
      updateCarousel();
  }

  function movePrev() {
      if (currentIndex > 0) {
          currentIndex--;
      } else {
          currentIndex = carouselSlides.length - slidesToShow;
      }
      updateCarousel();
  }

  // Initialize carousel
  if (carousel && carouselSlides.length) {
      updateSlidesToShow();
      window.addEventListener('resize', updateSlidesToShow);

      // Add event listeners to buttons
      if (prevBtn) prevBtn.addEventListener('click', movePrev);
      if (nextBtn) nextBtn.addEventListener('click', moveNext);

      // Auto slide every 5 seconds
      setInterval(moveNext, 5000);
  }

  // Newsletter form submission
  const newsletterForm = document.getElementById('newsletter-form');
  if (newsletterForm) {
      newsletterForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const email = this.querySelector('input[type="email"]').value;
          
          // Send AJAX request
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'newsletter.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function() {
              if (this.status === 200) {
                  const response = JSON.parse(this.responseText);
                  alert(response.message);
                  if (response.status === 'success') {
                      newsletterForm.reset();
                  }
              }
          };
          xhr.send(`newsletter_email=${email}`);
      });
  }

  // Add to cart functionality
  const addToCartButtons = document.querySelectorAll('.add-to-cart, .add-to-cart-btn');
  addToCartButtons.forEach(button => {
      button.addEventListener('click', function() {
          const productId = this.getAttribute('data-id');
          const quantity = 1; // Default quantity
          
          // Send AJAX request
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'cart.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function() {
              if (this.status === 200) {
                  const response = JSON.parse(this.responseText);
                  alert(response.message);
                  if (response.status === 'success') {
                      // Update cart count in header
                      document.querySelector('.cart-count').textContent = response.cart_count;
                  }
              }
          };
          xhr.send(`action=add_to_cart&product_id=${productId}&quantity=${quantity}`);
      });
  });

  // Quick view functionality
  const quickViewButtons = document.querySelectorAll('.quick-view');
  quickViewButtons.forEach(button => {
      button.addEventListener('click', function() {
          const productId = this.getAttribute('data-id');
          // Implementation for quick view modal
          console.log('Quick view for product ID:', productId);
      });
  });

  // Product quantity controls
  const quantityInputs = document.querySelectorAll('.quantity-input');
  quantityInputs.forEach(input => {
      const decrementBtn = input.previousElementSibling;
      const incrementBtn = input.nextElementSibling;
      
      decrementBtn.addEventListener('click', function() {
          let value = parseInt(input.value);
          if (value > 1) {
              input.value = value - 1;
          }
      });
      
      incrementBtn.addEventListener('click', function() {
          let value = parseInt(input.value);
          input.value = value + 1;
      });
  });

  // Animation on scroll
  const animatedElements = document.querySelectorAll('.product-card, .category-card, .newsletter');
  
  // Add initial classes for animation
  animatedElements.forEach(el => {
      el.classList.add('opacity-0');
  });
  
  // Check if element is in viewport and add animation
  function checkScroll() {
      animatedElements.forEach(el => {
          const elementTop = el.getBoundingClientRect().top;
          const elementVisible = 150;
          
          if (elementTop < window.innerHeight - elementVisible) {
              el.classList.add('fade-in');
              el.classList.remove('opacity-0');
          }
      });
  }
  
  // Run on initial load
  checkScroll();
  
  // Listen for scroll events
  window.addEventListener('scroll', checkScroll);

  // Toast notification system
  function showToast(message, type = 'info') {
      // Create toast element
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.textContent = message;
      
      // Add toast to body
      document.body.appendChild(toast);
      
      // Show toast
      setTimeout(() => {
          toast.classList.add('show');
      }, 10);
      
      // Hide and remove toast after 3 seconds
      setTimeout(() => {
          toast.classList.remove('show');
          setTimeout(() => {
              document.body.removeChild(toast);
          }, 500);
      }, 3000);
  }

  // Add toast styles to the document
  const toastStyles = document.createElement('style');
  toastStyles.textContent = `
      .toast {
          position: fixed;
          bottom: 20px;
          right: 20px;
          padding: 12px 20px;
          background-color: #333;
          color: white;
          border-radius: 4px;
          opacity: 0;
          transform: translateY(20px);
          transition: opacity 0.3s, transform 0.3s;
          z-index: 1000;
          max-width: 300px;
      }
      
      .toast.show {
          opacity: 1;
          transform: translateY(0);
      }
      
      .toast-success {
          background-color: #4caf50;
      }
      
      .toast-error {
          background-color: #f44336;
      }
      
      .toast-info {
          background-color: #2196f3;
      }
  `;
  document.head.appendChild(toastStyles);

  // Simple product page navigation
  const productCards = document.querySelectorAll('.product-card');
  productCards.forEach(card => {
      card.addEventListener('click', function(e) {
          // Don't navigate if they clicked on the button
          if (e.target.tagName !== 'BUTTON') {
              const productName = this.querySelector('h3').textContent;
              // Replace spaces with hyphens and convert to lowercase for URL
              const productSlug = productName.toLowerCase().replace(/ /g, '-');
              console.log(`Navigating to product: ${productSlug}`);
              // In a real app, you would navigate to the product page
              // window.location.href = `/product/${productSlug}`;
              
              // For demo, just show a message
              showToast(`Viewing ${productName}`, 'info');
          }
      });
  });

  // Add opacity class to body for initial load animation
  document.body.classList.add('opacity-0');
  
  // Trigger page load animation
  setTimeout(() => {
      document.body.classList.add('fade-in');
      document.body.classList.remove('opacity-0');
  }, 10);
});