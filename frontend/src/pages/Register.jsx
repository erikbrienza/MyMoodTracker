import { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import './register.css';

function Register() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [msg, setMsg] = useState('');
  const navigate = useNavigate();
  const baseURL = import.meta.env.VITE_API_BASE_URL;

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await axios.post(`${baseURL}/auth/register.php`, {
        email,
        password
      });
      if (res.data.success) {
        setMsg('Registrazione avvenuta con successo!');
        setTimeout(() => navigate('/login'), 1500);
      } else {
        setMsg(res.data.message);
      }
    } catch {
      setMsg('Errore di rete');
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Registrati</h2>
      {msg && <p className="info">{msg}</p>}
      <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} required />
      <input type="password" placeholder="Password" value={password} onChange={e => setPassword(e.target.value)} required />
      <button type="submit">Registrati</button>
    </form>
  );
}

export default Register;