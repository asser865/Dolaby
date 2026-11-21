// unified-script.js — combined interactions for all project pages (including rating stars)
(function () {
  'use strict';

  // --- Tiny Helpers ---
  const $ = (s, root = document) => root.querySelector(s);
  const $$ = (s, root = document) => Array.from(root.querySelectorAll(s));
  const isHash = (href) => typeof href === 'string' && href.startsWith('#');

  document.addEventListener('DOMContentLoaded', () => {

    // -------------------------
    // 1) Login: "Login as a Brand Owner" -> brandowner.html
    // -------------------------
    (function brandOwnerRedirect() {
      const brandOwnerLink = $('#brandOwnerLogin') || $('.brand-owner');
      if (!brandOwnerLink) return;

      brandOwnerLink.addEventListener('click', (e) => {
        const href = brandOwnerLink.getAttribute('href');
        if (href === '#' || href === null || href.trim() === '') {
          e.preventDefault();
          window.location.href = 'brandowner.html';
        }
      });
    })();


    // -------------------------
    // 2) Modal (Create Campaign) + step flow + filtering
    // -------------------------
    (function modalAndFilters() {
      const modal = $('#modal');
      const statusFilter = $('#statusFilter');
      const q = $('#q');
      const listItems = $$('.list .campaign');

      // --- Filter Logic (runs on pages with campaign list, even if no modal) ---
      const applyFilters = () => {
        const status = statusFilter ? statusFilter.value : '';
        const query = q ? q.value.trim().toLowerCase() : '';
        
        listItems.forEach(item => {
          const titleEl = item.querySelector('.campaign-title');
          const title = titleEl ? titleEl.textContent.toLowerCase() : '';
          const itemStatus = item.getAttribute('data-status') || '';
          
          const matchesStatus = !status || itemStatus === status;
          const matchesQuery = !query || title.includes(query);
          
          item.style.display = (matchesStatus && matchesQuery) ? '' : 'none';
        });
      };
      if (statusFilter) statusFilter.addEventListener('change', applyFilters);
      if (q) q.addEventListener('input', applyFilters);

      // --- Modal Logic (runs only on pages with the modal) ---
      if (!modal) return;
      
      const createBtn = $('#createBtn');
      const closeModal = $('#closeModal');
      const cancelBtn = $('#cancelBtn');
      const nextBtn = $('#nextBtn');
      const prevBtn = $('#prevBtn');
      const publishBtn = $('#publishBtn');
      const productSelection = $('#productSelection');
      const review = $('#review');
      const form = $('#campaignForm');
      const steps = $$('.flow .step');

      // Escape helper to avoid inserting raw values into review
      function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, (m) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
      }

      let stepIndex = 0;
      function showStep() {
        steps.forEach((s, i) => s.classList.toggle('active', i === stepIndex));
        
        // Step visibility logic
        if (productSelection) productSelection.style.display = stepIndex === 1 ? '' : 'none';
        if (review) review.style.display = stepIndex === 2 ? '' : 'none';

        if (prevBtn) prevBtn.style.display = stepIndex > 0 ? '' : 'none';
        if (nextBtn) nextBtn.style.display = stepIndex < steps.length - 1 ? '' : 'none';
        if (publishBtn) publishBtn.style.display = stepIndex === steps.length - 1 ? '' : 'none';
        
        // Populate review summary
        if (stepIndex === 2 && $('#reviewOutput')) {
          const nameEl = $('#campaignName');
          const typeEl = $('#discountType');
          const startEl = $('#startDate');
          const endEl = $('#endDate');
          const valEl = $('#discountValue');
          const name = nameEl ? nameEl.value || '(no name)' : '(no name)';
          const type = typeEl ? typeEl.value : '';
          const start = startEl ? startEl.value || ' — ' : ' — ';
          const end = endEl ? endEl.value || ' — ' : ' — ';
          const val = valEl ? valEl.value || '(not set)' : '(not set)';
          const out = $('#reviewOutput');
          
          out.innerHTML = `
            <div><strong>Campaign:</strong> ${escapeHtml(name)}</div>
            <div><strong>Discount:</strong> ${escapeHtml(type)} — ${escapeHtml(val)}</div>
            <div><strong>Dates:</strong> ${escapeHtml(start)} → ${escapeHtml(end)}</div>
          `;
        }
      }

      function openModal() {
        modal.style.display = 'flex';
        stepIndex = 0;
        showStep();
      }
      function closeModalFn() {
        modal.style.display = 'none';
        try { if (form) form.reset(); } catch (err) { /* ignore reset error */ }
        if (productSelection) productSelection.style.display = 'none';
        if (review) review.style.display = 'none';
        steps.forEach(s => s.classList.remove('active'));
      }

      if (createBtn) createBtn.addEventListener('click', openModal);
      if (closeModal) closeModal.addEventListener('click', closeModalFn);
      if (cancelBtn) cancelBtn.addEventListener('click', closeModalFn);

      if (nextBtn) nextBtn.addEventListener('click', () => { if (stepIndex < steps.length - 1) stepIndex++; showStep(); });
      if (prevBtn) prevBtn.addEventListener('click', () => { if (stepIndex > 0) stepIndex--; showStep(); });

      // publish: form submit
      if (form) form.addEventListener('submit', (e) => {
        e.preventDefault();
        closeModalFn();
        window.alert('Campaign published (demo). Integrate with backend to persist.');
      });

      // esc to close
      window.addEventListener('keydown', (ev) => { 
        if (ev.key === 'Escape' && modal.style.display === 'flex') closeModalFn(); 
      });

    })();


    // -------------------------
    // 3) Nav behaviour — Simple active state for new horizontal menu
    // -------------------------
    (function navBehavior() {
      const nav = $('.header-nav');
      if (!nav) return;

      nav.addEventListener('click', (ev) => {
        const anchor = ev.target.closest('a');
        if (!anchor) return;

        const currentPath = window.location.pathname.split('/').pop().toLowerCase() || 'index.html';
        const targetHref = (anchor.getAttribute('href') || '').split('/').pop().toLowerCase() || '';

        nav.querySelectorAll('a').forEach(a => {
            a.classList.remove('active');
            a.removeAttribute('aria-current');
        });
        
        if (targetHref === currentPath || (targetHref === '#' && currentPath === 'index.html')) {
            anchor.classList.add('active');
            anchor.setAttribute('aria-current', 'page');
        }
      });
      
      const currentPath = window.location.pathname.split('/').pop().toLowerCase() || 'index.html';
      const initialMap = {
          'brandowner.html': 'Home',
          'discount.html': 'Shop',
          'analysis.html': 'Shop'
      };
      
      const defaultActiveText = initialMap[currentPath] || null;

      if (defaultActiveText) {
          const defaultLink = $$('.header-nav a').find(a => a.textContent.trim() === defaultActiveText);
          if (defaultLink) {
              defaultLink.classList.add('active');
              defaultLink.setAttribute('aria-current', 'page');
          }
      }
      
    })();


    // -------------------------
    // 4) Sales SVG drawing — safe and corrected
    // -------------------------
    (function drawSalesBars() {
      const svg = document.getElementById('salesSvg');
      if (!svg) return;
      
      const salesData = [
        { month: 'Jul', value: 4200 },
        { month: 'Aug', value: 5600 },
        { month: 'Sep', value: 6800 },
        { month: 'Oct', value: 7400 },
        { month: 'Nov', value: 8600 },
        { month: 'Dec', value: 10200 }
      ];

      const BAR_COLOR = '#00B8D4';
      const HOVER_COLOR = '#008394'; 
      const TEXT_COLOR = '#6B7280';
      const GRID_COLOR = 'rgba(0,0,0,0.06)';

      const barsGroup = $('#bars');
      const gridGroup = $('#gridlines');
      const xTicksGroup = $('#xTicks');
      const yTicksGroup = $('#yTicks');

      if (!barsGroup || !gridGroup || !xTicksGroup || !yTicksGroup) return;

      const vbW = 900, vbH = 320;
      const top = 30, bottom = 280;
      const heightAvailable = bottom - top;
      const leftPad = 60, rightPad = 40;
      const xLabelY = bottom + 15;

      barsGroup.innerHTML = '';
      gridGroup.innerHTML = '';
      xTicksGroup.innerHTML = '';
      yTicksGroup.innerHTML = '';

      const maxVal = Math.max(...salesData.map(d => d.value));
      const maxWithPadding = Math.ceil((maxVal * 1.1) / 100) * 100;
      const ticks = 4;
      const tickStep = Math.ceil(maxWithPadding / ticks);

      for (let i = 0; i <= ticks; i++) {
        const val = i * tickStep;
        const ratio = val / maxWithPadding;
        const y = bottom - ratio * heightAvailable;

        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', leftPad);
        line.setAttribute('x2', vbW - rightPad);
        line.setAttribute('y1', y);
        line.setAttribute('y2', y);
        line.setAttribute('stroke', GRID_COLOR);
        line.setAttribute('stroke-width', '1');
        gridGroup.appendChild(line);

        const lbl = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        lbl.setAttribute('x', leftPad - 8);
        lbl.setAttribute('y', y + 4);
        lbl.setAttribute('text-anchor', 'end');
        lbl.setAttribute('font-size', '12');
        lbl.setAttribute('fill', TEXT_COLOR);
        lbl.textContent = '$' + val.toLocaleString();
        yTicksGroup.appendChild(lbl);
      }

      const usableW = vbW - leftPad - rightPad;
      const count = salesData.length;
      const gap = 28;
      const barWidth = Math.max(18, Math.floor((usableW - gap * (count - 1)) / count));
      const barGroupWidth = barWidth + gap;

      salesData.forEach((d, i) => {
        const x = leftPad + i * barGroupWidth;
        const barHeight = Math.max(2, (d.value / maxWithPadding) * heightAvailable);
        const y = bottom - barHeight;
        const barCenter = x + barWidth / 2;

        const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        g.setAttribute('class', 'data-point');

        const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        rect.setAttribute('x', x);
        rect.setAttribute('y', y);
        rect.setAttribute('width', barWidth);
        rect.setAttribute('height', barHeight);
        rect.setAttribute('rx', 6);
        rect.setAttribute('fill', BAR_COLOR);
        g.appendChild(rect);

        const xLbl = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        xLbl.setAttribute('x', barCenter);
        xLbl.setAttribute('y', xLabelY);
        xLbl.setAttribute('text-anchor', 'middle');
        xLbl.setAttribute('font-size', '12');
        xLbl.setAttribute('fill', TEXT_COLOR);
        xLbl.textContent = d.month;
        xTicksGroup.appendChild(xLbl);

        const valueLbl = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        valueLbl.setAttribute('x', barCenter);
        valueLbl.setAttribute('y', y - 8);
        valueLbl.setAttribute('text-anchor', 'middle');
        valueLbl.setAttribute('font-size', '12');
        valueLbl.setAttribute('font-weight', 'bold');
        valueLbl.setAttribute('fill', '#374151');
        valueLbl.textContent = '$' + d.value.toLocaleString();
        valueLbl.style.opacity = '0';
        valueLbl.classList.add('value-label');
        g.appendChild(valueLbl);

        g.addEventListener('mouseover', () => {
          rect.setAttribute('fill', HOVER_COLOR);
          valueLbl.style.opacity = '1';
          g.style.cursor = 'pointer';
        });
        g.addEventListener('mouseout', () => {
          rect.setAttribute('fill', BAR_COLOR);
          valueLbl.style.opacity = '0';
        });

        barsGroup.appendChild(g);
      });

      const xLabelsRow = document.getElementById('xLabelsStatic') || $('.x-labels');
      if (xLabelsRow) xLabelsRow.style.display = 'none';
      
    })();


    // -------------------------
    // 5) Rating stars interaction (merged inline script)
    // -------------------------
    (function ratingStars() {
      const containers = $$('.rating-stars');
      if (!containers.length) return;

      containers.forEach(starContainer => {
        const stars = Array.from(starContainer.querySelectorAll('i'));

        stars.forEach(star => {
          // make keyboard-focusable if not already
          if (!star.hasAttribute('tabindex')) star.setAttribute('tabindex','0');

          const setRating = (rating) => {
            stars.forEach(s => {
              const vAttr = s.dataset.value || s.getAttribute('data-value') || '0';
              const v = parseInt(vAttr, 10) || 0;
              if (v <= rating) {
                s.classList.remove('far');
                s.classList.add('fa-solid');
                s.setAttribute('aria-checked','true');
              } else {
                s.classList.remove('fa-solid');
                s.classList.add('far');
                s.setAttribute('aria-checked','false');
              }
            });
          };

          star.addEventListener('click', () => {
            const ratingVal = parseInt(star.dataset.value || star.getAttribute('data-value') || '0', 10) || 0;
            setRating(ratingVal);
            // optionally expose current rating to container dataset for external use
            try { starContainer.dataset.rating = String(ratingVal); } catch (err) { /* ignore */ }
          });

          star.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              star.click();
            } else if (e.key === 'ArrowLeft') {
              e.preventDefault();
              const prev = star.previousElementSibling;
              if (prev) prev.focus();
            } else if (e.key === 'ArrowRight') {
              e.preventDefault();
              const next = star.nextElementSibling;
              if (next) next.focus();
            }
          });
        });
      });
    })();


    // -------------------------
    // 6) Expose small API for debugging/extension
    // -------------------------
    (function exposeApi() {
      if (!window.Dolaby) window.Dolaby = {};
      window.Dolaby.openCampaignModal = function () {
        const btn = document.getElementById('createBtn');
        if (btn) btn.click();
        else console.warn('createBtn not found on this page.');
      };
      window.Dolaby.navigate = function (hrefOrKey) {
        if (!hrefOrKey) return;
        const map = { discounts: 'discount.html', analytics: 'analysis.html', brandowner: 'brandowner.html' };
        const dest = map[hrefOrKey] || hrefOrKey;
        
        if (isHash(dest)) window.location.hash = dest;
        else window.location.href = dest;
      };
    })();

  }); // DOMContentLoaded end
})();
