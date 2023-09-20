import { useEffect, useState } from 'react';
import {useNavigate} from 'react-router-dom';
import axios from 'axios';
import md5 from 'md5';
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
        let passHash = md5(password);
        // Send Post
        axios
            .post(`${process.env.REACT_APP_ENDPOINT}`,{
                action: 'login',
                user: user,
                password: passHash
            })
            .then((response)=>{
                if(response.data.error){
                    setMessage(response.data.error);
                }
                else if(response.data.success){
                    setMessage(response.data.success);
                    sessionStorage.setItem('loggedIn', true);
                    sessionStorage.setItem('user', user);
                    sessionStorage.setItem('passHash', passHash);
                    // Foward
                    setTimeout(() => {
                        navigate('/');
                    }, 1000);
                }
                else{
                    console.log(response.data);
                    setMessage('Unknown Error.');
                }
            })
            .catch((error)=>{
                console.log(error);
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
