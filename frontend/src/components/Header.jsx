import { Link, useNavigate } from 'react-router-dom';
import './header.css';

function Header() {
  const navigate = useNavigate();

  const logout = () => {
    localStorage.removeItem('token');
    navigate('/login');
  };

  return (
    <nav className="navbar">
      <div className="logo">MyMoodTracker</div>
      <div className="nav-links">
        <Link to="/dashboard" className="btn">Dashboard</Link>
        <button onClick={logout} className="btn red">Logout</button>
      </div>
    </nav>
  );
}

export default Header;