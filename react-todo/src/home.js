import { useEffect, useState } from 'react';
import {useNavigate} from 'react-router-dom';
import axios from 'axios';
import './App.css';
import Navbar from './navbar';

function Home() {
  const navigate = useNavigate();
  const [todos, setTodos] = useState();
  const [todo, setTodo] = useState();
  const loggedIn = sessionStorage.getItem("loggedIn");
  const user = sessionStorage.getItem("user");
  const passHash = sessionStorage.getItem("passHash");

  useEffect(() =>{
    // Check LoggedIn
    if(!loggedIn){
      navigate('/login');
      return;
    }
    //Post
    axios
      .post(`${process.env.REACT_APP_ENDPOINT}`,{
          action: 'todos',
          user: user,
          password: passHash
      })
      .then((response)=>{
        console.log(response.data)
          if(response.data.error){
              console.log(response.data.error);
          }
          else{
              setTodos(response.data);
          }
      })
      .catch((error)=>{
          console.log(error);
      });
  },[]);

  const addTodo = () => {
    if(!todo){
      alert('Please fill the todo input');
      return;
    }

    //Post
    axios
      .post(`${process.env.REACT_APP_ENDPOINT}`,{
          action: 'add-todo',
          todo: todo,
          user: user,
          password: passHash
      })
      .then((response)=>{
          if(response.data.error){
              console.log(response.data.error);
          }
          else{
            setTodos([response.data, ...todos]);
            setTodo('');
          }
      })
      .catch((error)=>{
          console.log(error);
      });
  };

  const deleteTodo = todoId => {
    if(!todoId){
      alert('Id missing');
      return;
    }

    //Post
    axios
      .post(`${process.env.REACT_APP_ENDPOINT}`,{
          action: 'delete-todo',
          id: todoId,
          user: user,
          password: passHash
      })
      .then((response)=>{
          if(response.data.error){
              console.log(response.data.error);
          }
          else if(response.data.deleted){
            setTodos(todos.filter(todo => todo.id != todoId ));
          }
          else{
            console.log(response.data);
          }
      })
      .catch((error)=>{
          console.log(error);
      });
  };

  const doneTodo = (todoId, todoDone) => {
    todoDone = todoDone === 1 ? 0 : 1;
    //Post
    axios
      .post(`${process.env.REACT_APP_ENDPOINT}`,{
          action: 'done-todo',
          id: todoId,
          done: todoDone,
          user: user,
          password: passHash
      })
      .then((response)=>{
          if(response.data.error){
              console.log(response.data.error);
          }
          else{
            setTodos(response.data);
          }
      })
      .catch((error)=>{
          console.log(error);
      });
  };

  

  return (
    <>
    <Navbar />
    <div className='container'>
        <h1>ToDo App</h1>
        <div className='row searchBox'>
          <input type='text' value={todo || ''} onChange={(e) => setTodo(e.target.value)} placeholder='ToDo'></input>
          <button onClick={addTodo}>Add</button>
        </div>

        <div className='row'>
          {todos && (
            <ul className='todos'>
              {todos.map(todo => (
                <li key={todo.id} className={todo.done == 1 ? 'done' : ''}>
                  <span onClick={() => doneTodo(todo.id, todo.done)}>
                    {todo.todo}
                  </span>
                  <button onClick={() => deleteTodo(todo.id)} className='deleteButton'>Delete</button>
                </li>
              ))}
            </ul>
          )}
        </div>
      </div>
    </>
  );
}

export default Home;
