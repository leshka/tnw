import React, { useState } from 'react'
import { Link } from 'react-router-dom'

function Search() {
    const [ query, setQuery ] = useState("")

    const searchUrl = `/search/${query}`

    return (
        <div>
            <input
                type="text"
                placeholder="Search"
                value={query}
                className="form-control mr-sm-2"
                onChange={ e => setQuery(e.target.value) }
            />
            <Link to={ searchUrl }>
                <button type="submit" className="btn btn-outline-success my-2 my-sm-0">Search</button>
            </Link>
        </div>
    )
}

export default Search
