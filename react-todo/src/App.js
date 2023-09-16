import { useEffect, useState } from 'react';
import {Route, BrowserRouter as Router, Routes} from 'react-router-dom';
import './App.css';
import Home from './home';
import Login from './login';

function App() {

  return (
    <Router>
      <Routes>
        <Route path='/' exact Component={Home}/>
        <Route path='/login' exact Component={Login}/>
      </Routes>
    </Router>

  );
}

export default App;
