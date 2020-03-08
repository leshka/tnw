import React from 'react';
import { Link } from 'react-router-dom'
import Search from './Search'

function Header() {
    return (
        <header>
            <nav className="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
                <Link to="/" className="navbar-brand">Random News</Link>
                <div className="collapse navbar-collapse">
                    <div className="form-inline mt-2 mt-md-0">
                        <Search />
                    </div>
                </div>
            </nav>
        </header>
    )
}

export default Header
