import React from 'react';
import Requester from "sulu-admin-bundle/services/Requester";
import {FieldTransformer} from "sulu-admin-bundle/types";

export default class RelatedTilesFieldTransformer implements FieldTransformer{

    relatedTilesData = [];

     getRelatedTiles(entityName, entityId) {
        const path = '/admin/api/tiles/entityRelatedTiles/' + entityName + '/' + entityId;
        Requester.get(path);
    }

    transform(value,parameter, item) {
         console.log(item);
        let id, entityName;
        [id, entityName] = value.split('|');
        this.getRelatedTiles(entityName, id).then((item) => {
            console.log(this.item);
            this.relatedTilesData = item;
        });
        // console.log(this.relatedTilesData);
        return <ul>
            {/*{this.relatedTilesData.map((item) => (*/}
            {/*    <li key={item.id}>{item.id}</li>*/}
            {/*))}*/}
        </ul>;
    }
}

// return null;
// if (!item.id) {
//     return null;
// }
// let title = item.id + ' - '+ item.name;
// return (
//
// );