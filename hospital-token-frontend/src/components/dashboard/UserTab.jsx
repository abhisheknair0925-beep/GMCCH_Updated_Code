import React, { useState } from 'react';
import api from '../../lib/axios';
import { useToast } from '../Toast';
import { LoadingButton } from '../Spinner';
import { Search, Plus, Upload } from 'lucide-react';

const EMPTY_USER = { name: '', crno: '', phone: '', email: '' };

const UserTab = () => {
    const toast = useToast();

    const [searchCrno, setSearchCrno] = useState('');
    const [foundUser,  setFoundUser]  = useState(null);
    const [searchMsg,  setSearchMsg]  = useState('');
    const [newUser,    setNewUser]    = useState(EMPTY_USER);
    const [bulkFile,   setBulkFile]   = useState(null);
    const [bulkResult, setBulkResult] = useState(null);
    const [loading,    setLoading]    = useState({ search: false, add: false, bulk: false });

    const setLoad = (key, val) => setLoading(prev => ({ ...prev, [key]: val }));

    // ── Search user by CRNO ────────────────────────────────────────────────
    const handleSearch = async (e) => {
        e.preventDefault();
        setLoad('search', true);
        setFoundUser(null);
        setSearchMsg('');
        try {
            const res = await api.get(`/hospital/users?crno=${searchCrno}`);
            setFoundUser(res.data.data);
        } catch {
            setSearchMsg('No user found with this CR Number.');
        } finally {
            setLoad('search', false);
        }
    };

    // ── Add individual user ────────────────────────────────────────────────
    const handleAdd = async (e) => {
        e.preventDefault();
        setLoad('add', true);
        try {
            await api.post('/hospital/users', newUser);
            toast.success('User created successfully.');
            setNewUser(EMPTY_USER);
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
            const res = await api.post('/hospital/users/bulk', form);
            setBulkResult({ type: 'success', data: res.data });
            toast.success(res.data.message);
            setBulkFile(null);
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
                {/* ── Left: Search + Add ── */}
                <div style={s.card}>
                    <SectionTitle icon={<Search size={17} />} text="Find User by CR Number" />
                    <form onSubmit={handleSearch} style={{ display: 'flex', gap: '0.75rem', marginBottom: '1rem' }}>
                        <input
                            style={s.input} placeholder="Enter CR Number..."
                            value={searchCrno} onChange={e => setSearchCrno(e.target.value)} required
                        />
                        <LoadingButton
                            loading={loading.search}
                            label=""
                            loadingLabel=""
                            style={s.btnPrimary}
                            disabled={!searchCrno}
                        >
                        </LoadingButton>
                    </form>
                    {searchMsg && <p style={{ color: '#ef4444', fontSize: '0.82rem', marginBottom: '0.75rem' }}>{searchMsg}</p>}
                    {foundUser && (
                        <div style={s.resultCard}>
                            <p style={{ margin: '0 0 0.4rem', fontWeight: 800, color: '#0f172a' }}>{foundUser.name}</p>
                            <p style={s.meta}>CRNO: {foundUser.crno}</p>
                            <p style={s.meta}>Phone: {foundUser.phone || 'N/A'}</p>
                            <p style={s.meta}>Email: {foundUser.email || 'N/A'}</p>
                        </div>
                    )}

                    <hr style={s.divider} />

                    <SectionTitle icon={<Plus size={17} />} text="Add Individual User" />
                    <form onSubmit={handleAdd}>
                        <Field label="Full Name *"        value={newUser.name}  onChange={v => setNewUser({ ...newUser, name: v })}  required />
                        <Field label="CR Number *"        value={newUser.crno}  onChange={v => setNewUser({ ...newUser, crno: v })}  required />
                        <Field label="Phone (optional)"   value={newUser.phone} onChange={v => setNewUser({ ...newUser, phone: v })} />
                        <Field label="Email (optional)"   value={newUser.email} onChange={v => setNewUser({ ...newUser, email: v })} type="email" />
                        <LoadingButton
                            loading={loading.add}
                            label="Create User"
                            loadingLabel="Creating…"
                            style={{ ...s.btnPrimary, width: '100%', marginTop: '0.5rem' }}
                        />
                    </form>
                </div>

                {/* ── Right: Bulk Import ── */}
                <div style={s.card}>
                    <SectionTitle icon={<Upload size={17} />} text="Bulk CSV Import" />
                    <p style={s.desc}>
                        Upload a CSV file (max 500 rows) with columns: <code>name</code>, <code>crno</code>, <code>phone</code>, <code>email</code>.
                    </p>

                    {/* Bulk result detail (errors list) stays inline */}
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
    twoCol:      { display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '1.5rem' },
    card:        { background: 'white', borderRadius: '20px', padding: '1.75rem', border: '1px solid #e2e8f0' },
    input:       { width: '100%', padding: '0.75rem 1rem', border: '2px solid #e2e8f0', borderRadius: '10px', fontSize: '0.875rem', fontFamily: 'inherit', outline: 'none', boxSizing: 'border-box' },
    label:       { display: 'block', fontSize: '0.72rem', fontWeight: 700, color: '#374151', marginBottom: '0.4rem', textTransform: 'uppercase', letterSpacing: '0.05em' },
    btnPrimary:  { background: '#ff0088', color: 'white', border: 'none', borderRadius: '10px', padding: '0.75rem 1.25rem', fontWeight: 700, cursor: 'pointer', fontFamily: 'inherit', fontSize: '0.875rem', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '0.4rem' },
    btnSecondary:{ background: '#f8fafc', color: '#0f172a', border: '1px solid #e2e8f0', borderRadius: '10px', padding: '0.6rem 1.25rem', fontWeight: 700, fontFamily: 'inherit', fontSize: '0.85rem' },
    divider:     { margin: '1.5rem 0', borderColor: '#f1f5f9' },
    resultCard:  { background: '#f8fafc', borderRadius: '12px', padding: '1rem', border: '1px solid #e2e8f0', marginBottom: '0.5rem' },
    meta:        { margin: '0 0 0.2rem', fontSize: '0.82rem', color: '#64748b' },
    desc:        { fontSize: '0.82rem', color: '#64748b', marginBottom: '1.25rem', lineHeight: 1.6 },
};

export default UserTab;
