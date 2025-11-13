/**
* Template Name: TheProperty
* Template URL: https://bootstrapmade.com/theproperty-bootstrap-real-estate-template/
* Updated: Aug 05 2025 with Bootstrap v5.3.7
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Product Image Zoom and Thumbnail Functionality
   */

  function productDetailFeatures() {
    // Initialize Drift for image zoom
    function initDriftZoom() {
      // Check if Drift is available
      if (typeof Drift === 'undefined') {
        console.error('Drift library is not loaded');
        return;
      }

      const driftOptions = {
        paneContainer: document.querySelector('.image-zoom-container'),
        inlinePane: window.innerWidth < 768 ? true : false,
        inlineOffsetY: -85,
        containInline: true,
        hoverBoundingBox: false,
        zoomFactor: 3,
        handleTouch: false
      };

      // Initialize Drift on the main product image
      const mainImage = document.getElementById('main-product-image');
      if (mainImage) {
        new Drift(mainImage, driftOptions);
      }
    }

    // Thumbnail click functionality
    function initThumbnailClick() {
      const thumbnails = document.querySelectorAll('.thumbnail-item');
      const mainImage = document.getElementById('main-product-image');

      if (!thumbnails.length || !mainImage) return;

      thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
          // Get image path from data attribute
          const imageSrc = this.getAttribute('data-image');

          // Update main image src and zoom attribute
          mainImage.src = imageSrc;
          mainImage.setAttribute('data-zoom', imageSrc);

          // Update active state
          thumbnails.forEach(item => item.classList.remove('active'));
          this.classList.add('active');

          // Reinitialize Drift for the new image
          initDriftZoom();
        });
      });
    }

    // Image navigation functionality (prev/next buttons)
    function initImageNavigation() {
      const prevButton = document.querySelector('.image-nav-btn.prev-image');
      const nextButton = document.querySelector('.image-nav-btn.next-image');

      if (!prevButton || !nextButton) return;

      const thumbnails = Array.from(document.querySelectorAll('.thumbnail-item'));
      if (!thumbnails.length) return;

      // Function to navigate to previous or next image
      function navigateImage(direction) {
        // Find the currently active thumbnail
        const activeIndex = thumbnails.findIndex(thumb => thumb.classList.contains('active'));
        if (activeIndex === -1) return;

        let newIndex;
        if (direction === 'prev') {
          // Go to previous image or loop to the last one
          newIndex = activeIndex === 0 ? thumbnails.length - 1 : activeIndex - 1;
        } else {
          // Go to next image or loop to the first one
          newIndex = activeIndex === thumbnails.length - 1 ? 0 : activeIndex + 1;
        }

        // Simulate click on the new thumbnail
        thumbnails[newIndex].click();
      }

      // Add event listeners to navigation buttons
      prevButton.addEventListener('click', () => navigateImage('prev'));
      nextButton.addEventListener('click', () => navigateImage('next'));
    }

    // Initialize all features
    initDriftZoom();
    initThumbnailClick();
    initImageNavigation();
  }

  productDetailFeatures();





 const navItems = document.querySelectorAll('.bottom-nav .nav-item');
  const currentPage = window.location.pathname.split("/").pop(); // get current file name

  navItems.forEach(item => {
    const link = item.getAttribute('data-link');
    if(link === currentPage){
      item.classList.add('settings'); // add active class
    }

    // redirect on click
    item.addEventListener('click', () => {
      window.location.href = link;
    });
  });



const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');
  const toggleLink = document.getElementById('toggleForm');
  const modalTitle = document.getElementById('modalTitle');

  // Switch forms
  toggleLink.addEventListener('click', (e) => {
    e.preventDefault();
    if (loginForm.classList.contains('d-none')) {
      signupForm.classList.add('d-none');
      loginForm.classList.remove('d-none');
      modalTitle.textContent = 'Login';
      toggleLink.textContent = 'Don’t have an account? Signup';
    } else {
      loginForm.classList.add('d-none');
      signupForm.classList.remove('d-none');
      modalTitle.textContent = 'Signup';
      toggleLink.textContent = 'Already have an account? Login';
    }
  });

  // Show error helper
  function showError(id, msg) {
    const el = document.getElementById(id);
    el.textContent = msg;
    el.classList.remove('d-none');
  }

  function clearErrors(formType) {
    document.querySelectorAll(`#${formType} small`).forEach(el => {
      el.classList.add('d-none');
      el.textContent = '';
    });
  }

  // Validation
  function validateForm(nameId, mobileId, userTypeId, formType) {
    clearErrors(formType);

    const name = document.getElementById(nameId).value.trim();
    const mobile = document.getElementById(mobileId).value.trim();
    const userType = document.getElementById(userTypeId).value;
    let valid = true;

    if (name.length < 3) {
      showError(nameId + 'Error', 'Name must be at least 3 letters long');
      valid = false;
    }
    if (!/^[0-9]{10}$/.test(mobile)) {
      showError(mobileId + 'Error', 'Mobile must be exactly 10 digits');
      valid = false;
    }
    if (!userType) {
      showError(userTypeId + 'Error', 'Please select a user type');
      valid = false;
    }
    return valid;
  }

  // Login submit
  loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (validateForm('loginName', 'loginMobile', 'loginUserType', 'loginForm')) {
      this.reset();
      bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
    }
  });

  // Signup submit
  signupForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (validateForm('signupName', 'signupMobile', 'signupUserType', 'signupForm')) {
      this.reset();
      bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
    }
  });



})();