import React from 'react';

const { CssStyle, StyleContainer } = window?.divi?.module;

export const ModuleStyles = ( {
        attrs,
        elements,
        settings,
        orderClass,
        mode,
        state,
        noStyleTag,
} ) => (
        <StyleContainer mode={mode} state={state} noStyleTag={noStyleTag}>
                { elements.style( {
                        attrName:   'module',
                        styleProps: {
                                disabledOn: {
                                        disabledModuleVisibility: settings?.disabledModuleVisibility,
                                },
                        },
                } ) }
                <CssStyle
                        selector={orderClass}
                        attr={attrs?.css}
                        cssFields={[]}
                />
        </StyleContainer>
);
