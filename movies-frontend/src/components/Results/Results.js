import React from "react";
import styled from "styled-components";
import MovieCard from "../MovieCard/MovieCard";

const MovieCardsContainer = styled.div``;

const Loader = () => {
    return (
        <div className="ui segment">
            <div className="ui active inverted dimmer">
                <div className="ui text loader">Loading</div>
            </div>
            <p></p>
        </div>
    )
}

const Results = ({results, pending}) =>{
    return (
        <>
          <h1>Results:</h1>
          {pending &&
            <div className="ui segment">
                <div className="ui active inverted dimmer"> 
                <Loader />
                </div>
            </div>
          }
          {!pending && !!results && 
                <MovieCardsContainer className="ui four column grid">
                    {results.map((movie)=>{
                        const { title, poster, year, id} = movie;
                        return <MovieCard key={title+year} id={id} title={title} poster={poster} year={year} />
                    })}
                </MovieCardsContainer>
             }
        </> 
           
        
    )
}
export default Results;