require('./bootstrap')

import React from 'react'
import { render } from 'react-dom'
import ApolloClient from 'apollo-boost'
import { ApolloProvider } from '@apollo/react-hooks'
import { BrowserRouter as Router, Route } from "react-router-dom"

import Header from './components/Header'
import News from './components/News'
import Article from './components/Article'
import SearchResults from './components/SearchResults'

const client = new ApolloClient({
    uri: '/graphql/',
})

const App = () => (
    <ApolloProvider client={client}>
        <div className="container-fluid">
            <Router>
                <Header />
                <Route exact path="/">
                    <News />
                </Route>
                <Route path="/news/:slug" children={<Article />} />
                <Route path="/category/:category" children={<News />} />
                <Route path='/search/:query' children={<SearchResults />} />
            </Router>
        </div>
    </ApolloProvider>
)

render(<App />, document.getElementById('app'))
