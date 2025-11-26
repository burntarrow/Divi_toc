/**
 * Builder entry: registers the Divi TOC module inside the Divi 5 visual builder.
 * The module definition mirrors the structure used in the Divi 5 example modules.
 */
import { registerModule } from './divi-module-shim';
import { TableOfContentsModule } from './components/table-of-contents-module';
import './components/table-of-contents-module/style.scss';
import './components/table-of-contents-module/module.scss';

// Use the slug from module metadata to register with Divi 5
registerModule(TableOfContentsModule.metadata.slug, TableOfContentsModule);
