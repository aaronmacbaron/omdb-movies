import React from "react";
import styled from 'styled-components';
import { Link } from "react-router-dom";

const StyledLink = styled(Link)``

const MovieCard = (props) => {
    const {title, poster, year, id} = props;

    return (
        <div className="column"> 
            <div className="ui fluid card">
                <div className="image">
                    <img src={`${poster}`} />
                </div>
                <div className="content">
                    <StyledLink to={`movie/${id}`} state={{id}}className="header">{`${title} - (${year})`}</StyledLink>
                </div>
            </div>
        </div>
    )
}

export default MovieCard;