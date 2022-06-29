import React from "react";
import { Outlet } from "react-router-dom";

const MovieDetailsContainer = () => {

    return (
        <div  className="ui one column padded grid">
            <Outlet/>
        </div>
    )
}

export default MovieDetailsContainer;