/**
 * Entry point registering the Table Of Contents module with Divi 5 and attaching front-end runtime.
 */
import { registerModule } from '@elegantthemes/module';
import TableOfContentsModule from './components/table-of-contents-module';
import './components/table-of-contents-module/style.scss';
import './frontend';

registerModule(TableOfContentsModule);
