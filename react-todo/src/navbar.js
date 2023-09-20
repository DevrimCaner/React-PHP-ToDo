import {useNavigate} from 'react-router-dom';
import axios from 'axios';
import './App.css';

function Navbar() {
    const navigate = useNavigate();

    const Logout = () => {
        // Send Post
        axios
            .post(`${process.env.REACT_APP_ENDPOINT}`,{
                action: 'logout'
            })
            .then((response)=>{
                sessionStorage.clear();
                navigate('/login');
            })
            .catch((error)=>{
                console.log(error);
            });
      };

    return (
        <nav>
            <button className='logout' onClick={Logout}>Logout</button>
        </nav>
    );
}

export default Navbar;
