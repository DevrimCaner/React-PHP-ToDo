import logo from './logo.svg';
import { useEffect, useState } from 'react';
import './App.css';

function App() {
  

const [todos, setTodos] = useState();
const [todo, setTodo] = useState();

  useEffect(() =>{
    const formData = new FormData();
    formData.append('action', 'todos');
    fetch(`${process.env.REACT_APP_ENDPOINT}`,{
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => setTodos(data));
  },[]);

  const addTodo = () => {
    if(!todo){
      alert('Please fill the todo input');
      return;
    }
    const formData = new FormData();
    formData.append('todo', todo);
    formData.append('action', 'add-todo');
    fetch(`${process.env.REACT_APP_ENDPOINT}`,{
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      console.log(data)
      if(data.error){
        alert(data.error);
      }
      else{
        setTodos([data, ...todos]);
        setTodo('');
      }
    });
  };

  const deleteTodo = todoId => {
    if(!todoId){
      alert('Id missing');
      return;
    }
    const formData = new FormData();
    formData.append('id', todoId);
    formData.append('action', 'delete-todo');
    fetch(`${process.env.REACT_APP_ENDPOINT}`,{
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      console.log(data);
      if(data.error){
        alert(data.error);
      }
      else{
        setTodos(todos.filter(todo => todo.id != todoId ));
      }
    });
  };

  const doneTodo = (todoId, todoDone) => {
    console.log(todoDone)
    todoDone = todoDone === 1 ? 0 : 1;
    console.log(todoDone)
    const formData = new FormData();
    formData.append('id', todoId);
    formData.append('done', todoDone);
    formData.append('action', 'done-todo');
    fetch(`${process.env.REACT_APP_ENDPOINT}`,{
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      console.log(data);
      if(data.error){
        alert(data.error);
      }
      else{
        setTodos(data);
      }
    });
  };

  

  return (
    <>
      <h1>ToDo App</h1>

      <div>
        <input type='text' value={todo || ''} onChange={(e) => setTodo(e.target.value)} placeholder='ToDo'></input>
        <button onClick={addTodo}>ADD</button>
      </div>

      {todos && (
        <ul className='todos'>
          {todos.map(todo => (
            <li key={todo.id} className={todo.done == 1 ? 'done' : ''}>
              <span onClick={() => doneTodo(todo.id, todo.done)}>
                {todo.todo}
              </span>
              <button onClick={() => deleteTodo(todo.id)}>Delete</button>
            </li>
          ))}
        </ul>
      )}
    </>
  );
}

export default App;
