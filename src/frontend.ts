/**
 * Front-end runtime for Divi TOC.
 *
 * - Collects headings from the rendered document (supports classic + Gutenberg).
 * - Generates unique anchors when missing and renders nested/flat lists.
 * - Handles smooth scrolling with offset, URL hash updates, copy-link buttons,
 *   scrollspy highlighting, collapsible UI, responsive dropdown, and back-to-top.
 */

import './components/table-of-contents-module/style.scss';
import './components/table-of-contents-module/module.scss';

export type TocHeading = {
  id: string;
  text: string;
  level: number;
  element: HTMLElement;
  children: TocHeading[];
};

export type TocSettings = {
  includeTitle?: boolean;
  headingLevels?: string;
  customSelector?: string;
  ignoreClasses?: string;
  minimum?: number;
  onEmpty?: 'hide' | 'message';
  emptyMessage?: string;
  structure?: 'nested' | 'flat';
  scrollOffset?: number;
  scrollspy?: boolean;
  preset?: string;
  collapsible?: boolean;
  startCollapsed?: boolean;
  collapseChildren?: boolean;
  dropdownMobile?: boolean;
  sticky?: { desktop?: boolean; tablet?: boolean; mobile?: boolean };
  hide?: { mobile?: boolean; tablet?: boolean };
  backToTop?: { enabled?: boolean; mode?: 'section' | 'floating'; position?: string };
  design?: {
    listStyle?: string;
    indent?: number;
    iconStyle?: string;
    activeColor?: string;
    activeBg?: string;
    activeBorder?: string;
    activeWeight?: string;
    activeUnderline?: boolean;
  };
};

const DEFAULT_LEVELS = ['h2', 'h3', 'h4', 'h5'];

export const slugify = (text: string, used: Record<string, number>): string => {
  const base = text
    .normalize('NFD')
    .replace(/\p{Diacritic}/gu, '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '') || 'section';
  const count = used[base] || 0;
  used[base] = count + 1;
  return count ? `${base}-${count}` : base;
};

const ensureHeadingId = (el: HTMLElement, used: Record<string, number>): string => {
  const existing = el.id?.trim();
  if (existing && !used[existing]) {
    used[existing] = 1;
    return existing;
  }
  const text = el.innerText || el.textContent || 'section';
  const slug = slugify(text, used);
  el.id = slug;
  return slug;
};

export const collectHeadings = (
  container: Element,
  levels: string[],
  ignore: string[],
  used: Record<string, number>,
): TocHeading[] => {
  const selector = levels.map((level) => level.toUpperCase()).join(',');
  const items: TocHeading[] = [];

  container.querySelectorAll(selector).forEach((node) => {
    const el = node as HTMLElement;
    if (ignore.some((cls) => el.classList.contains(cls))) {
      return;
    }
    const id = ensureHeadingId(el, used);
    items.push({
      id,
      text: el.innerText || el.textContent || '',
      level: parseInt(el.tagName.replace('H', ''), 10),
      element: el,
      children: [],
    });
  });

  return items;
};

export const buildNestedTree = (items: TocHeading[]): TocHeading[] => {
  const root: TocHeading[] = [];
  const stack: TocHeading[] = [];

  items.forEach((item) => {
    while (stack.length && stack[stack.length - 1].level >= item.level) {
      stack.pop();
    }
    if (stack.length === 0) {
      root.push(item);
    } else {
      stack[stack.length - 1].children.push(item);
    }
    stack.push(item);
  });

  return root;
};

const renderList = (
  items: TocHeading[],
  nested: boolean,
  settings: TocSettings,
  depth = 0,
): HTMLUListElement => {
  const list = document.createElement('ul');
  list.className = depth === 0 ? 'divi-toc-list' : 'divi-toc-sub-list';

  if (settings.design?.listStyle) {
    list.style.listStyleType = settings.design.listStyle as string;
  }

  items.forEach((item) => {
    const li = document.createElement('li');
    li.className = 'divi-toc-item';

    let childList: HTMLUListElement | null = null;
    let toggle: HTMLButtonElement | null = null;

    const link = document.createElement('a');
    link.href = `#${item.id}`;
    link.className = 'divi-toc-link';
    link.textContent = item.text;
    li.appendChild(link);

    const copy = document.createElement('button');
    copy.type = 'button';
    copy.className = 'divi-toc-copy';
    copy.textContent = 'Copy';
    copy.addEventListener('click', (event) => {
      event.preventDefault();
      const url = `${window.location.origin}${window.location.pathname}#${item.id}`;
      if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(url).catch(() => {});
      } else {
        const input = document.createElement('input');
        input.value = url;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        input.remove();
      }
      copy.textContent = 'Copied!';
      setTimeout(() => {
        copy.textContent = 'Copy';
      }, 1200);
    });
    li.appendChild(copy);

    if (nested && item.children.length) {
      childList = renderList(item.children, true, settings, depth + 1);
      if (settings.collapseChildren) {
        toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'divi-toc-sub-toggle';
        toggle.setAttribute('aria-expanded', 'true');
        toggle.textContent = '−';
        const regionId = `divi-toc-children-${item.id}`;
        childList.id = regionId;
        toggle.setAttribute('aria-controls', regionId);
        li.insertBefore(toggle, link);
        toggle.addEventListener('click', () => {
          const expanded = toggle!.getAttribute('aria-expanded') === 'true';
          toggle!.setAttribute('aria-expanded', expanded ? 'false' : 'true');
          toggle!.textContent = expanded ? '+' : '−';
          if (childList) {
            childList.style.display = expanded ? 'none' : '';
          }
        });
      }
      li.appendChild(childList);
    }

    if (settings.backToTop?.enabled && settings.backToTop.mode === 'section') {
      const top = document.createElement('button');
      top.type = 'button';
      top.className = 'divi-toc-back-top';
      top.textContent = 'Back to top';
      top.addEventListener('click', (event) => {
        event.preventDefault();
        const topAnchor = document.getElementById('divi-toc-page-top') || document.body;
        smoothScroll(topAnchor as HTMLElement, settings.scrollOffset || 0);
      });
      li.appendChild(top);
    }

    list.appendChild(li);
  });

  return list;
};

