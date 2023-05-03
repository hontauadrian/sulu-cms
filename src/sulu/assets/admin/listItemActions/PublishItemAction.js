import {AbstractListItemAction} from 'sulu-admin-bundle/views';
import Requester from "sulu-admin-bundle/services/Requester";

export default class PublishItemAction extends AbstractListItemAction {
    getItemActionConfig(item) {
        return {
            icon: 'su-upload', disabled: item ? item.isPublished : false,
            onClick: item ? () => this.handleClick(item) : undefined,
        };
    }

    handleClick = (item) => {
        const buttonRef = '/admin/api/tiles/' + item.id + '/publish';
        this.listStore.setDataLoading(true);
        Requester.get(buttonRef).finally(() => this.listStore.reload());
    };
}