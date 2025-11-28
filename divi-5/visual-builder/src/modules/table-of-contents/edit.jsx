import React from 'react';

import { ModuleStyles } from './module-styles';
import { ModuleScriptData } from './module-script-data';
import { moduleClassnames } from './module-classnames';

const { ModuleContainer } = window?.divi?.module;

export const TableOfContentsEdit = ( {
        attrs,
        elements,
        id,
        name,
} ) => (
        <ModuleContainer
                attrs={attrs}
                elements={elements}
                id={id}
                name={name}
                scriptDataComponent={ModuleScriptData}
                stylesComponent={ModuleStyles}
                classnamesFunction={moduleClassnames}
        >
                <nav className="divi-toc-placeholder">Table of Contents placeholder</nav>
        </ModuleContainer>
);
