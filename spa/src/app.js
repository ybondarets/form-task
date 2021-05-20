import '../assets/styles/app.scss';
import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter as Router, Link, Route, Switch } from 'react-router-dom';
import Home from './pages/home';
import 'bootstrap/dist/css/bootstrap.css'

function App() {
    return (
        <div>
            <header className="header">
                <nav className="navbar navbar-light bg-light">
                    <div className="container">
                        &#128184; Companies historical quotes
                    </div>
                </nav>
            </header>

            <Router>
                <Switch>
                    <Route exact path="/">
                        <Home />
                    </Route>
                </Switch>
            </Router>
        </div>
    );
}

ReactDOM.render(<App />, document.getElementById('app'));
