import React from 'react';
import renderer from 'react-test-renderer';
import Edit from '../edit';
import { collectHeadings, slugify, buildNestedTree } from '../../../frontend';

test('Edit renders placeholder', () => {
  const tree = renderer.create(<Edit />).toJSON();
  expect(tree).toBeTruthy();
});

test('slugify ensures unique IDs', () => {
  const used: Record<string, number> = {};
  expect(slugify('Section One', used)).toBe('section-one');
  expect(slugify('Section One', used)).toBe('section-one-1');
});

test('collectHeadings skips ignored classes and assigns ids', () => {
  document.body.innerHTML = `
    <div id="main-content">
      <h2>Keep me</h2>
      <h3 class="skip">Ignore me</h3>
    </div>
  `;
  const container = document.querySelector('#main-content') as Element;
  const headings = collectHeadings(container, ['h2', 'h3'], ['skip'], {});
  expect(headings).toHaveLength(1);
  expect(headings[0].id).toBeTruthy();
});

test('buildNestedTree nests by heading level', () => {
  const items = [
    { id: 'a', text: 'A', level: 2, element: document.body, children: [] },
    { id: 'b', text: 'B', level: 3, element: document.body, children: [] },
  ];
  const tree = buildNestedTree(items);
  expect(tree[0].children[0].id).toBe('b');
});
