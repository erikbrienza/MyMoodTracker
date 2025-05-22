import { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import './login.css';

function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [msg, setMsg] = useState('');
  const navigate = useNavigate();
  const baseURL = import.meta.env.VITE_API_BASE_URL;

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await axios.post(`${baseURL}/auth/login.php`, {
        email,
        password
      });
      if (res.data.success) {
        localStorage.setItem('token', res.data.token);
        navigate('/dashboard');
      } else {
        setMsg(res.data.message);
      }
    } catch {
      setMsg('Errore di rete');
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Login</h2>
      {msg && <p className="error">{msg}</p>}
      <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} required />
      <input type="password" placeholder="Password" value={password} onChange={e => setPassword(e.target.value)} required />
      <button type="submit">Login</button>
    </form>
  );
}

export default Login;