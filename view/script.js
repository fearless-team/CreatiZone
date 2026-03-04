document.addEventListener('DOMContentLoaded', () => {

  // Navbar scroll
  window.addEventListener('scroll', () => {
    document.getElementById('navbar')?.classList.toggle('scrolled', window.scrollY > 40);
    document.getElementById('scrollTop')?.classList.toggle('visible', window.scrollY > 300);
  });
  document.getElementById('scrollTop')?.addEventListener('click', () =>
    window.scrollTo({ top: 0, behavior: 'smooth' })
  );

  // Char counter description
  const desc = document.getElementById('description');
  if (desc) {
    const update = () => {
      const c = document.getElementById('descCounter');
      if (!c) return;
      const n = desc.value.length;
      c.textContent = n + ' / 2000';
      c.className = 'char-counter' + (n > 1800 ? ' warn' : '') + (n >= 2000 ? ' over' : '');
    };
    update();
    desc.addEventListener('input', update);
  }

  // Image preview
  document.getElementById('image')?.addEventListener('change', function () {
    const p = document.getElementById('imgPreview');
    if (!p || !this.files[0]) return;
    const r = new FileReader();
    r.onload = e => { p.src = e.target.result; p.style.display = 'block'; };
    r.readAsDataURL(this.files[0]);
  });

  // File drop zone
  const dz = document.getElementById('fileDropZone');
  if (dz) {
    ['dragover', 'dragenter'].forEach(ev => dz.addEventListener(ev, e => {
      e.preventDefault();
      dz.style.borderColor = 'var(--green-2)';
      dz.style.background  = 'var(--accent-pale)';
    }));
    ['dragleave', 'drop'].forEach(ev => dz.addEventListener(ev, () => {
      dz.style.borderColor = '';
      dz.style.background  = '';
    }));
  }

  // Submit loading
  document.getElementById('createForm')?.addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Publication en cours...'; }
  });

  // Focus rings
  document.querySelectorAll('input, textarea').forEach(el => {
    el.addEventListener('focus', () => el.closest('.form-group')?.classList.add('focused'));
    el.addEventListener('blur',  () => el.closest('.form-group')?.classList.remove('focused'));
  });

  // Auto-dismiss alerts
  document.querySelectorAll('.alert-success').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .5s, max-height .5s, padding .5s, margin .5s';
      el.style.opacity = '0'; el.style.maxHeight = '0';
      el.style.overflow = 'hidden'; el.style.padding = '0'; el.style.margin = '0';
      setTimeout(() => el.remove(), 500);
    }, 4000);
  });

  // Vote — visuel seulement
  document.querySelectorAll('.vote-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const wasVoted = btn.classList.contains('voted');
      btn.classList.toggle('voted');

      const badge = btn.querySelector('.vote-badge');
      if (badge) {
        const n = parseInt(badge.textContent) || 0;
        badge.textContent = wasVoted ? Math.max(0, n - 1) : n + 1;
        badge.classList.remove('bump');
        void badge.offsetWidth;
        badge.classList.add('bump');
      }

      if (!wasVoted) {
        const star = btn.querySelector('.star-icon');
        if (star) {
          star.style.animation = 'none';
          requestAnimationFrame(() => requestAnimationFrame(() => {
            star.style.animation = 'starPop .35s cubic-bezier(.4,0,.2,1)';
            star.addEventListener('animationend', () => {
              star.style.animation = '';
            }, { once: true });
          }));
        }
      }
    });
  });

  // Commenter — flash visuel
  document.querySelectorAll('.comment-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.style.background  = 'var(--accent-pale)';
      btn.style.borderColor = 'var(--green-3)';
      setTimeout(() => {
        btn.style.background  = '';
        btn.style.borderColor = '';
      }, 300);
    });
  });

});