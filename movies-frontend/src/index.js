import React from 'react';
import { 
  BrowserRouter,  
  Routes,
  Route,
} from "react-router-dom";
import ReactDOM from 'react-dom/client';
import App from "./components/App/App";
import MovieDetailsContainer from './components/View/MovieDetailsContainer';
import MovieDetail from './components/View/MovieDetail';


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<App />} />
        <Route path="/movie" element={<MovieDetailsContainer />} >
          <Route path=":id" element={<MovieDetail />} />
        </Route>
      </Routes>
    </BrowserRouter>
  </React.StrictMode>
);