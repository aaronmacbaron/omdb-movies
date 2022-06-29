import React from "react";

export const CardPlaceholder = () =>{
    return (
        <div className="ui card">
            <div className="image">
                <div className="ui placeholder">
                <div className="square image"></div>
                </div>
            </div>
        </div>
    )
}

export const ContentPlaceHolder = () => {
    return (
        <div className="ui placeholder">
            <div className="image header">
            <div className="line"></div>
            <div className="line"></div>
            </div>
        </div>
    )
}