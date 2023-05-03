import {AbstractListToolbarAction} from 'sulu-admin-bundle/views';
import ResourceRequester from "sulu-admin-bundle/services/ResourceRequester";

export default class DuplicateToolbarAction extends AbstractListToolbarAction {
    getToolbarItemConfig() {
        return {
            type: 'button',
            label: 'Duplicate',
            icon: 'su-shadow-page',
            disabled: this.listStore.selections.length === 0,
            onClick: this.handleClick,
        };
    }

    handleClick = () => {
        if (this.listStore.selections.length === 0) {
            alert('Nothing selected');

            return;
        }
        const resourceIdsToClone = this.listStore.selections
            .map((item) => item.id);

        ResourceRequester.post(this.listStore.resourceKey, null, {
            ...this.listStore.options,
            resourceIdsToClone: resourceIdsToClone,
        }).finally(() => this.listStore.reload());
    };
}