import React, { useState } from 'react';
import styled from 'styled-components';

const FullWidthSearchBar = styled.div`
    width:100%;
`

const MarginButton = styled.button`
    margin:0 10px !important;
`

const SearchInput = ({ loading = false, ...props }) => {
    const [searchTerm, setSearchTerm] = useState('');

    const { onSearch, onPendingSearch } = props;

    const handleSearchInput = (evt) => {
        setSearchTerm(evt.target.value)
    };

    const handleKeyEvent = (evt) => {
        if(evt.key === 'Enter') {
            handleSearch();
        }
    }

    const handleSearch = () => {
        onPendingSearch(true);
        onSearch(searchTerm);
    }

     return (
        <FullWidthSearchBar className={`ui icon input ${loading ? 'loading':''}`}>
            <input type='text' 
                placeholder='Search...' 
                onChange={handleSearchInput} 
                onKeyUp={handleKeyEvent} />
            <MarginButton 
                className='ui button' 
                onClick={handleSearch}>
               Search
            </MarginButton>
        </FullWidthSearchBar>
    )
}

export default SearchInput;