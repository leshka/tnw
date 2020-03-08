import React from 'react';
import { useParams, Link } from 'react-router-dom'
import { gql } from 'apollo-boost'
import { useQuery } from "@apollo/react-hooks"

const FETCH_ARTICLE = gql`
    query Article($slug: String!) {
        articleBySlug(slug: $slug) {
            title
            categories
            content
            media
        }
    }
`

function Category(props) {
    return (
        <Link to={ `/category/${props.children}` } className="badge badge-info news-category">{ props.children }</Link>
    )
}

function Article() {
    const { slug } = useParams()
    const { loading, error, data } = useQuery(
        FETCH_ARTICLE,
        { variables: { slug } }
    )

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error :(</p>;

    const { title, content, categories } = data.articleBySlug

    return (
        <div>
            <h1 dangerouslySetInnerHTML={{ __html: title }} />
            <div>
                { categories.map(cat => <Category key={cat}>{ cat }</Category>) }
            </div>

            <div dangerouslySetInnerHTML={{ __html: content }} />
        </div>
    )
}

export default Article
