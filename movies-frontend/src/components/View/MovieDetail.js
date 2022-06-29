import React, { useEffect, useState } from "react";
import axios from "axios";
import { useParams } from "react-router-dom";
import { ContentPlaceHolder, CardPlaceholder } from "../Placeholder/Placeholder";
import styled from 'styled-components';

const moviesAPI = 'http://localhost:8000/api/movies/';

const PinnedRightButton = styled.button`
  float:right;
`

const MovieDetail = () => {

    const [movieData, setMovieData] = useState(null);
    const [fieldsDisabled,setFieldsDisabled] = useState(false);

    const [title, setTitle] = useState('');
    const [poster, setPoster] = useState('');
    const [year, setYear] = useState('');
    const [imdbID, setImdbID] = useState('');
    const [liked, setLiked] = useState(false);

    const id = useParams();

    useEffect( () => {
        
        if(!movieData){
            axios.get(`${moviesAPI}${id.id}`).then((response) => {
                if(response.data[0]){
                    const {title, poster, year, imdbId, liked} = response.data[0];

                    setMovieData(response.data[0]);
                    setTitle(title);
                    setPoster(poster);
                    setYear(year);
                    setImdbID(imdbId);
                    setLiked(liked);
                }
                
            })
        }
    },[]);

    const onChangeTitle = (e) => {
        setTitle(e.target.value);
    }
    const onChangePoster = (e) => {
        setPoster(e.target.value);
    }
    const onChangeYear = (e) => {
        setYear(e.target.value);
    }
    const onChangeImdbID = (e) => {
        setImdbID(e.target.value);
    }
    const onChangeLiked = () => {
        setLiked(!liked);
    }

    const onSaveChanges = (e) => {
        e.preventDefault();
        setFieldsDisabled(true);
        axios.patch(`${moviesAPI}${id.id}`, {
            title,
            poster,
            year,
            imdbId:imdbID,
            liked
        }).then((response) => {
            const {title, poster, year, imdbId, liked} = response.data[1];

            setMovieData(response.data[1]);
            setTitle(title);
            setPoster(poster);
            setYear(year);
            setImdbID(imdbId);
            setLiked(liked);
            setFieldsDisabled(false);
            
        })
    }

    const onDelete = (e) => {
        e.preventDefault();
        setFieldsDisabled(true);
        axios.delete(`${moviesAPI}${id.id}`).then((response) => {
            window.location = "/"
        });
    }

    return (
        <div  className="ui two column padded grid">
            {movieData ? (
                <div className="column">
                    <h1> {movieData.title}</h1>
                    <div className="ui fluid card">
                        <div className="image">
                            <img src={movieData.poster} /> 
                        </div>
                    </div>
                </div> 
            ) : <CardPlaceholder />}
            <div className="column">
                {movieData ? (
                    <>
                    <form className="ui form">
                        <div className="field">
                            <label>Title</label>
                            <input type="text" id="title" disabled={fieldsDisabled} name="title" placeholder="Title" value={title} onChange={onChangeTitle}/>
                        </div>
                        <div className="field">
                            <label>Poster</label>
                            <input type="text" id="poster" disabled={fieldsDisabled} name="poster" placeholder="Poster" value={poster} onChange={onChangePoster}/>
                        </div>
                        <div className="field">
                            <label>Year</label>
                            <input type="text" id="year" disabled={fieldsDisabled} name="year" placeholder="Year" value={year} onChange={onChangeYear} />
                        </div>
                        <div className="field">
                            <label>IMDB ID</label>
                            <input type="text" id="imdbid" disabled={fieldsDisabled} name="imdbId" placeholder="imdbId" value={imdbID} onChange={onChangeImdbID}/>
                        </div>
                        <div className="field">
                            <div className="ui checkbox">
                                <input type="checkbox" disabled={fieldsDisabled} name="liked" tabIndex="0" checked={liked} onChange={onChangeLiked}/>
                                <label>Do you like this movie?</label>
                            </div>
                        </div>

                        <PinnedRightButton className="ui primary button" onClick={onSaveChanges}>
                         Save
                        </PinnedRightButton>
                        <PinnedRightButton className="ui secondary button" onClick={onDelete}>
                         Delete
                        </PinnedRightButton>
                    </form>
                    </>

                ) : <ContentPlaceHolder />}
            </div>
        </div>

    )
}

export default MovieDetail;