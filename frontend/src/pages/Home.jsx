import { Link } from 'react-router-dom';
import './home.css';

function Home() {
  return (
    <div className="home">
      <nav className="navbar">
        <div className="logo">MyMoodTracker</div>
        <div className="nav-links">
          <Link to="/login" className="btn">Login</Link>
          <Link to="/register" className="btn white">Registrati</Link>
        </div>
      </nav>

      <header className="hero">
        <h1><strong>Benvenuto su <span>MyMoodTracker</span></strong></h1>
        <p>Traccia il tuo umore ogni giorno. Registrati per iniziare il tuo percorso di consapevolezza personale.</p>
        <Link to="/register" className="hero-btn">Inizia ora</Link>
      </header>
    </div>
  );
}

export default Home;