// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
  // Mobile Menu Toggle
  const hamburger = document.querySelector('.hamburger');
  const navMenu = document.querySelector('.nav-menu');

  if (hamburger) {
      hamburger.addEventListener('click', () => {
          hamburger.classList.toggle('active');
          navMenu.classList.toggle('active');
      });
  }

  // Close mobile menu when clicking a nav link
  document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
          hamburger.classList.remove('active');
          navMenu.classList.remove('active');
      });
  });

  // Carousel functionality
  const carousel = document.querySelector('.carousel-container');
  const slides = document.querySelectorAll('.carousel-slide');
  const prevBtn = document.querySelector('.carousel-prev');
  const nextBtn = document.querySelector('.carousel-next');
  
  if (carousel && slides.length > 0) {
      let currentIndex = 0;
      const slideWidth = slides[0].clientWidth;
      const slidesCount = slides.length;
      let autoPlayInterval;

      // Set initial position
      updateCarouselPosition();

      // Event listeners for carousel buttons
      if (prevBtn) {
          prevBtn.addEventListener('click', () => {
              goToPrevSlide();
              resetAutoPlay();
          });
      }

      if (nextBtn) {
          nextBtn.addEventListener('click', () => {
              goToNextSlide();
              resetAutoPlay();
          });
      }

      // Auto-play the carousel
      startAutoPlay();

      // Functions for carousel
      function updateCarouselPosition() {
          // For mobile, show one slide at a time
          if (window.innerWidth <= 768) {
              carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
          } else {
              // For desktop, show 3 slides with the current one in the middle
              carousel.style.transform = `translateX(-${currentIndex * 33.33}%)`;
          }
      }

      function goToNextSlide() {
          currentIndex = (currentIndex + 1) % slidesCount;
          updateCarouselPosition();
      }

      function goToPrevSlide() {
          currentIndex = (currentIndex - 1 + slidesCount) % slidesCount;
          updateCarouselPosition();
      }

      function startAutoPlay() {
          autoPlayInterval = setInterval(goToNextSlide, 7000);
      }

      function resetAutoPlay() {
          clearInterval(autoPlayInterval);
          startAutoPlay();
      }

      // Handle window resize
      window.addEventListener('resize', updateCarouselPosition);
  }

  // Newsletter form submission
  const newsletterForm = document.getElementById('newsletter-form');
  if (newsletterForm) {
      newsletterForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const emailInput = this.querySelector('input[type="email"]');
          const email = emailInput.value;
          
          // Simulate API call
          console.log(`Newsletter subscription: ${email}`);
          
          // Show success message
          showToast('Thank you for subscribing!', 'success');
          
          // Reset form
          emailInput.value = '';
      });
  }

  // Add to cart functionality
  const addToCartButtons = document.querySelectorAll('.product-card .btn');
  const cartCountElement = document.querySelector('.cart-count');
  let cartCount = 0;

  addToCartButtons.forEach(button => {
      button.addEventListener('click', function() {
          // Get product info
          const productCard = this.closest('.product-card');
          const productName = productCard.querySelector('h3').textContent;
          const productPrice = productCard.querySelector('p').textContent;
          
          // Increment cart count
          cartCount++;
          if (cartCountElement) {
              cartCountElement.textContent = cartCount;
          }
          
          // Show success message
          showToast(`${productName} added to cart!`, 'success');
          
          // Simulate adding to cart
          console.log(`Added to cart: ${productName} - ${productPrice}`);
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