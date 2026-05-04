import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Home from './pages/Home';
import HospitalLogin from './pages/HospitalLogin';
import HospitalDashboard from './pages/HospitalDashboard';

function App() {
  return (
    <Router>
      <Routes>
        {/* Public Routes */}
        <Route path="/" element={<Home />} />
        <Route path="/login" element={<HospitalLogin />} />
        
        {/* Hospital Admin Routes */}
        <Route path="/admin/dashboard" element={<HospitalDashboard />} />
        
        {/* Fallback */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
