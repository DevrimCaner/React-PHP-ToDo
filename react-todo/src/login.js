import { useEffect, useState } from 'react';
import './App.css';

function Login() {
  return (
    <div className='container'>
        <div className='loginBox'>
            <div className='row'>
                <h2>ToDo-App Login Page</h2>
            </div>
            <div className='row'>
                <input type='text' placeholder='Username'></input>
            </div>
            <div className='row'>
                <input type='password' placeholder='Password'></input>
            </div>
            <div className='row'>
                <button >Login</button>
            </div>
        </div>
      </div>
  );
}

export default Login;
