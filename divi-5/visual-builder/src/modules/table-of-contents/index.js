import { TableOfContentsEdit } from './edit';
import metadata from './module.json';
import moduleDefaultRenderAttributes from './module-default-render-attributes.json';
import { SettingsAdvanced } from './settings-advanced';
import { SettingsContent } from './settings-content';
import { SettingsDesign } from './settings-design';

export const TableOfContentsMetadata = metadata;

export const TableOfContents = {
        metadata: TableOfContentsMetadata,
        defaultAttrs: moduleDefaultRenderAttributes,
        renderers: {
                edit: TableOfContentsEdit,
        },
        settings: {
                content: SettingsContent,
                design: SettingsDesign,
                advanced: SettingsAdvanced,
        },
};
