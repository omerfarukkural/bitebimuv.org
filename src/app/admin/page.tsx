import { createAdminClient } from '@/lib/supabase/server'
import { formatCurrency } from '@/lib/utils'
import { Users, Heart, Calendar, MessageSquare, TrendingUp, Clock, CheckCircle, AlertCircle } from 'lucide-react'

async function getStats() {
  const supabase = await createAdminClient()
  const [regs, donations, volunteers, messages, pending_regs, pending_vols] = await Promise.all([
    supabase.from('event_registrations').select('*', { count: 'exact', head: true }),
    supabase.from('donations').select('amount').eq('payment_status', 'completed'),
    supabase.from('volunteers').select('*', { count: 'exact', head: true }).eq('status', 'active'),
    supabase.from('contact_messages').select('*', { count: 'exact', head: true }).eq('is_read', false),
    supabase.from('event_registrations').select('*', { count: 'exact', head: true }).eq('status', 'pending'),
    supabase.from('volunteer_applications').select('*', { count: 'exact', head: true }).eq('status', 'pending'),
  ])
  const totalDonations = (donations.data ?? []).reduce((s: number, d: any) => s + Number(d.amount), 0)
  return {
    totalRegistrations: regs.count ?? 0,
    totalDonations,
    activeVolunteers: volunteers.count ?? 0,
    unreadMessages: messages.count ?? 0,
    pendingRegistrations: pending_regs.count ?? 0,
    pendingVolunteers: pending_vols.count ?? 0,
  }
}

async function getRecentActivity() {
  const supabase = await createAdminClient()
  const [regs, donations] = await Promise.all([
    supabase.from('event_registrations').select('name,event_title,registered_at,status').order('registered_at', { ascending: false }).limit(5),
    supabase.from('donations').select('donor_name,amount,currency,created_at,donation_type').order('created_at', { ascending: false }).limit(5),
  ])
  return { regs: (regs.data ?? []) as any[], donations: (donations.data ?? []) as any[] }
}

