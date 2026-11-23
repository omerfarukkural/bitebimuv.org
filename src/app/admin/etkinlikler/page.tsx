import { createAdminClient } from '@/lib/supabase/server'
import { formatDateTime } from '@/lib/utils'
import { CheckCircle, XCircle, Clock, Users, Calendar, ExternalLink } from 'lucide-react'
import Link from 'next/link'

import type { EventRegistration } from '@/types/supabase'

async function getEventRegistrations(): Promise<EventRegistration[]> {
  const supabase = await createAdminClient()
  const { data } = await supabase
    .from('event_registrations')
    .select('*')
    .order('registered_at', { ascending: false })
  return (data ?? []) as EventRegistration[]
}

const STATUS_STYLES: Record<string, string> = {
  pending:   'text-gold border-gold/30 bg-gold/10',
  approved:  'text-sage border-sage/30 bg-sage/10',
  rejected:  'text-ember border-ember/30 bg-ember/10',
  cancelled: 'text-muted border-border/60 bg-surface',
  attended:  'text-blue-400 border-blue-400/30 bg-blue-400/10',
}
const STATUS_LABELS: Record<string, string> = {
  pending: 'Bekliyor', approved: 'Onaylı', rejected: 'Reddedildi',
  cancelled: 'İptal', attended: 'Katıldı',
}

export default async function AdminEventsPage() {
  const registrations = await getEventRegistrations()
  const pending = registrations.filter(r => r.status === 'pending')
  const grouped = registrations.reduce((acc, r) => {
    const key = r.event_title ?? r.event_id
    if (!acc[key]) acc[key] = []
    acc[key].push(r)
    return acc
  }, {} as Record<string, typeof registrations>)

  return (
    <div>
      <div className="flex items-center justify-between mb-8">
        <div>
          <h1 className="font-display text-3xl font-semibold">Etkinlik Yönetimi</h1>
          <p className="text-muted mt-1">Kayıtlar & Katılımcılar</p>
        </div>
        <div className="flex gap-3">
          <a href="https://bitebimuv.sanity.studio" target="_blank" rel="noreferrer"
            className="btn-secondary text-sm gap-2">
            <ExternalLink size={14} /> Sanity Studio
          </a>
        </div>
      </div>

      {/* Bekleyen onaylar */}
      {pending.length > 0 && (
        <div className="mb-8 p-5 bg-gold/5 border border-gold/20 rounded-xl">
          <h2 className="font-semibold text-gold mb-3 flex items-center gap-2">
            <Clock size={16} /> {pending.length} Kayıt Onay Bekliyor
          </h2>
          <div className="space-y-2">
            {pending.slice(0, 5).map(r => (
              <div key={r.id} className="flex items-center gap-4 p-3 bg-surface rounded-lg">
                <div className="flex-1">
                  <p className="text-sm font-medium">{r.name} — <span className="text-muted">{r.email}</span></p>
                  <p className="text-xs text-muted mt-0.5">{r.event_title} · {formatDateTime(r.registered_at)}</p>
                  {r.is_volunteer && <span className="text-xs text-gold">🤝 Gönüllü olarak katılmak istiyor</span>}
                </div>
                <div className="flex gap-2">
                  <form action="/api/admin/registrations/approve" method="POST" className="inline">
                    <input type="hidden" name="id" value={r.id} />
                    <button type="submit" className="p-2 rounded-lg bg-sage/10 text-sage border border-sage/30 hover:bg-sage/20 transition-all" title="Onayla">
                      <CheckCircle size={16} />
                    </button>
                  </form>
                  <form action="/api/admin/registrations/reject" method="POST" className="inline">
                    <input type="hidden" name="id" value={r.id} />
                    <button type="submit" className="p-2 rounded-lg bg-ember/10 text-ember border border-ember/30 hover:bg-ember/20 transition-all" title="Reddet">
                      <XCircle size={16} />
                    </button>
                  </form>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Etkinliklere göre gruplu liste */}
      <div className="space-y-6">
        {Object.entries(grouped).map(([eventTitle, regs]) => (
          <div key={eventTitle} className="card">
            <div className="flex items-center justify-between mb-4">
              <h3 className="font-semibold flex items-center gap-2">
                <Calendar size={16} className="text-gold" /> {eventTitle}
              </h3>
              <div className="flex items-center gap-2">
                <span className="badge badge-gold"><Users size={10} /> {regs.length} kayıt</span>
                <span className="badge badge-sage">{regs.filter(r=>r.status==='approved').length} onaylı</span>
                {regs.filter(r=>r.status==='pending').length > 0 && (
                  <span className="badge badge-muted">{regs.filter(r=>r.status==='pending').length} bekliyor</span>
                )}
              </div>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="text-left text-xs font-mono text-muted border-b border-border/60">
                    <th className="pb-2 pr-4">Ad Soyad</th>
                    <th className="pb-2 pr-4">E-posta</th>
                    <th className="pb-2 pr-4">Telefon</th>
                    <th className="pb-2 pr-4">Şehir</th>
                    <th className="pb-2 pr-4">Gönüllü</th>
                    <th className="pb-2 pr-4">Kayıt Tarihi</th>
                    <th className="pb-2">Durum</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-border/30">
                  {regs.map(r => (
                    <tr key={r.id} className="hover:bg-surface2/50 transition-colors">
                      <td className="py-2.5 pr-4 font-medium">{r.name}</td>
                      <td className="py-2.5 pr-4 text-muted">{r.email}</td>
                      <td className="py-2.5 pr-4 text-muted">{r.phone ?? '—'}</td>
                      <td className="py-2.5 pr-4 text-muted">{r.city ?? '—'}</td>
                      <td className="py-2.5 pr-4">{r.is_volunteer ? '🤝 Evet' : '—'}</td>
                      <td className="py-2.5 pr-4 text-muted text-xs">{formatDateTime(r.registered_at)}</td>
                      <td className="py-2.5">
                        <span className={`text-[10px] font-mono px-2 py-0.5 rounded-full border ${STATUS_STYLES[r.status] ?? ''}`}>
                          {STATUS_LABELS[r.status] ?? r.status}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        ))}
        {Object.keys(grouped).length === 0 && (
          <div className="text-center py-16 text-muted">
            <Calendar size={40} className="mx-auto mb-4 opacity-30" />
            <p>Henüz etkinlik kaydı yok</p>
          </div>
        )}
      </div>
    </div>
  )
}
