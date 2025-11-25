/**
 * Front-end runtime for Divi TOC. Scans headings and renders list.
 */

type TocItem = {
  id: string;
  text: string;
  level: number;
  children: TocItem[];
};

const slugify = (text: string, used: Record<string, number>): string => {
  const base = text
    .normalize('NFD')
    .replace(/\p{Diacritic}/gu, '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
  const count = used[base] || 0;
  used[base] = count + 1;
  return count ? `${base}-${count}` : base;
};

const collectHeadings = (
  container: Element,
  levels: string[],
  ignore: string[],
): TocItem[] => {
  const selector = levels.map((level) => level.toUpperCase()).join(',');
  const items: TocItem[] = [];
  const used: Record<string, number> = {};
  container.querySelectorAll(selector).forEach((node) => {
    const el = node as HTMLElement;
    if (ignore.some((cls) => el.classList.contains(cls))) {
      return;
    }
    if (!el.id) {
      el.id = slugify(el.innerText || el.textContent || 'section', used);
    }
    items.push({
      id: el.id,
      text: el.innerText || el.textContent || '',
      level: parseInt(el.tagName.replace('H', ''), 10),
      children: [],
    });
  });
  return items;
};

const nest = (items: TocItem[]): TocItem[] => {
  const root: TocItem[] = [];
  const stack: TocItem[] = [];
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

const renderList = (items: TocItem[], nested: boolean): HTMLElement => {
  const list = document.createElement('ul');
  items.forEach((item) => {
    const li = document.createElement('li');
    const link = document.createElement('a');
    link.href = `#${item.id}`;
    link.textContent = item.text;
    li.appendChild(link);

    const copy = document.createElement('button');
    copy.className = 'toc-copy';
    copy.type = 'button';
    copy.textContent = 'Copy';
    copy.addEventListener('click', (e) => {
      e.preventDefault();
      const url = `${window.location.origin}${window.location.pathname}#${item.id}`;
      navigator.clipboard?.writeText(url).catch(() => {
        const input = document.createElement('input');
        input.value = url;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        input.remove();
      });
      copy.textContent = 'Copied!';
      setTimeout(() => (copy.textContent = 'Copy'), 1000);
    });
    li.appendChild(copy);

    if (nested && item.children.length) {
      li.appendChild(renderList(item.children, true));
    }
    list.appendChild(li);
  });
  return list;
};

const smoothScroll = (target: HTMLElement, offset: number) => {
  const top = target.getBoundingClientRect().top + window.scrollY - offset;
  window.scrollTo({ top, behavior: 'smooth' });
};

const activateScrollspy = (nav: HTMLElement, headings: HTMLElement[]) => {
  const links = Array.from(nav.querySelectorAll('a')) as HTMLAnchorElement[];
  const onScroll = () => {
    const fromTop = window.scrollY + 5;
    let activeId = '';
    headings.forEach((el) => {
      if (el.offsetTop <= fromTop) {
        activeId = el.id;
      }
    });
    links.forEach((link) => {
      const isActive = link.hash === `#${activeId}`;
      link.parentElement?.classList.toggle('is-active', isActive);
    });
  };
  document.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
};

const initToc = () => {
  const toc = document.querySelector('[data-divi-toc]') as HTMLElement | null;
  if (!toc) return;

  const data = JSON.parse(toc.dataset.diviToc || '{}');
  const levels = (data.headingLevels as string)?.split('|') || data.headingLevels || ['h2', 'h3', 'h4', 'h5'];
  const ignore = (data.ignoreClasses as string)?.split(',').map((c: string) => c.trim()).filter(Boolean) || [];
  const selector = data.customSelector || '#main-content';
  const container = document.querySelector(selector) || document.querySelector('#main-content') || document.body;
  const headings = collectHeadings(container, levels, ignore);

  if (data.includeTitle && document.title) {
    headings.unshift({ id: 'page-title', text: document.title, level: 1, children: [] });
  }

  const valid = headings.length >= (data.minimum || 2);
  if (!valid && data.onEmpty === 'hide') {
    toc.style.display = 'none';
    return;
  }
  if (!valid && data.onEmpty === 'message') {
    toc.innerText = data.emptyMessage || 'No table of contents available.';
    return;
  }

  const nested = data.structure !== 'flat';
  const tree = nested ? nest(headings) : headings;
  toc.innerHTML = '';
  const list = renderList(tree, nested);
  toc.appendChild(list);

  const headingEls = headings
    .map((item) => document.getElementById(item.id))
    .filter((el): el is HTMLElement => Boolean(el));

  toc.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', (event) => {
      const targetId = (event.currentTarget as HTMLAnchorElement).hash.replace('#', '');
      const target = document.getElementById(targetId);
      if (target) {
        event.preventDefault();
        smoothScroll(target, data.scrollOffset || 0);
        window.history.pushState({}, '', `#${targetId}`);
      }
    });
  });

  if (data.scrollspy) {
    activateScrollspy(toc, headingEls);
  }
};

document.addEventListener('DOMContentLoaded', initToc);
