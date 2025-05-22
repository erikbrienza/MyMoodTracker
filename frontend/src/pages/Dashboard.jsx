import { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import './dashboard.css';
import Header from '../components/Header';

function Dashboard() {
  const [moods, setMoods] = useState([]);
  const [msg, setMsg] = useState('');
  const [mood, setMood] = useState('');
  const [token, setToken] = useState(null); // ✅ stato dinamico
  const navigate = useNavigate();
  const baseURL = import.meta.env.VITE_API_BASE_URL;

  // 🔁 Rileggi token da localStorage
  useEffect(() => {
    const storedToken = localStorage.getItem('token');
    if (!storedToken) {
      navigate('/login');
    } else {
      setToken(storedToken);
    }
  }, [navigate]);

  // 🔁 Fetch umori solo quando il token esiste
  useEffect(() => {
    if (token) {
      fetchMoods();
    }
  }, [token]);

  const fetchMoods = async () => {
    try {
      const res = await axios.get(`${baseURL}/moods/list.php`, {
        headers: { 'X-Token': token }
      });
      if (res.data.success) {
        setMoods(res.data.moods);
      } else {
        setMsg(res.data.message);
      }
    } catch {
      setMsg('Errore durante il caricamento');
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!mood) {
      setMsg('Seleziona un umore valido');
      return;
    }
    try {
      const res = await axios.post(`${baseURL}/moods/add.php`, { mood }, {
        headers: { 'X-Token': token }
      });
      if (res.data.success) {
        setMsg('Mood salvato con successo!');
        setMood('');
        fetchMoods();
      } else {
        setMsg(res.data.message);
      }
    } catch {
      setMsg('Errore durante il salvataggio');
    }
  };

  return (
    <>
      <Header />
      <div className="dashboard">
        <h2>Dashboard</h2>
        <form onSubmit={handleSubmit}>
          <label>Seleziona il tuo umore di oggi:</label>
          <select value={mood} onChange={(e) => setMood(e.target.value)}>
            <option value="">--Scegli--</option>
            <option value="happy">😊 felice</option>
            <option value="neutral">😐 neutro</option>
            <option value="sad">😢 triste</option>
          </select>
          <button type="submit">Salva Umore</button>
        </form>

        {msg && <p className="feedback">{msg}</p>}

        <h3>Storico Umori:</h3>
        {moods.length === 0 ? (
          <p>Nessun umore ancora salvato.</p>
        ) : (
          <ul className="mood-history">
            {moods.map((m, i) => (
              <li key={i} className={m.mood}>
                <span>{m.date}</span>
                <span className="emoji">
                  {m.mood === 'happy' && '😊 felice'}
                  {m.mood === 'neutral' && '😐 neutro'}
                  {m.mood === 'sad' && '😢 triste'}
                </span>
              </li>
            ))}
          </ul>
        )}
      </div>
    </>
  );
}

export default Dashboard;