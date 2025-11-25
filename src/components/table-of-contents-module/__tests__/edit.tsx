import React from 'react';
import renderer from 'react-test-renderer';
import Edit from '../edit';

test('Edit renders placeholder', () => {
  const tree = renderer.create(<Edit />).toJSON();
  expect(tree).toBeTruthy();
});