function StatCard({ label, value, icon: Icon, sub, alert }: {
  label: string; value: string | number; icon: React.ElementType; sub?: string; alert?: boolean
}) {
  return (
    <div className={`card ${alert ? 'border-ember/30 bg-ember/5' : ''}`}>
      <div className="flex items-start justify-between mb-4">
        <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${alert ? 'bg-ember/10' : 'bg-gold/10'}`}>
          <Icon size={18} className={alert ? 'text-ember' : 'text-gold'} />
        </div>
        {alert && <span className="w-2 h-2 rounded-full bg-ember animate-pulse" />}
      </div>
      <p className={`font-display text-3xl font-semibold mb-1 ${alert ? 'text-ember' : 'text-gold'}`}>{value}</p>
      <p className="text-sm text-muted">{label}</p>
      {sub && <p className="text-xs text-muted/60 mt-1">{sub}</p>}
    </div>
  )
}

export default async function AdminDashboard() {
  const [stats, activity] = await Promise.all([getStats(), getRecentActivity()])

  return (
    <div>
      <div className="mb-8">
        <h1 className="font-display text-3xl font-semibold">Dashboard</h1>
        <p className="text-muted mt-1">Tebessüm Bin Mutluluk — Genel Bakış</p>
      </div>

      {/* Uyarılar */}
      {(stats.pendingRegistrations > 0 || stats.pendingVolunteers > 0 || stats.unreadMessages > 0) && (
        <div className="mb-6 space-y-2">
          {stats.pendingRegistrations > 0 && (
            <div className="flex items-center gap-3 p-4 bg-gold/5 border border-gold/20 rounded-xl text-sm">
              <AlertCircle size={16} className="text-gold flex-shrink-0" />
              <span><strong className="text-gold">{stats.pendingRegistrations}</strong> etkinlik kaydı onay bekliyor</span>
              <a href="/admin/etkinlikler?filter=pending" className="ml-auto text-gold hover:underline text-xs">İncele →</a>
            </div>
          )}
          {stats.pendingVolunteers > 0 && (
            <div className="flex items-center gap-3 p-4 bg-gold/5 border border-gold/20 rounded-xl text-sm">
              <AlertCircle size={16} className="text-gold flex-shrink-0" />
              <span><strong className="text-gold">{stats.pendingVolunteers}</strong> gönüllü başvurusu bekliyor</span>
              <a href="/admin/gonulluler?filter=pending" className="ml-auto text-gold hover:underline text-xs">İncele →</a>
            </div>
          )}
          {stats.unreadMessages > 0 && (
            <div className="flex items-center gap-3 p-4 bg-ember/5 border border-ember/20 rounded-xl text-sm">
              <AlertCircle size={16} className="text-ember flex-shrink-0" />
              <span><strong className="text-ember">{stats.unreadMessages}</strong> okunmamış mesaj var</span>
              <a href="/admin/mesajlar" className="ml-auto text-ember hover:underline text-xs">Gör →</a>
            </div>
          )}
        </div>
      )}

      {/* Stats Grid */}
      <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <StatCard label="Toplam Etkinlik Kaydı" value={stats.totalRegistrations.toLocaleString('tr')} icon={Calendar} />
        <StatCard label="Toplam Bağış" value={formatCurrency(stats.totalDonations)} icon={Heart} />
        <StatCard label="Aktif Gönüllüler" value={stats.activeVolunteers} icon={Users} />
        <StatCard label="Okunmamış Mesaj" value={stats.unreadMessages} icon={MessageSquare} alert={stats.unreadMessages > 0} />
      </div>

      <div className="grid lg:grid-cols-2 gap-6">
        {/* Son Kayıtlar */}
        <div className="card">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-semibold flex items-center gap-2">
              <Clock size={16} className="text-gold" /> Son Etkinlik Kayıtları
            </h2>
            <a href="/admin/etkinlikler" className="text-xs text-gold hover:underline">Tümü →</a>
          </div>
          <div className="space-y-3">
            {activity.regs.length === 0 && <p className="text-sm text-muted py-4 text-center">Henüz kayıt yok</p>}
            {activity.regs.map((r, i) => (
              <div key={i} className="flex items-center gap-3 p-3 bg-surface2 rounded-lg">
                <div className="w-8 h-8 rounded-full bg-gold/10 border border-gold/20 flex items-center justify-center text-sm">
                  {r.name?.[0]?.toUpperCase() ?? '?'}
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium truncate">{r.name}</p>
                  <p className="text-xs text-muted truncate">{r.event_title}</p>
                </div>
                <span className={`text-[10px] font-mono px-2 py-0.5 rounded-full border ${
                  r.status === 'approved' ? 'text-sage border-sage/30 bg-sage/10' :
                  r.status === 'pending'  ? 'text-gold border-gold/30 bg-gold/10' :
                  'text-muted border-border/60'
                }`}>
                  {r.status === 'pending' ? 'Bekliyor' : r.status === 'approved' ? 'Onaylı' : r.status}
                </span>
              </div>
            ))}
          </div>
        </div>

        {/* Son Bağışlar */}
        <div className="card">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-semibold flex items-center gap-2">
              <Heart size={16} className="text-gold" /> Son Bağışlar
            </h2>
            <a href="/admin/bagislar" className="text-xs text-gold hover:underline">Tümü →</a>
          </div>
          <div className="space-y-3">
            {activity.donations.length === 0 && <p className="text-sm text-muted py-4 text-center">Henüz bağış yok</p>}
            {activity.donations.map((d, i) => (
              <div key={i} className="flex items-center gap-3 p-3 bg-surface2 rounded-lg">
                <div className="w-8 h-8 rounded-full bg-ember/10 border border-ember/20 flex items-center justify-center text-base">
                  ❤
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium">{d.donor_name ?? 'Anonim'}</p>
                  <p className="text-xs text-muted capitalize">{d.donation_type?.replace('_',' ')}</p>
                </div>
                <span className="font-mono text-sm font-semibold text-gold">
                  {formatCurrency(Number(d.amount), d.currency)}
                </span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
