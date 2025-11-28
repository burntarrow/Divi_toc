const { addAction } = window?.vendor?.wp?.hooks;

import { TableOfContents, TableOfContentsMetadata } from './table-of-contents';

const { registerModule } = window?.divi?.moduleLibrary;

addAction( 'divi.moduleLibrary.registerModuleLibraryStore.after', 'divi-toc', () => {
        registerModule( TableOfContentsMetadata, TableOfContents );
} );
