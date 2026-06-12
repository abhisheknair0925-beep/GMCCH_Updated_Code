import React, { useState } from 'react';
import api from '../../lib/axios';
import { useToast } from '../Toast';
import { LoadingButton } from '../Spinner';
import { UserPlus, Camera, X, CheckCircle, User, Mail, Phone, Building2, CreditCard, Lock, Stethoscope } from 'lucide-react';

const EMPTY_DOCTOR = {
    name: '', qualification: '', unit_id: '', department: '',
    phone: '', email: '', gender: 'male', regno: '', password: '', photo: null
};

// ── Confirmation Modal ─────────────────────────────────────────────────────
const ConfirmModal = ({ form, unitLabel, preview, onConfirm, onBack, loading }) => {
    const rows = [
        { icon: <User size={15} />,        label: 'Full Name',       value: form.name },
        { icon: <Stethoscope size={15} />, label: 'Qualification',   value: form.qualification },
        { icon: <Building2 size={15} />,   label: 'Department',      value: form.department },
        { icon: <Building2 size={15} />,   label: 'Assigned Unit',   value: unitLabel },
        { icon: <Phone size={15} />,       label: 'Phone',           value: form.phone },
        { icon: <Mail size={15} />,        label: 'Email',           value: form.email },
        { icon: <User size={15} />,        label: 'Gender',          value: form.gender.charAt(0).toUpperCase() + form.gender.slice(1) },
        { icon: <CreditCard size={15} />,  label: 'Registration No', value: form.regno },
        { icon: <Lock size={15} />,        label: 'Password',        value: '••••••••' },
    ];

    return (
        <div style={{
            position: 'fixed', inset: 0,
            background: 'rgba(15,23,42,0.55)',
            backdropFilter: 'blur(6px)',
            zIndex: 1000,
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            padding: '1rem',
            animation: 'fadeIn 0.2s ease'
        }}>
            {/* Modal Card */}
            <div style={{
                background: 'white',
                borderRadius: '24px',
                padding: '2rem',
                width: '100%',
                maxWidth: '520px',
                maxHeight: '90vh',
                overflowY: 'auto',
                boxShadow: '0 25px 60px rgba(0,0,0,0.2)',
                animation: 'slideUp 0.25s ease'
            }}>
                {/* Modal Header */}
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
                    <div>
                        <h2 style={{ margin: 0, fontSize: '1.2rem', fontWeight: 900, color: '#0f172a' }}>Confirm Registration</h2>
                        <p style={{ margin: '0.2rem 0 0', fontSize: '0.8rem', color: '#94a3b8' }}>Please review the details before submitting.</p>
                    </div>
                    <button onClick={onBack} style={ms.closeBtn} aria-label="Go back">
                        <X size={18} />
                    </button>
                </div>

                {/* Photo + Name banner */}
                <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', padding: '1rem', background: '#fff5fa', borderRadius: '16px', marginBottom: '1.5rem', border: '1px solid #ffd6ec' }}>
                    <div style={ms.photoThumb}>
                        {preview
                            ? <img src={preview} alt="Doctor" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                            : <User size={28} style={{ color: '#ff0088' }} />
                        }
                    </div>
                    <div>
                        <p style={{ margin: 0, fontWeight: 900, fontSize: '1rem', color: '#0f172a' }}>{form.name}</p>
                        <p style={{ margin: 0, fontSize: '0.82rem', color: '#ff0088', fontWeight: 700 }}>{form.qualification}</p>
                        <p style={{ margin: 0, fontSize: '0.75rem', color: '#94a3b8' }}>{form.department}</p>
                    </div>
                </div>

                {/* Detail Rows */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '0.6rem', marginBottom: '1.75rem' }}>
                    {rows.map(({ icon, label, value }) => (
                        <div key={label} style={ms.row}>
                            <div style={ms.rowIcon}>{icon}</div>
                            <div style={{ flex: 1 }}>
                                <p style={ms.rowLabel}>{label}</p>
                                <p style={ms.rowValue}>{value || <span style={{ color: '#cbd5e1' }}>Not provided</span>}</p>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Actions */}
                <div style={{ display: 'flex', gap: '0.75rem' }}>
                    <button onClick={onBack} style={ms.btnBack} disabled={loading}>
                        ← Edit Details
                    </button>
                    <LoadingButton
                        type="button"
                        loading={loading}
                        label="Confirm & Register"
                        loadingLabel="Registering…"
                        onClick={onConfirm}
                        style={ms.btnConfirm}
                    />
                </div>
            </div>

            <style>{`
                @keyframes fadeIn  { from { opacity: 0 } to { opacity: 1 } }
                @keyframes slideUp { from { opacity: 0; transform: translateY(20px) } to { opacity: 1; transform: translateY(0) } }
                @keyframes spin    { to { transform: rotate(360deg) } }
            `}</style>
        </div>
    );
};

// ── Main DoctorTab Component ───────────────────────────────────────────────
const DoctorTab = ({ units, onDoctorAdded }) => {
    const toast = useToast();

    const [form,        setForm]        = useState(EMPTY_DOCTOR);
    const [preview,     setPreview]     = useState(null);
    const [showConfirm, setShowConfirm] = useState(false);
    const [loading,     setLoading]     = useState(false);

    const set = (key, val) => setForm(prev => ({ ...prev, [key]: val }));

    const handlePhoto = (e) => {
        const file = e.target.files[0];
        if (!file) return;
        set('photo', file);
        setPreview(URL.createObjectURL(file));
    };

    // Step 1: Show confirmation modal instead of submitting immediately
    const handleReview = (e) => {
        e.preventDefault();
        setShowConfirm(true);
    };

    // Step 2: Confirmed — submit to API
    const handleConfirmedSubmit = async () => {
        setLoading(true);
        const fd = new FormData();
        Object.entries(form).forEach(([k, v]) => { if (v) fd.append(k, v); });

        try {
            await api.post('/hospital/doctors', fd, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            toast.success(`Dr. ${form.name} has been registered successfully.`);
            setForm(EMPTY_DOCTOR);
            setPreview(null);
            setShowConfirm(false);
            onDoctorAdded?.();
        } catch (err) {
            toast.error(err.response?.data?.message || 'Failed to register doctor.');
            setShowConfirm(false); // close modal on error so admin can fix fields
        } finally {
            setLoading(false);
        }
    };

    // Derive the unit label for the modal
    const selectedUnit = units.find(u => String(u.unit_id) === String(form.unit_id));
    const unitLabel = selectedUnit ? `${selectedUnit.unit_name} — ${selectedUnit.day || ''}` : '—';

    return (
        <>
            {/* ── Confirmation Modal (portal-style, rendered above everything) ── */}
            {showConfirm && (
                <ConfirmModal
                    form={form}
                    unitLabel={unitLabel}
                    preview={preview}
                    onConfirm={handleConfirmedSubmit}
                    onBack={() => setShowConfirm(false)}
                    loading={loading}
                />
            )}

            <div>
                <h1 style={s.pageTitle}>Doctor Management</h1>

                <div style={s.card}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', marginBottom: '1.5rem' }}>
                        <UserPlus size={20} color="#ff0088" />
                        <h3 style={{ margin: 0, fontWeight: 800, fontSize: '1rem', color: '#0f172a' }}>Register New Doctor</h3>
                    </div>

                    {/* Progress hint */}
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginBottom: '1.5rem', padding: '0.7rem 1rem', background: '#eff6ff', borderRadius: '10px', border: '1px solid #bfdbfe' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
                            <span style={{ width: '22px', height: '22px', borderRadius: '50%', background: '#3b82f6', color: 'white', fontSize: '0.7rem', fontWeight: 900, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>1</span>
                            <span style={{ fontSize: '0.8rem', fontWeight: 700, color: '#1d4ed8' }}>Fill Details</span>
                        </div>
                        <div style={{ flex: 1, height: '2px', background: '#bfdbfe', margin: '0 0.5rem' }} />
                        <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', opacity: 0.5 }}>
                            <span style={{ width: '22px', height: '22px', borderRadius: '50%', background: '#94a3b8', color: 'white', fontSize: '0.7rem', fontWeight: 900, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>2</span>
                            <span style={{ fontSize: '0.8rem', fontWeight: 700, color: '#64748b' }}>Review</span>
                        </div>
                        <div style={{ flex: 1, height: '2px', background: '#bfdbfe', margin: '0 0.5rem' }} />
                        <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', opacity: 0.5 }}>
                            <span style={{ width: '22px', height: '22px', borderRadius: '50%', background: '#94a3b8', color: 'white', fontSize: '0.7rem', fontWeight: 900, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>3</span>
                            <span style={{ fontSize: '0.8rem', fontWeight: 700, color: '#64748b' }}>Registered</span>
                        </div>
                    </div>

                    <form onSubmit={handleReview}>
                        <div style={s.formGrid}>
                            {/* Column 1 */}
                            <div>
                                <Field label="Full Name *"         value={form.name}          onChange={v => set('name', v)}          required />
                                <Field label="Qualification *"     value={form.qualification}  onChange={v => set('qualification', v)} required placeholder="e.g. MD, MBBS" />
                                <div style={{ marginBottom: '0.85rem' }}>
                                    <label style={s.label}>Select Unit *</label>
                                    <select style={s.input} value={form.unit_id} onChange={e => set('unit_id', e.target.value)} required>
                                        <option value="">Select a unit…</option>
                                        {units.map(u => (
                                            <option key={u.unit_id} value={u.unit_id}>
                                                {u.unit_name} — {u.day || 'No day set'}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <Field label="Department *"        value={form.department}     onChange={v => set('department', v)}    required placeholder="e.g. Pulmonology" />
                                <Field label="Phone No *"          value={form.phone}          onChange={v => set('phone', v)}         required placeholder="+91 98765 43210" />
                            </div>

                            {/* Column 2 */}
                            <div>
                                <Field label="Registration No (Regno) *" value={form.regno} onChange={v => set('regno', v)} required placeholder="MCI Reg number" />
                                <Field label="Email" value={form.email} onChange={v => set('email', v)} type="email" placeholder="doctor@example.com" />
                                <div style={{ marginBottom: '0.85rem' }}>
                                    <label style={s.label}>Gender *</label>
                                    <select style={s.input} value={form.gender} onChange={e => set('gender', e.target.value)}>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <Field label="Password *" value={form.password} onChange={v => set('password', v)} type="password" required placeholder="Min. 6 characters" />

                                {/* Photo Upload */}
                                <div style={{ marginBottom: '1rem' }}>
                                    <label style={s.label}>Photo (jpeg, png)</label>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
                                        <div style={s.photoPreview}>
                                            {preview
                                                ? <img src={preview} alt="preview" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                                : <Camera size={22} style={{ color: '#cbd5e1' }} />
                                            }
                                        </div>
                                        <div>
                                            <input type="file" id="doctorPhoto" accept="image/jpeg,image/png" onChange={handlePhoto} style={{ display: 'none' }} />
                                            <label htmlFor="doctorPhoto" style={{ ...s.btnSecondary, cursor: 'pointer', display: 'inline-block' }}>
                                                {preview ? 'Change Photo' : 'Upload Photo'}
                                            </label>
                                            {form.photo && <p style={{ margin: '0.3rem 0 0', fontSize: '0.72rem', color: '#94a3b8' }}>{form.photo.name}</p>}
                                        </div>
                                    </div>
                                </div>

                                {/* Step 1 button: Review (not submit) */}
                                <LoadingButton
                                    loading={false}
                                    label="Review Details →"
                                    style={{ ...s.btnPrimary, width: '100%', marginTop: '0.75rem' }}
                                />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
};

// ── Shared sub-components ──────────────────────────────────────────────────
const Field = ({ label, value, onChange, required, type = 'text', placeholder = '' }) => (
    <div style={{ marginBottom: '0.85rem' }}>
        <label style={s.label}>{label}</label>
        <input
            type={type}
            style={s.input}
            value={value}
            onChange={e => onChange(e.target.value)}
            required={required}
            placeholder={placeholder}
        />
    </div>
);

// ── Styles ─────────────────────────────────────────────────────────────────
const s = {
    pageTitle:    { fontSize: '1.5rem', fontWeight: 900, color: '#0f172a', marginBottom: '1.5rem' },
    card:         { background: 'white', borderRadius: '20px', padding: '1.75rem', border: '1px solid #e2e8f0' },
    formGrid:     { display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '2rem' },
    input:        { width: '100%', padding: '0.75rem 1rem', border: '2px solid #e2e8f0', borderRadius: '10px', fontSize: '0.875rem', fontFamily: 'inherit', outline: 'none', boxSizing: 'border-box' },
    label:        { display: 'block', fontSize: '0.72rem', fontWeight: 700, color: '#374151', marginBottom: '0.4rem', textTransform: 'uppercase', letterSpacing: '0.05em' },
    btnPrimary:   { background: '#ff0088', color: 'white', border: 'none', borderRadius: '10px', padding: '0.8rem 1.5rem', fontWeight: 700, cursor: 'pointer', fontFamily: 'inherit', fontSize: '0.9rem', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '0.5rem' },
    btnSecondary: { background: '#f8fafc', color: '#0f172a', border: '1px solid #e2e8f0', borderRadius: '8px', padding: '0.5rem 1rem', fontWeight: 700, fontFamily: 'inherit', fontSize: '0.82rem' },
    photoPreview: { width: '64px', height: '64px', borderRadius: '12px', background: '#f8fafc', border: '2px solid #e2e8f0', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden', flexShrink: 0 },
};

// Modal styles
const ms = {
    closeBtn:   { background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '8px', width: '34px', height: '34px', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#475569' },
    photoThumb: { width: '60px', height: '60px', borderRadius: '14px', background: '#ffd6ec', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden', flexShrink: 0 },
    row:        { display: 'flex', alignItems: 'flex-start', gap: '0.75rem', padding: '0.65rem 0.85rem', borderRadius: '10px', background: '#fafafa', border: '1px solid #f1f5f9' },
    rowIcon:    { color: '#ff0088', marginTop: '2px', flexShrink: 0 },
    rowLabel:   { margin: 0, fontSize: '0.68rem', fontWeight: 700, color: '#94a3b8', textTransform: 'uppercase', letterSpacing: '0.04em' },
    rowValue:   { margin: '0.1rem 0 0', fontSize: '0.875rem', fontWeight: 600, color: '#0f172a' },
    btnBack:    { flex: 1, padding: '0.8rem', border: '2px solid #e2e8f0', borderRadius: '12px', background: 'white', cursor: 'pointer', fontWeight: 700, color: '#475569', fontFamily: 'inherit', fontSize: '0.875rem' },
    btnConfirm: { flex: 2, padding: '0.8rem', border: 'none', borderRadius: '12px', background: '#ff0088', color: 'white', cursor: 'pointer', fontWeight: 800, fontFamily: 'inherit', fontSize: '0.9rem', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.5rem' },
    spinner:    { width: '14px', height: '14px', border: '2px solid rgba(255,255,255,0.3)', borderTopColor: 'white', borderRadius: '50%', animation: 'spin 0.7s linear infinite', display: 'inline-block' },
};

export default DoctorTab;
