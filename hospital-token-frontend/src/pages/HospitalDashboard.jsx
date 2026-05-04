import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../lib/axios';
import { LayoutDashboard, Users, Activity, CheckCircle, LogOut } from 'lucide-react';

const HospitalDashboard = () => {
    const [hospital, setHospital] = useState(null);
    const [summary, setSummary] = useState(null);
    const [units, setUnits] = useState([]);
    const [doctors, setDoctors] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        const storedAdmin = localStorage.getItem('hospital');
        if (!storedAdmin) {
            navigate('/');
            return;
        }
        setHospital(JSON.parse(storedAdmin));
        fetchDashboardData();
    }, [navigate]);

    const fetchDashboardData = async () => {
        try {
            const [sumRes, unitRes, docRes] = await Promise.all([
                api.get('/hospital/dashboard/summary'),
                api.get('/hospital/dashboard/units'),
                api.get('/hospital/dashboard/doctors')
            ]);
            
            if (sumRes.data.success) setSummary(sumRes.data.data);
            if (unitRes.data.success) setUnits(unitRes.data.data);
            if (docRes.data.success) setDoctors(docRes.data.data);
        } catch (err) {
            console.error("Failed to fetch dashboard data", err);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem('token');
        localStorage.removeItem('hospital');
        navigate('/');
    };

    if (!hospital || !summary) return <div className="auth-container">Loading Dashboard...</div>;

    return (
        <div className="app-container animate-fade-in">
            <header style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '3rem' }}>
                <div style={{ display: 'flex', alignItems: 'center' }}>
                    <div style={{ padding: '0.75rem', borderRadius: '8px', background: 'rgba(59, 130, 246, 0.1)', marginRight: '1rem' }}>
                        <LayoutDashboard size={24} color="var(--accent-color)" />
                    </div>
                    <div>
                        <h1 style={{ margin: 0, lineHeight: 1 }}>{hospital.name}</h1>
                        <p style={{ margin: 0, fontSize: '0.9rem', marginTop: '0.25rem' }}>Administrator Dashboard</p>
                    </div>
                </div>
                <button onClick={handleLogout} className="btn btn-secondary">
                    <LogOut size={18} style={{ marginRight: '0.5rem' }} /> Logout
                </button>
            </header>

            {/* KPI Summary Cards */}
            <h3 style={{ marginBottom: '1.5rem', color: 'var(--text-secondary)' }}>Today's Overview</h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '1.5rem', marginBottom: '3rem' }}>
                <div className="glass-panel" style={{ padding: '1.5rem' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '1rem' }}>
                        <p style={{ margin: 0, fontWeight: 500 }}>Total Bookings</p>
                        <Users size={20} color="var(--accent-color)" />
                    </div>
                    <h2 style={{ margin: 0, fontSize: '2.5rem' }}>{summary.today.total}</h2>
                </div>
                
                <div className="glass-panel" style={{ padding: '1.5rem' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '1rem' }}>
                        <p style={{ margin: 0, fontWeight: 500 }}>Active Queue</p>
                        <Activity size={20} color="var(--warning-color)" />
                    </div>
                    <h2 style={{ margin: 0, fontSize: '2.5rem' }}>{summary.today.active}</h2>
                </div>

                <div className="glass-panel" style={{ padding: '1.5rem' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '1rem' }}>
                        <p style={{ margin: 0, fontWeight: 500 }}>Completed</p>
                        <CheckCircle size={20} color="var(--success-color)" />
                    </div>
                    <h2 style={{ margin: 0, fontSize: '2.5rem' }}>{summary.today.completed}</h2>
                </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(400px, 1fr))', gap: '2rem' }}>
                
                {/* Unit Tracking */}
                <div className="glass-panel" style={{ padding: '1.5rem' }}>
                    <h3 style={{ marginBottom: '1.5rem' }}>Unit Queues</h3>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
                        {units.map(unit => (
                            <div key={unit.unit_id} style={{ padding: '1rem', background: 'rgba(255,255,255,0.03)', border: '1px solid var(--border-color)', borderRadius: '8px' }}>
                                <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '0.5rem' }}>
                                    <h4 style={{ margin: 0 }}>{unit.unit_name}</h4>
                                    <span className="badge badge-active">{unit.today.pending} Pending</span>
                                </div>
                                <p style={{ fontSize: '0.85rem', margin: 0 }}>Assigned: {unit.doctor_name}</p>
                                <div style={{ display: 'flex', gap: '1rem', marginTop: '1rem', fontSize: '0.85rem' }}>
                                    <div><strong style={{ color: 'var(--success-color)' }}>{unit.today.completed}</strong> Completed</div>
                                    <div><strong>{unit.today.chemo}</strong> Chemo</div>
                                    <div><strong>{unit.today.normal}</strong> Normal</div>
                                </div>
                            </div>
                        ))}
                        {units.length === 0 && <p>No active unit data.</p>}
                    </div>
                </div>

                {/* Doctor Workload */}
                <div className="glass-panel" style={{ padding: '1.5rem' }}>
                    <h3 style={{ marginBottom: '1.5rem' }}>Doctor Workload</h3>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
                        {doctors.map(doctor => (
                            <div key={doctor.doctor_id} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '1rem', background: 'rgba(255,255,255,0.03)', border: '1px solid var(--border-color)', borderRadius: '8px' }}>
                                <div>
                                    <h4 style={{ margin: 0, marginBottom: '0.25rem' }}>{doctor.doctor_name}</h4>
                                    <p style={{ fontSize: '0.85rem', margin: 0 }}>Units Assigned: {doctor.total_assigned_units}</p>
                                </div>
                                <div style={{ display: 'flex', gap: '1rem', textAlign: 'center' }}>
                                    <div>
                                        <div style={{ fontSize: '1.25rem', fontWeight: 600, color: 'var(--success-color)' }}>{doctor.today_patients_seen}</div>
                                        <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>Seen</div>
                                    </div>
                                    <div>
                                        <div style={{ fontSize: '1.25rem', fontWeight: 600, color: 'var(--warning-color)' }}>{doctor.today_patients_waiting}</div>
                                        <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>Waiting</div>
                                    </div>
                                </div>
                            </div>
                        ))}
                        {doctors.length === 0 && <p>No active doctor data.</p>}
                    </div>
                </div>

            </div>
        </div>
    );
};

export default HospitalDashboard;
