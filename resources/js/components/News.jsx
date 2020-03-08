import React, { useState, useEffect } from 'react'
import { useQuery } from '@apollo/react-hooks'
import { gql } from 'apollo-boost'
import { useParams, Link } from 'react-router-dom'
import ArticleSnippet from "./ArticleSnippet";

const PAGE_SIZE = 8

const FETCH_NEWS = gql`
    query News($category: String, $limit: Int!, $offset: Int) {
        news(category: $category, limit: $limit, offset: $offset) {
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

function News() {
    const [ offset, setOffset ] = useState(0)
    const [ news, setNews ] = useState([])
    const { category } = useParams()
    const { loading, error, data } = useQuery(
        FETCH_NEWS,
        { variables: { category, limit: PAGE_SIZE, offset } }
    )

    useEffect(()=>{
        if (data) {
            setNews([...news, ...data.news.items])
        }
    }, [data])

    if (error) return <p>Error :(</p>

    const handleMore = function() {
        setOffset(offset + PAGE_SIZE)
    }

    return <div>
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
                data && data.news.pageInfo.hasNextPage &&
                    <button type="button" className="btn btn-outline-primary" onClick={ handleMore }>
                        More
                    </button>
            }
        </div>
    </div>
}

export default News
