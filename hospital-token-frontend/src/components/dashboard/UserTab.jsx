import React, { useState, useEffect } from 'react';
import api from '../../lib/axios';
import { useToast } from '../Toast';
import { LoadingButton } from '../Spinner';
import { Search, Plus, Upload, User as UserIcon } from 'lucide-react';

const EMPTY_USER = { name: '', crno: '', user_age: '', user_gender: '' };

const UserTab = () => {
    const toast = useToast();

    const [users, setUsers] = useState([]);
    const [searchQuery, setSearchQuery] = useState('');
    const [newUser, setNewUser] = useState(EMPTY_USER);
    const [bulkFile, setBulkFile] = useState(null);
    const [bulkResult, setBulkResult] = useState(null);
    const [loading, setLoading] = useState({ fetch: false, add: false, bulk: false });

    const setLoad = (key, val) => setLoading(prev => ({ ...prev, [key]: val }));

    const fetchUsers = async () => {
        setLoad('fetch', true);
        try {
            const res = await api.get('/hospital/users');
            setUsers(res.data.data || []);
        } catch (err) {
            toast.error('Failed to load users.');
        } finally {
            setLoad('fetch', false);
        }
    };

    useEffect(() => {
        fetchUsers();
    }, []);

    // Filter users locally
    const filteredUsers = users.filter(u => 
        u.crno.toLowerCase().includes(searchQuery.toLowerCase()) || 
        u.name.toLowerCase().includes(searchQuery.toLowerCase())
    );

    // ── Add individual user ────────────────────────────────────────────────
    const handleAdd = async (e) => {
        e.preventDefault();
        setLoad('add', true);
        try {
            await api.post('/hospital/users', newUser);
            toast.success('User created successfully.');
            setNewUser(EMPTY_USER);
            fetchUsers();
        } catch (err) {
            toast.error(err.response?.data?.message || 'Failed to add user.');
        } finally {
            setLoad('add', false);
        }
    };

    // ── Bulk CSV import ────────────────────────────────────────────────────
    const handleBulk = async (e) => {
        e.preventDefault();
        if (!bulkFile) return;
        setLoad('bulk', true);
        setBulkResult(null);
        const form = new FormData();
        form.append('file', bulkFile);
        try {
            const res = await api.post('/hospital/users/bulk', form, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            setBulkResult({ type: 'success', data: res.data });
            toast.success(res.data.message);
            setBulkFile(null);
            fetchUsers();
        } catch (err) {
            const msg = err.response?.data?.message || 'Bulk upload failed.';
            setBulkResult({ type: 'error', text: msg });
            toast.error(msg);
        } finally {
            setLoad('bulk', false);
        }
    };

    return (
        <div>
            <h1 style={s.pageTitle}>User Management</h1>

            <div style={s.twoCol}>
                {/* ── Left: Add + Bulk Import ── */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
                    <div style={s.card}>
                        <SectionTitle icon={<Plus size={17} />} text="Add Individual User" />
                        <form onSubmit={handleAdd}>
                            <Field label="Full Name *" value={newUser.name} onChange={v => setNewUser({ ...newUser, name: v })} required />
                            <Field label="CR Number *" value={newUser.crno} onChange={v => setNewUser({ ...newUser, crno: v })} required />
                            <div style={{ display: 'flex', gap: '1rem' }}>
                                <div style={{ flex: 1 }}><Field label="Age (optional)" value={newUser.user_age} onChange={v => setNewUser({ ...newUser, user_age: v })} type="number" /></div>
                                <div style={{ flex: 1 }}>
                                    <div style={{ marginBottom: '0.85rem' }}>
                                        <label style={s.label}>Gender (optional)</label>
                                        <select style={s.input} value={newUser.user_gender} onChange={e => setNewUser({ ...newUser, user_gender: e.target.value })}>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <LoadingButton
                                loading={loading.add}
                                label="Create User"
                                loadingLabel="Creating…"
                                style={{ ...s.btnPrimary, width: '100%', marginTop: '0.5rem' }}
                            />
                        </form>
                    </div>

                    <div style={s.card}>
                        <SectionTitle icon={<Upload size={17} />} text="Bulk CSV Import" />
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.75rem' }}>
                            <p style={s.desc}>
                                Upload a CSV file with columns: <code>name</code>, <code>crno</code>, <code>user_age</code>, <code>user_gender</code>.
                            </p>
                            <a
                                href="/sample_patients_import.csv"
                                download
                                style={{ fontSize: '0.85rem', color: '#0f172a', fontWeight: 700, textDecoration: 'none' }}
                            >
                                Download sample CSV
                            </a>
                        </div>

                        {bulkResult?.type === 'success' && bulkResult.data.errors?.length > 0 && (
                            <div style={{ background: '#fffbeb', border: '1px solid #fde68a', borderRadius: '10px', padding: '0.85rem 1rem', marginBottom: '1rem', fontSize: '0.82rem', color: '#92400e' }}>
                                <strong>{bulkResult.data.errors.length} row(s) skipped:</strong>
                                <ul style={{ margin: '0.5rem 0 0', paddingLeft: '1.2rem' }}>
                                    {bulkResult.data.errors.slice(0, 5).map((e, i) => <li key={i}>{e}</li>)}
                                    {bulkResult.data.errors.length > 5 && <li>…and {bulkResult.data.errors.length - 5} more.</li>}
                                </ul>
                            </div>
                        )}

                        <form onSubmit={handleBulk}>
                            <div style={{ border: '2px dashed #e2e8f0', borderRadius: '16px', padding: '2rem', textAlign: 'center', marginBottom: '1rem', background: '#fafafa' }}>
                                <Upload size={36} style={{ color: '#cbd5e1', marginBottom: '0.75rem' }} />
                                <p style={{ margin: '0 0 0.75rem', fontSize: '0.85rem', color: '#94a3b8' }}>
                                    {bulkFile ? `📄 ${bulkFile.name}` : 'Drop your CSV file here or click to browse'}
                                </p>
                                <input type="file" id="bulkFile" accept=".csv" onChange={e => setBulkFile(e.target.files[0])} style={{ display: 'none' }} />
                                <label htmlFor="bulkFile" style={{ ...s.btnSecondary, cursor: 'pointer', display: 'inline-block' }}>
                                    {bulkFile ? 'Change File' : 'Choose CSV File'}
                                </label>
                            </div>
                            <LoadingButton
                                loading={loading.bulk}
                                label="Process Bulk Import"
                                loadingLabel="Processing…"
                                disabled={!bulkFile}
                                style={{ ...s.btnPrimary, width: '100%', opacity: (!bulkFile || loading.bulk) ? 0.5 : 1 }}
                            />
                        </form>
                    </div>
                </div>

                {/* ── Right: List of Users ── */}
                <div style={s.card}>
                    <SectionTitle icon={<UserIcon size={17} />} text="All Users" />
                    
                    <div style={{ position: 'relative', marginBottom: '1.5rem' }}>
                        <Search size={18} style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)', color: '#94a3b8' }} />
                        <input
                            type="text"
                            placeholder="Search by CR Number or Name..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            style={{ ...s.input, paddingLeft: '2.5rem' }}
                        />
                    </div>

                    {loading.fetch ? (
                        <p style={{ color: '#64748b', fontSize: '0.875rem' }}>Loading users...</p>
                    ) : (
                        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem', maxHeight: '600px', overflowY: 'auto', paddingRight: '0.5rem' }}>
                            {filteredUsers.length === 0 ? (
                                <p style={{ color: '#64748b', fontSize: '0.875rem' }}>No users found.</p>
                            ) : (
                                filteredUsers.map((u, i) => (
                                    <div key={i} style={s.userCard}>
                                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                            <div>
                                                <h4 style={{ margin: '0 0 0.25rem', color: '#0f172a', fontWeight: 800, fontSize: '0.95rem' }}>{u.name}</h4>
                                                <p style={{ margin: 0, fontSize: '0.82rem', color: '#64748b', fontWeight: 600 }}>{u.crno}</p>
                                            </div>
                                            <div style={{ textAlign: 'right' }}>
                                                {u.user_age && <span style={s.badge}>{u.user_age} yrs</span>}
                                                {u.user_gender && <span style={{ ...s.badge, marginLeft: '0.4rem', background: '#e0e7ff', color: '#3730a3' }}>{u.user_gender}</span>}
                                            </div>
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

// ── Sub-components ─────────────────────────────────────────────────────────
const SectionTitle = ({ icon, text }) => (
    <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginBottom: '1rem' }}>
        <span style={{ color: '#ff0088' }}>{icon}</span>
        <h3 style={{ margin: 0, fontWeight: 800, fontSize: '0.95rem', color: '#0f172a' }}>{text}</h3>
    </div>
);

const Field = ({ label, value, onChange, required, type = 'text' }) => (
    <div style={{ marginBottom: '0.85rem' }}>
        <label style={s.label}>{label}</label>
        <input type={type} style={s.input} value={value} onChange={e => onChange(e.target.value)} required={required} />
    </div>
);

// ── Styles ─────────────────────────────────────────────────────────────────
const s = {
    pageTitle:   { fontSize: '1.5rem', fontWeight: 900, color: '#0f172a', marginBottom: '1.5rem' },
    twoCol:      { display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '1.5rem', alignItems: 'start' },
    card:        { background: 'white', borderRadius: '20px', padding: '1.75rem', border: '1px solid #e2e8f0' },
    input:       { width: '100%', padding: '0.75rem 1rem', border: '2px solid #e2e8f0', borderRadius: '10px', fontSize: '0.875rem', fontFamily: 'inherit', outline: 'none', boxSizing: 'border-box' },
    label:       { display: 'block', fontSize: '0.72rem', fontWeight: 700, color: '#374151', marginBottom: '0.4rem', textTransform: 'uppercase', letterSpacing: '0.05em' },
    btnPrimary:  { background: '#ff0088', color: 'white', border: 'none', borderRadius: '10px', padding: '0.75rem 1.25rem', fontWeight: 700, cursor: 'pointer', fontFamily: 'inherit', fontSize: '0.875rem', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '0.4rem' },
    btnSecondary:{ background: '#f8fafc', color: '#0f172a', border: '1px solid #e2e8f0', borderRadius: '10px', padding: '0.6rem 1.25rem', fontWeight: 700, fontFamily: 'inherit', fontSize: '0.85rem' },
    divider:     { margin: '1.5rem 0', borderColor: '#f1f5f9' },
    desc:        { fontSize: '0.82rem', color: '#64748b', marginBottom: '1.25rem', lineHeight: 1.6 },
    userCard:    { background: '#f8fafc', borderRadius: '12px', padding: '1rem', border: '1px solid #e2e8f0' },
    badge:       { display: 'inline-block', padding: '0.2rem 0.5rem', borderRadius: '6px', fontSize: '0.75rem', fontWeight: 700, background: '#f1f5f9', color: '#475569' }
};

export default UserTab;
