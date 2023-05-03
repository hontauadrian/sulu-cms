// Add project specific javascript code and import of additional bundles here:
import {listItemActionRegistry, listToolbarActionRegistry} from "sulu-admin-bundle/views";
import DuplicateToolbarAction from "./listToolbarActions/DuplicateToolbarAction";
import PublishItemAction from "./listItemActions/PublishItemAction";
import {ckeditorPluginRegistry, ckeditorConfigRegistry} from 'sulu-admin-bundle/containers';
import Font from '@ckeditor/ckeditor5-font/src/font';
import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting';
import {listFieldTransformerRegistry} from 'sulu-admin-bundle/containers';
import EscapeHtmlTransformer from "./fieldTransformers/EscapeHtmlTransformer";
import CustomBoolFieldlTransformer from "./fieldTransformers/CustomBoolFieldlTransformer";

listFieldTransformerRegistry.add('html_escaped', new EscapeHtmlTransformer());
listFieldTransformerRegistry.add('custom_bool',new CustomBoolFieldlTransformer());
listToolbarActionRegistry.add('sulu_admin.duplicate', DuplicateToolbarAction);
listItemActionRegistry.add('app.publish_tile', PublishItemAction);

ckeditorPluginRegistry.add(Font);
ckeditorPluginRegistry.add(SourceEditing);
ckeditorConfigRegistry.add((config) => ({
    toolbar: [...config.toolbar, 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor','sourceEditing'],
}));