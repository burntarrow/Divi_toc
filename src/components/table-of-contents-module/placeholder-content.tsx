// src/components/table-of-contents-module/placeholder-content.tsx
import * as React from 'react';

/**
 * Simple placeholder shown in the Divi 5 builder
 * when rendering the Divi TOC module.
 */
export const PlaceholderContent: React.FC = () => (
  <div className="divi-toc-placeholder">
    <strong>Divi TOC</strong>
    <p>Headings found in the page will appear here.</p>
  </div>
);
