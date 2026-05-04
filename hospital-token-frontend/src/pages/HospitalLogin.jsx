import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../lib/axios';
import { ShieldCheck, ArrowRight, Mail, Lock } from 'lucide-react';

const HospitalLogin = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleLogin = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');

        try {
            const response = await api.post('/hospital/login', { email, password });
            if (response.data.success) {
                localStorage.setItem('token', response.data.data.token);
                localStorage.setItem('hospital', JSON.stringify(response.data.data.hospital));
                navigate('/admin/dashboard');
            }
        } catch (err) {
            setError(err.response?.data?.message || 'Authentication Failed');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="auth-container">
            <div className="glass-panel auth-card animate-fade-in">
                <div className="text-center mb-8">
                    <div className="inline-flex p-4 rounded-2xl bg-primary/10 mb-4">
                        <ShieldCheck className="text-primary" size={32} />
                    </div>
                    <h2>Admin Portal</h2>
                    <p className="text-sm">Secure Institutional Access</p>
                </div>

                {error && (
                    <div className="bg-error/10 text-error p-3 rounded-lg mb-6 text-sm text-center font-medium">
                        {error}
                    </div>
                )}

                <form onSubmit={handleLogin}>
                    <div className="input-group">
                        <label className="input-label">Email Address</label>
                        <div className="relative">
                            <div className="absolute top-1/2 -translate-y-1/2 left-4 text-text-secondary">
                                <Mail size={18} />
                            </div>
                            <input 
                                type="email" 
                                className="input-field pl-12" 
                                placeholder="admin@hospital.com"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required
                            />
                        </div>
                    </div>

                    <div className="input-group">
                        <label className="input-label">Access Code</label>
                        <div className="relative">
                            <div className="absolute top-1/2 -translate-y-1/2 left-4 text-text-secondary">
                                <Lock size={18} />
                            </div>
                            <input 
                                type="password" 
                                className="input-field pl-12" 
                                placeholder="••••••••"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                            />
                        </div>
                    </div>

                    <button type="submit" className="btn btn-primary w-full mt-4" disabled={loading}>
                        {loading ? 'Verifying...' : 'Sign In'}
                        {!loading && <ArrowRight size={18} className="ml-2" />}
                    </button>
                </form>
            </div>
        </div>
    );
};

export default HospitalLogin;
