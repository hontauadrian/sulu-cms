import React from 'react';
import {FieldTransformer} from "sulu-admin-bundle/types";
import Icon from "sulu-admin-bundle/components/Icon"

export default class CustomBoolFieldlTransformer implements FieldTransformer {

    transform(value) {
        const style = {color: 'Green'};
        let className = 'su-circle-full';

        if (value !== true) {
            style.color = 'Tomato';
        }

        return (
            <Icon className={className} name={className} style={style}/>
        );

    }
}