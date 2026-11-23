'use client'

import { useState } from 'react'
import { createClient } from '@/lib/supabase/client'
import { useRouter } from 'next/navigation'
import { Loader2, Lock } from 'lucide-react'

export default function AdminLoginPage() {
  const [email, setEmail]       = useState('')
  const [password, setPassword] = useState('')
  const [loading, setLoading]   = useState(false)
  const [error, setError]       = useState('')
  const router = useRouter()

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError('')
    const supabase = createClient()
    const { error: err } = await supabase.auth.signInWithPassword({ email, password })
    if (err) { setError(err.message); setLoading(false); return }
    router.push('/admin')
    router.refresh()
  }

  return (
    <div className="min-h-screen bg-ground flex items-center justify-center px-4">
      <div className="w-full max-w-sm">
        <div className="text-center mb-8">
          <span className="w-12 h-12 rounded-xl bg-gold flex items-center justify-center text-ground text-2xl mx-auto mb-4">☺</span>
          <h1 className="font-display text-2xl font-semibold">Admin Girişi</h1>
          <p className="text-muted text-sm mt-1">Tebessüm Bin Mutluluk</p>
        </div>
        <form onSubmit={handleLogin} className="bg-surface border border-border/60 rounded-2xl p-6 space-y-4">
          <div>
            <label className="label">E-posta</label>
            <input type="email" value={email} onChange={e=>setEmail(e.target.value)}
              className="input" placeholder="admin@bitebimuv.org" required />
          </div>
          <div>
            <label className="label">Şifre</label>
            <input type="password" value={password} onChange={e=>setPassword(e.target.value)}
              className="input" placeholder="••••••••" required />
          </div>
          {error && <p className="text-sm text-ember">{error}</p>}
          <button type="submit" disabled={loading} className="btn-primary w-full justify-center">
            {loading ? <><Loader2 size={16} className="animate-spin" /> Giriş yapılıyor...</> : <><Lock size={16} /> Giriş Yap</>}
          </button>
        </form>
      </div>
    </div>
  )
}
