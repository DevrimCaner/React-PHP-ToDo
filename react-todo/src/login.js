import { useEffect, useState } from 'react';
import {useNavigate} from 'react-router-dom';
import './App.css';

function Login() {
    const [user, setUser] = useState();
    const [password, setPassword] = useState();
    const [message, setMessage] = useState();
    const navigate = useNavigate();

    const sendLogin = () => {
        setMessage("");
        if(!user){
            setMessage("Username is blank");
            return;
        }
        if(!password){
            setMessage("Password is blank");
            return;
        }
        const formData = new FormData();
        formData.append('user', user);
        formData.append('password', password);
        formData.append('action', 'login');
        fetch(`${process.env.REACT_APP_ENDPOINT}`,{
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          console.log(data)
          if(data.error){
            setMessage(data.error);
          }
          else if(data.success){
            setMessage(data.success);
            // Foward in 2 seconds
            setTimeout(() => {
                navigate('/');
            }, 2000);
        }
        else{
            setMessage('Unknown Error!');
          }
        });
      };

    return (
        <div className='container'>
            <div className='loginBox'>
                <div className='row'>
                    <h2>ToDo-App Login Page</h2>
                </div>
                <div className='row'>
                    <p className='loginMessage'>&nbsp;{message}</p>
                </div>
                <div className='row'>
                    <input type='text' placeholder='Username' value={user || ''} onChange={(e) => setUser(e.target.value)}></input>
                </div>
                <div className='row'>
                    <input type='password' placeholder='Password' value={password || ''} onChange={(e) => setPassword(e.target.value)}></input>
                </div>
                <div className='row'>
                    <button onClick={sendLogin}>Login</button>
                </div>
            </div>
        </div>
    );
}

export default Login;
