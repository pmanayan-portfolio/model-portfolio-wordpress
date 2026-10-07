document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.site-header');
  const menuToggle = document.querySelector('.menu-toggle');
  const navLinks = document.querySelectorAll('.nav a');
  const filters = document.querySelectorAll('.filter');
  const galleryItems = document.querySelectorAll('.gallery-item');
  const lightbox = document.querySelector('#lightbox');
  const lightboxImage = lightbox ? lightbox.querySelector('img') : null;
  const closeLightbox = lightbox ? lightbox.querySelector('.lightbox-close') : null;
  const zoomable = document.querySelectorAll('[data-full]');

  const updateHeader = () => {
    if (header) header.classList.toggle('is-scrolled', window.scrollY > 20);
  };
  updateHeader();
  window.addEventListener('scroll', updateHeader, { passive: true });

  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      const isOpen = document.body.classList.toggle('menu-open');
      menuToggle.setAttribute('aria-expanded', String(isOpen));
    });
  }

  navLinks.forEach(link => link.addEventListener('click', () => {
    document.body.classList.remove('menu-open');
    if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
  }));

  filters.forEach(button => {
    button.addEventListener('click', () => {
      const target = button.dataset.filter;
      filters.forEach(btn => btn.classList.toggle('is-active', btn === button));
      galleryItems.forEach(item => {
        const categories = (item.dataset.category || '').split(' ');
        const show = target === 'all' || categories.includes(target);
        item.classList.toggle('is-hidden', !show);
      });
    });
  });

  if (lightbox && lightboxImage) {
    zoomable.forEach(item => {
      item.addEventListener('click', () => {
        lightboxImage.src = item.dataset.full;
        if (typeof lightbox.showModal === 'function') lightbox.showModal();
      });
    });
    if (closeLightbox) closeLightbox.addEventListener('click', () => lightbox.close());
    lightbox.addEventListener('click', event => {
      if (event.target === lightbox) lightbox.close();
    });
  }

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -30px' });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  } else {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('is-visible'));
  }
});
