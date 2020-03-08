import React from 'react';
import {Link} from "react-router-dom";

function ArticleSnippet(props) {
    const { title, slug, media } = props

    return (
        <div className="article card">
            <Link to={`/news/${slug}`}>
                <img src={media} className="card-img-top" alt={title} />
            </Link>
            <div className="card-body">
                <p className="card-text">
                    <Link to={`/news/${slug}`} dangerouslySetInnerHTML={{__html: title}}/>
                </p>
            </div>
        </div>
    )
}

export default ArticleSnippet