const smoothScroll = (target: HTMLElement, offset: number) => {
  const top = target.getBoundingClientRect().top + window.scrollY - offset;
  window.scrollTo({ top, behavior: 'smooth' });
};

const activateScrollspy = (
  nav: HTMLElement,
  headings: TocHeading[],
  offset: number,
): void => {
  const map = new Map<string, HTMLElement>();
  nav.querySelectorAll('.divi-toc-item').forEach((item) => {
    const link = item.querySelector('a');
    if (link?.hash) {
      map.set(link.hash.replace('#', ''), item as HTMLElement);
    }
  });

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const targetId = entry.target.id;
        const navItem = map.get(targetId);
        if (!navItem) return;
        if (entry.isIntersecting) {
          nav.querySelectorAll('.divi-toc-active').forEach((el) => el.classList.remove('divi-toc-active'));
          navItem.classList.add('divi-toc-active');
        }
      });
    },
    {
      rootMargin: `-${offset}px 0px -70% 0px`,
      threshold: [0, 0.2, 0.4, 0.6, 0.8, 1],
    },
  );

  headings.forEach((heading) => observer.observe(heading.element));
};

const createDropdown = (items: TocHeading[], select: HTMLSelectElement, depth = 0) => {
  items.forEach((item) => {
    const option = document.createElement('option');
    option.value = item.id;
    option.textContent = `${'— '.repeat(depth)}${item.text}`;
    select.appendChild(option);
    if (item.children.length) {
      createDropdown(item.children, select, depth + 1);
    }
  });
};

const applyDesignStyles = (nav: HTMLElement, settings: TocSettings) => {
  if (!settings.design) return;
  if (settings.design.indent !== undefined) {
    nav.style.setProperty('--divi-toc-indent', `${settings.design.indent}px`);
  }
  if (settings.design.activeColor) {
    nav.style.setProperty('--divi-toc-active-color', settings.design.activeColor);
  }
  if (settings.design.activeBg) {
    nav.style.setProperty('--divi-toc-active-bg', settings.design.activeBg);
  }
  if (settings.design.activeBorder) {
    nav.style.setProperty('--divi-toc-active-border', settings.design.activeBorder);
  }
  if (settings.design.activeWeight === 'bold') {
    nav.classList.add('divi-toc-active-bold');
  }
  if (settings.design.activeUnderline) {
    nav.classList.add('divi-toc-active-underline');
  }
};

const ensureTopAnchor = (container: Element): HTMLElement => {
  const existing = document.getElementById('divi-toc-page-top');
  if (existing) return existing;
  const anchor = document.createElement('div');
  anchor.id = 'divi-toc-page-top';
  anchor.tabIndex = -1;
  if (container.firstChild) {
    container.insertBefore(anchor, container.firstChild);
  } else {
    container.appendChild(anchor);
  }
  return anchor;
};

