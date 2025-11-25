/**
 * Front-end logic for the Divi TOC module.
 *
 * Responsibilities:
 * - Read module settings passed from PHP via data-settings JSON.
 * - Scan the document for headings and generate anchors when missing.
 * - Build nested or flat list markup and inject into the module container.
 * - Enable smooth scrolling with offset and URL hash updates.
 * - Provide copy-link buttons for each heading and feedback UI.
 * - Implement scrollspy highlighting using IntersectionObserver.
 * - Support collapsible behavior, responsive dropdown on mobile, and back-to-top controls.
 */
(function () {
  // Expose the initializer for the builder so it can re-run after prop changes.
  window.diviTOCInit = function () {
    const tocElements = Array.from(document.querySelectorAll('.divi-toc-nav'));
    if (!tocElements.length) return;

    if (tocElements.length > 1) {
      console.warn('Divi TOC: Multiple instances detected. Only the first instance will be wired.');
    }

    const toc = tocElements[0];
    const settings = parseSettings(toc.dataset.settings || '{}');
    const output = toc.querySelector('[data-toc-output]');
    const message = toc.querySelector('[data-toc-message]');
    const toggle = toc.querySelector('.divi-toc-toggle');

    // Apply sticky classes based on device flags so CSS can pick up.
    ['desktop', 'tablet', 'mobile'].forEach((device) => {
      if (settings[`sticky_${device}`] === 'on') {
        toc.classList.add(`divi-toc-sticky-${device}`);
      }
    });

    if (settings.mobile_dropdown === 'on') {
      toc.classList.add('divi-toc-mobile-dropdown');
    }

    if (settings.active_color) toc.style.setProperty('--divi-toc-active-color', settings.active_color);
    if (settings.active_bg) toc.style.setProperty('--divi-toc-active-bg', settings.active_bg);
    if (settings.active_border_color) toc.style.setProperty('--divi-toc-active-border', settings.active_border_color);
    if (settings.active_border_width) toc.style.setProperty('--divi-toc-active-border-width', `${settings.active_border_width}px`);
    if (settings.active_weight === 'bold') toc.classList.add('divi-toc-active-bold');
    if (settings.active_underline === 'on') toc.classList.add('divi-toc-active-underline');

    // Collapsible container toggle.
    if (toggle && settings.collapsible === 'on') {
      let collapsed = settings.start_collapsed === 'on';
      updateCollapseState(collapsed);
      toggle.addEventListener('click', () => {
        collapsed = !collapsed;
        updateCollapseState(collapsed);
      });
    } else if (toggle) {
      toggle.style.display = 'none';
    }

    // Build TOC immediately when DOM is ready.
    const headings = collectHeadings(settings);
    const validCount = headings.filter((h) => h.type !== 'title').length;

    if (validCount < parseInt(settings.minimum_headings, 10)) {
      if (settings.fallback_behavior === 'show') {
        message.textContent = settings.fallback_message;
      } else {
        toc.style.display = 'none';
      }
      return;
    }

    const listRoot = buildList(headings, settings);
    output.appendChild(listRoot);

    if (settings.mobile_dropdown === 'on') {
      const select = buildDropdown(headings, settings);
      toc.insertBefore(select, output);
    }

    if (settings.back_to_top === 'on') {
      insertBackToTop(toc, settings);
    }

    enableSmoothScrolling(toc, settings);
    if (settings.scrollspy === 'on') {
      setupScrollSpy(toc, settings);
    }
  };

  // Run immediately on page load.
  window.diviTOCInit();

  function parseSettings(json) {
    try {
      return JSON.parse(json);
    } catch (e) {
      console.warn('Divi TOC: Unable to parse settings', e);
      return {};
    }
  }

  /**
   * Adds or removes collapsed class and updates ARIA attributes.
   */
  function updateCollapseState(collapsed) {
    const body = toc.querySelector('.divi-toc-body');
    toc.classList.toggle('divi-toc-collapsed', collapsed);
    if (toggle) {
      toggle.setAttribute('aria-expanded', (!collapsed).toString());
    }
    if (body) {
      body.hidden = collapsed;
    }
  }

  /**
   * Collect headings based on settings, generating unique IDs as needed.
   */
  function collectHeadings(opts) {
    const selectorBase = opts.custom_selector && opts.custom_selector.trim().length ? opts.custom_selector : '#main-content';
    const scope = document.querySelector(selectorBase) || document.body;
    const levels = Array.isArray(opts.heading_levels) ? opts.heading_levels : Object.keys(opts.heading_levels || {});
    const ignore = (opts.ignore_classes || '').split(',').map((c) => c.trim()).filter(Boolean);
    const headings = [];
    const existingIds = new Set();

    if (opts.include_title === 'on') {
      headings.push({
        type: 'title',
        text: document.title || '',
        id: 'page-title',
        level: 1,
        element: null,
      });
      existingIds.add('page-title');
    }

    const headingSelector = levels.map((h) => h.toLowerCase()).join(',');
    const found = scope ? Array.from(scope.querySelectorAll(headingSelector)) : [];

    found.forEach((node) => {
      if (!node || !node.textContent) return;
      if (ignore.length && node.classList && ignore.some((cls) => node.classList.contains(cls))) {
        return;
      }
      const level = parseInt(node.tagName.replace('H', ''), 10);
      const text = node.textContent.trim();
      if (!text.length) return;

      let id = node.id || slugify(text);
      id = ensureUnique(id, existingIds);
      if (!node.id) {
        node.id = id;
      }
      existingIds.add(id);

      headings.push({
        type: 'heading',
        text,
        id,
        level,
        element: node,
      });
    });

    return headings;
  }

  /**
   * Create a unique slug from text.
   */
  function slugify(text) {
    return text
      .toString()
      .normalize('NFD')
      .replace(/\p{Diacritic}/gu, '')
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)+/g, '') || 'section';
  }

  function ensureUnique(id, used) {
    let unique = id;
    let i = 1;
    while (used.has(unique)) {
      unique = `${id}-${i}`;
      i += 1;
    }
    return unique;
  }

  /**
   * Build nested or flat list structure.
   */
  function buildList(items, opts) {
    const isNested = opts.structure !== 'flat';
    const rootList = document.createElement(opts.style_preset === 'numbered' ? 'ol' : 'ul');
    rootList.className = 'divi-toc-list';
    rootList.style.listStyleType = opts.bullet_style || 'disc';

    let currentList = rootList;
    const stack = [{ level: 1, list: rootList }];

    items.forEach((item) => {
      const li = document.createElement('li');
      li.className = 'divi-toc-item';
      li.dataset.targetId = item.id;
      li.dataset.level = item.level;

      const link = document.createElement('a');
      link.href = `#${item.id}`;
      link.textContent = item.text;
      link.className = 'divi-toc-link';
      link.setAttribute('data-scroll-target', item.id);

      const copy = document.createElement('button');
      copy.type = 'button';
      copy.className = 'divi-toc-copy';
      copy.textContent = 'Copy link';
      copy.addEventListener('click', (e) => {
        e.stopPropagation();
        e.preventDefault();
        copyLink(item.id, copy);
      });

      li.appendChild(link);
      li.appendChild(copy);

      if (opts.collapse_nested === 'on' && opts.structure !== 'flat') {
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'divi-toc-sub-toggle';
        toggle.setAttribute('aria-expanded', 'true');
        toggle.innerHTML = opts.icon_style === 'plus' ? '+' : '▾';
        li.insertBefore(toggle, link);
        toggle.addEventListener('click', (e) => {
          e.preventDefault();
          const expanded = toggle.getAttribute('aria-expanded') === 'true';
          toggle.setAttribute('aria-expanded', (!expanded).toString());
          const childList = li.querySelector('ul,ol');
          if (childList) {
            childList.hidden = expanded;
          }
        });
      }

      if (!isNested) {
        currentList.appendChild(li);
        return;
      }

      // Nested structure following heading levels.
      const last = stack[stack.length - 1];
      if (item.level > last.level) {
        const newList = document.createElement(opts.style_preset === 'numbered' ? 'ol' : 'ul');
        newList.className = 'divi-toc-sub-list';
        newList.style.paddingLeft = `${parseInt(opts.indentation || 16, 10)}px`;
        last.list.lastElementChild && last.list.lastElementChild.appendChild(newList);
        stack.push({ level: item.level, list: newList });
        currentList = newList;
      } else {
        while (stack.length > 1 && item.level < stack[stack.length - 1].level) {
          stack.pop();
        }
        currentList = stack[stack.length - 1].list;
      }

      currentList.appendChild(li);
    });

    return rootList;
  }

  function buildDropdown(items, opts) {
    const select = document.createElement('select');
    select.className = 'divi-toc-dropdown';
    select.addEventListener('change', (e) => {
      const target = e.target.value;
      if (target) {
        scrollToHeading(target, opts);
      }
    });
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Jump to…';
    select.appendChild(placeholder);

    items.forEach((item) => {
      const option = document.createElement('option');
      option.value = item.id;
      option.textContent = `${' '.repeat(Math.max(0, item.level - 1))}${item.text}`;
      select.appendChild(option);
    });
    return select;
  }

  function copyLink(id, button) {
    const url = `${window.location.origin}${window.location.pathname}#${id}`;
    const write = (text) => navigator.clipboard.writeText(text);

    if (navigator.clipboard && navigator.clipboard.writeText) {
      write(url).then(() => showCopied(button)).catch(() => fallbackCopy(url, button));
    } else {
      fallbackCopy(url, button);
    }
  }

  function fallbackCopy(text, button) {
    const input = document.createElement('input');
    input.value = text;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
    showCopied(button);
  }

  function showCopied(button) {
    const original = button.textContent;
    button.textContent = 'Copied!';
    setTimeout(() => {
      button.textContent = original;
    }, 1500);
  }

  function enableSmoothScrolling(container, opts) {
    container.addEventListener('click', (event) => {
      const link = event.target.closest('a[data-scroll-target]');
      if (!link) return;
      event.preventDefault();
      const targetId = link.getAttribute('data-scroll-target');
      scrollToHeading(targetId, opts);
    });
  }

  function scrollToHeading(id, opts) {
    const target = document.getElementById(id);
    if (!target) return;
    const offset = parseInt(opts.scroll_offset || 0, 10);
    const rect = target.getBoundingClientRect();
    const top = window.pageYOffset + rect.top - offset;
    window.scrollTo({ top, behavior: 'smooth' });
    window.history.pushState({}, '', `#${id}`);
  }

  function setupScrollSpy(container, opts) {
    const items = Array.from(container.querySelectorAll('.divi-toc-item'));
    const map = new Map();
    items.forEach((item) => {
      map.set(item.dataset.targetId, item);
    });

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          const id = entry.target.id;
          if (!id) return;
          const tocItem = map.get(id);
          if (!tocItem) return;
          if (entry.isIntersecting) {
            map.forEach((el) => el.classList.remove('divi-toc-active'));
            tocItem.classList.add('divi-toc-active');
          }
        });
      },
      {
        rootMargin: `-${opts.scroll_offset || 0}px 0px -70% 0px`,
        threshold: [0, 0.25, 0.5, 1],
      }
    );

    const observedHeadings = new Set();
    items.forEach((item) => {
      const id = item.dataset.targetId;
      if (id && !observedHeadings.has(id)) {
        const el = document.getElementById(id);
        if (el) {
          observer.observe(el);
          observedHeadings.add(id);
        }
      }
    });
  }

  function insertBackToTop(container, opts) {
    const handler = () => scrollToHeading('page-title', opts);
    if (opts.back_to_top_mode === 'floating') {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = `divi-toc-back-top floating ${opts.back_to_top_position}`;
      btn.textContent = 'Back to top';
      btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
      document.body.appendChild(btn);
    } else {
      const links = container.querySelectorAll('.divi-toc-item');
      links.forEach((item) => {
        const link = document.createElement('button');
        link.type = 'button';
        link.className = 'divi-toc-back-top inline';
        link.textContent = 'Back to top';
        link.addEventListener('click', (e) => {
          e.stopPropagation();
          e.preventDefault();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        item.appendChild(link);
      });
    }
  }
})();
