import React, {useState} from 'react'; 
import Results from '../Results/Results';
import SearchInput from '../Search/SearchInput';
import axios from 'axios';
import { MainContainer } from '../StyledComponents';
import mockdata from '../../mock-api/mock-data.json'


const App = () => {
  const moviesAPI = 'http://localhost:8000/api'
  const useMock = false;

  const [searchIsPending, setSearchIsPending] = useState(false);
  const [results, setResults] = useState(null);

  const onSearch = (term) => {
    const searchURL = `${moviesAPI}/search/movies/${term}`;
    if(useMock){
        const data = mockdata ?? {};
        setSearchIsPending(false);
        setResults(data.Search);
    } else {
      setSearchIsPending(true);
      axios.get(searchURL).then((response) => {
        const { data } = response;
        setSearchIsPending(false);
        setResults(data.Search);
      })  
    }
    
  }

  return (
    <MainContainer className="ui one column padded grid">
      <SearchInput onSearch={onSearch} onPendingSearch={setSearchIsPending} loading={searchIsPending}/>
      <Results pending={searchIsPending} results={results}/>
    </MainContainer>
  )
}

export default App;