const initSingle = (nav: HTMLElement, settings: TocSettings) => {
  const levels = (settings.headingLevels || '')
    .split('|')
    .map((l) => l.trim().toLowerCase())
    .filter(Boolean);
  if (!levels.length) {
    levels.push(...DEFAULT_LEVELS);
  }
  const ignore = (settings.ignoreClasses || '')
    .split(',')
    .map((cls) => cls.trim())
    .filter(Boolean);
  const selector = settings.customSelector || '#main-content';
  const container = document.querySelector(selector) || document.querySelector('#main-content') || document.body;

  const usedIds: Record<string, number> = {};
  const headings = collectHeadings(container, levels, ignore, usedIds);

  if (settings.includeTitle) {
    const anchor = ensureTopAnchor(container);
    const titleText = document.title || 'Page';
    const titleHeading: TocHeading = {
      id: anchor.id,
      text: titleText,
      level: 1,
      element: anchor,
      children: [],
    };
    headings.unshift(titleHeading);
  }

  const meetsMinimum = headings.length >= (settings.minimum || 2);
  if (!meetsMinimum && settings.onEmpty === 'hide') {
    nav.style.display = 'none';
    return;
  }
  if (!meetsMinimum && settings.onEmpty === 'message') {
    nav.innerHTML = `<div class="divi-toc-message">${settings.emptyMessage || 'No table of contents available.'}</div>`;
    return;
  }

  const tree = settings.structure === 'flat' ? headings : buildNestedTree(headings);

  const presetMap: Record<string, string> = {
    bullet: 'simple',
    numbered: 'numbered',
    tree: 'tree',
    card: 'card',
    collapsible: 'accordion',
    floating: 'floating',
    boxed: 'boxed',
    none: 'raw',
  };
  const presetKey = presetMap[settings.preset || 'simple'] || 'simple';
  nav.classList.add(`divi-toc-preset-${presetKey}`);
  applyDesignStyles(nav, settings);

  const body = document.createElement('div');
  body.className = 'divi-toc-body';
  if (settings.collapsible && settings.startCollapsed) {
    nav.classList.add('divi-toc-collapsed');
  }

  if (settings.collapsible) {
    const header = document.createElement('div');
    header.className = 'divi-toc-header';
    const title = document.createElement('span');
    title.textContent = 'Table of contents';
    header.appendChild(title);
    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'divi-toc-toggle';
    toggle.setAttribute('aria-expanded', settings.startCollapsed ? 'false' : 'true');
    toggle.textContent = settings.startCollapsed ? 'Expand' : 'Collapse';
    toggle.addEventListener('click', () => {
      const collapsed = nav.classList.toggle('divi-toc-collapsed');
      toggle.textContent = collapsed ? 'Expand' : 'Collapse';
      toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    });
    header.appendChild(toggle);
    nav.appendChild(header);
  }

  if (settings.dropdownMobile) {
    nav.classList.add('divi-toc-mobile-dropdown');
    const select = document.createElement('select');
    select.className = 'divi-toc-dropdown';
    select.addEventListener('change', (event) => {
      const id = (event.target as HTMLSelectElement).value;
      const target = document.getElementById(id);
      if (target) {
        smoothScroll(target, settings.scrollOffset || 0);
        window.history.pushState({}, '', `#${id}`);
      }
    });
    createDropdown(tree, select);
    body.appendChild(select);
  }

  const list = renderList(tree, settings.structure !== 'flat', settings);
  body.appendChild(list);
  nav.appendChild(body);

  const headingEls = headings
    .map((item) => item.element)
    .filter((el): el is HTMLElement => Boolean(el));

  nav.querySelectorAll('.divi-toc-link').forEach((link) => {
    link.addEventListener('click', (event) => {
      const targetId = (event.currentTarget as HTMLAnchorElement).hash.replace('#', '');
      const target = document.getElementById(targetId);
      if (target) {
        event.preventDefault();
        smoothScroll(target, settings.scrollOffset || 0);
        window.history.pushState({}, '', `#${targetId}`);
      }
    });
  });

  if (settings.scrollspy) {
    activateScrollspy(nav, headings, settings.scrollOffset || 0);
  }

  if (settings.backToTop?.enabled && settings.backToTop.mode === 'floating') {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = `divi-toc-back-top floating ${settings.backToTop.position || 'bottom-right'}`;
    btn.textContent = 'Top';
    btn.addEventListener('click', (event) => {
      event.preventDefault();
      const anchor = document.getElementById('divi-toc-page-top') || document.body;
      smoothScroll(anchor as HTMLElement, settings.scrollOffset || 0);
    });
    document.body.appendChild(btn);
  }
};

const initToc = () => {
  const nodes = Array.from(document.querySelectorAll('[data-divi-toc]')) as HTMLElement[];
  if (!nodes.length) return;
  if (nodes.length > 1) {
    // eslint-disable-next-line no-console
    console.warn('[Divi TOC] Multiple TOC modules detected; only the first will be initialized.');
  }

  const nav = nodes[0];
  const data = JSON.parse(nav.dataset.diviToc || '{}') as TocSettings;
  initSingle(nav, data);
};

document.addEventListener('DOMContentLoaded', initToc);
(window as any).diviTOCInit = initToc;
