import React from 'react';
import {FieldTransformer} from "sulu-admin-bundle/types";

export default class EscapeHtmlTransformer implements FieldTransformer {
    stripHtml(html) {
        let tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }

    transform(value) {
        value = this.stripHtml(value);
        return (
            <div>
                {value}
            </div>
        );
    }
}