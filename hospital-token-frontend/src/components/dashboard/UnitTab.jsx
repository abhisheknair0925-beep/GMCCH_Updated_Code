import React, { useState } from 'react';
import api from '../../lib/axios';
import { useToast } from '../Toast';
import { LoadingButton } from '../Spinner';
import { Layers, Calendar } from 'lucide-react';

const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
const EMPTY_UNIT = { name: '', day: 'Monday' };

const UnitTab = ({ units, onUnitAdded }) => {
    const toast = useToast();

    const [form,    setForm]    = useState(EMPTY_UNIT);
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            await api.post('/hospital/units', form);
            toast.success(`Unit "${form.name}" created for ${form.day}.`);
            setForm(EMPTY_UNIT);
            onUnitAdded?.();
        } catch (err) {
            toast.error(err.response?.data?.message || 'Failed to create unit.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <h1 style={s.pageTitle}>Unit Management</h1>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '1.5rem' }}>

                {/* ── Add Unit Form ── */}
                <div style={s.card}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', marginBottom: '1rem' }}>
                        <Layers size={18} color="#ff0088" />
                        <h3 style={{ margin: 0, fontWeight: 800, fontSize: '0.95rem', color: '#0f172a' }}>Add New Unit</h3>
                    </div>
                    <p style={s.desc}>Create a unit by number and assign its operating day.</p>

                    <form onSubmit={handleSubmit}>
                        <div style={{ marginBottom: '0.85rem' }}>
                            <label style={s.label}>Unit Number / Name *</label>
                            <input
                                style={s.input}
                                placeholder="e.g., Unit 1"
                                value={form.name}
                                onChange={e => setForm({ ...form, name: e.target.value })}
                                required
                            />
                        </div>
                        <div style={{ marginBottom: '1.25rem' }}>
                            <label style={s.label}>Operating Day *</label>
                            <select
                                style={s.input}
                                value={form.day}
                                onChange={e => setForm({ ...form, day: e.target.value })}
                                required
                            >
                                {DAYS.map(d => <option key={d} value={d}>{d}</option>)}
                            </select>
                        </div>
                        <LoadingButton
                            loading={loading}
                            label="Create Unit"
                            loadingLabel="Creating…"
                            style={{ ...s.btnPrimary, width: '100%' }}
                        />
                    </form>
                </div>

                {/* ── Existing Units List ── */}
                <div style={s.card}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', marginBottom: '1rem' }}>
                        <Calendar size={18} color="#ff0088" />
                        <h3 style={{ margin: 0, fontWeight: 800, fontSize: '0.95rem', color: '#0f172a' }}>
                            Existing Units{' '}
                            <span style={{ fontWeight: 400, color: '#94a3b8' }}>({units.length})</span>
                        </h3>
                    </div>

                    {units.length === 0 ? (
                        <p style={s.desc}>No units have been created yet.</p>
                    ) : (
                        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
                            {units.map(unit => (
                                <div key={unit.unit_id} style={s.unitRow}>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                                        <div style={s.unitBadge}>
                                            {(unit.unit_name || '?').charAt(0)}
                                        </div>
                                        <div>
                                            <p style={{ margin: 0, fontWeight: 800, fontSize: '0.9rem', color: '#0f172a' }}>{unit.unit_name}</p>
                                            <p style={{ margin: 0, fontSize: '0.75rem', color: '#94a3b8' }}>
                                                Dr. {unit.doctor_name || 'Not assigned'}
                                            </p>
                                        </div>
                                    </div>
                                    <span style={s.dayPill}>{unit.day || 'No day'}</span>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

// ── Styles ─────────────────────────────────────────────────────────────────
const s = {
    pageTitle: { fontSize: '1.5rem', fontWeight: 900, color: '#0f172a', marginBottom: '1.5rem' },
    card:      { background: 'white', borderRadius: '20px', padding: '1.75rem', border: '1px solid #e2e8f0' },
    input:     { width: '100%', padding: '0.75rem 1rem', border: '2px solid #e2e8f0', borderRadius: '10px', fontSize: '0.875rem', fontFamily: 'inherit', outline: 'none', boxSizing: 'border-box' },
    label:     { display: 'block', fontSize: '0.72rem', fontWeight: 700, color: '#374151', marginBottom: '0.4rem', textTransform: 'uppercase', letterSpacing: '0.05em' },
    btnPrimary:{ background: '#ff0088', color: 'white', border: 'none', borderRadius: '10px', padding: '0.8rem 1.5rem', fontWeight: 700, cursor: 'pointer', fontFamily: 'inherit', fontSize: '0.9rem' },
    desc:      { fontSize: '0.82rem', color: '#64748b', marginBottom: '1rem', lineHeight: 1.6 },
    unitRow:   { display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '0.85rem 1rem', borderRadius: '12px', background: '#fafafa', border: '1px solid #f1f5f9' },
    unitBadge: { width: '36px', height: '36px', borderRadius: '10px', background: '#fff5fa', border: '1px solid #ffd6ec', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 900, fontSize: '0.85rem', color: '#ff0088', flexShrink: 0 },
    dayPill:   { background: '#f1f5f9', color: '#475569', padding: '0.3rem 0.75rem', borderRadius: '20px', fontSize: '0.72rem', fontWeight: 700 },
};

export default UnitTab;
