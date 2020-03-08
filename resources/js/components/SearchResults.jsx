import React, {useEffect, useState} from 'react';
import { useParams, Link } from 'react-router-dom'
import {gql} from "apollo-boost";
import {useQuery} from "@apollo/react-hooks";
import ArticleSnippet from "./ArticleSnippet";

const PAGE_SIZE = 8

const FETCH_SEARCH = gql`
    query Search($query: String!, $limit: Int!, $offset: Int) {
        search(query: $query, limit: $limit, offset: $offset) {
            totalCount
            items {
                id
                title
                slug
                media
            }
            pageInfo {
                hasNextPage
            }
        }
    }
`

function SearchResults() {
    const [ offset, setOffset ] = useState(0)
    const [ news, setNews ] = useState([])
    // const [ total, setTotal ] = useState(0)
    const { query } = useParams()
    const { loading, error, data } = useQuery(
        FETCH_SEARCH,
        { variables: { query, limit: PAGE_SIZE, offset } }
    )

    useEffect(()=>{
        if (data) {
            setNews([...news, ...data.search.items])
        }
    }, [data])

    useEffect(() => setNews([]), [query])


    if (error) return <p>Error :(</p>

    const handleMore = function() {
        setOffset(offset + PAGE_SIZE)
    }

    return <div>
        {
            data && <div className="searchStat">Found: { data.search.totalCount }</div>
        }

        <div className="news">
            {
                news.map((item) => <ArticleSnippet key={item.id} slug={item.slug} media={item.media} title={item.title} />)
            }
        </div>
        <div className="controls">
            {
                loading && <div><div className="spinner-border text-primary" role="status">
                    <span className="sr-only">Loading...</span>
                </div></div>
            }
            {
                data && data.search.pageInfo.hasNextPage &&
                <button type="button" className="btn btn-outline-primary" onClick={ handleMore }>
                    More
                </button>
            }
        </div>
    </div>
}

export default SearchResults
