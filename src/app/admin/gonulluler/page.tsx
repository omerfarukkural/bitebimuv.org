import { createAdminClient } from '@/lib/supabase/server'
import { formatDate } from '@/lib/utils'
import { Users, CheckCircle, XCircle, Clock } from 'lucide-react'
import type { Volunteer, VolunteerApplication } from '@/types/supabase'

export default async function AdminVolunteersPage() {
  const supabase = await createAdminClient()
  const { data: rawApps } = await supabase
    .from('volunteer_applications')
    .select('*')
    .order('applied_at', { ascending: false })
  const { data: rawVols } = await supabase
    .from('volunteers')
    .select('*')
    .order('joined_at', { ascending: false })
  const apps = (rawApps ?? []) as VolunteerApplication[]
  const volunteers = (rawVols ?? []) as Volunteer[]

  const pending = apps.filter(a => a.status === 'pending')
  const badgeLevels: Record<string,string> = { beginner:'🌱 Başlangıç', helper:'⭐ Yardımcı', champion:'🏆 Şampiyon', legend:'👑 Efsane' }

  return (
    <div>
      <h1 className="font-display text-3xl font-semibold mb-8">Gönüllü Yönetimi</h1>

      {/* İstatistikler */}
      <div className="grid grid-cols-4 gap-4 mb-8">
        {[
          { label: 'Aktif Gönüllü', value: volunteers.filter(v=>v.status==='active').length, color:'text-sage' },
          { label: 'Bekleyen Başvuru', value: pending.length, color:'text-gold' },
          { label: 'Toplam Etkinlik Saati', value: volunteers.reduce((s,v)=>s+v.total_hours,0), color:'text-gold' },
          { label: 'Toplam Gönüllü', value: volunteers.length, color:'text-ink' },
        ].map(s => (
          <div key={s.label} className="card text-center">
            <p className={`font-display text-3xl font-semibold mb-1 ${s.color}`}>{s.value}</p>
            <p className="text-xs text-muted">{s.label}</p>
          </div>
        ))}
      </div>

      {/* Bekleyen Başvurular */}
      {pending.length > 0 && (
        <section className="mb-8">
          <h2 className="font-semibold mb-4 flex items-center gap-2">
            <Clock size={16} className="text-gold" /> Bekleyen Başvurular
          </h2>
          <div className="space-y-3">
            {pending.map(a => (
              <div key={a.id} className="card border-gold/20">
                <div className="flex items-start justify-between gap-4">
                  <div>
                    <p className="font-medium">{a.name} — <span className="text-muted text-sm">{a.email}</span></p>
                    <p className="text-sm text-muted mt-1">{a.city} · {a.occupation}</p>
                    <p className="text-sm text-muted mt-1 leading-relaxed max-w-lg">&quot;{a.motivation}&quot;</p>
                    {(a.skills ?? []).length > 0 && (
                      <div className="flex gap-1.5 flex-wrap mt-2">
                        {(a.skills ?? []).map((s: string) => <span key={s} className="badge badge-muted">{s}</span>)}
                      </div>
                    )}
                  </div>
                  <div className="flex gap-2 flex-shrink-0">
                    <form action="/api/admin/volunteers/approve" method="POST">
                      <input type="hidden" name="id" value={a.id} />
                      <button className="btn-secondary text-sm gap-1.5 text-sage border-sage/30">
                        <CheckCircle size={14} /> Onayla
                      </button>
                    </form>
                    <form action="/api/admin/volunteers/reject" method="POST">
                      <input type="hidden" name="id" value={a.id} />
                      <button className="btn-secondary text-sm gap-1.5 text-ember border-ember/30">
                        <XCircle size={14} /> Reddet
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </section>
      )}

      {/* Aktif Gönüllüler */}
      <section>
        <h2 className="font-semibold mb-4 flex items-center gap-2">
          <Users size={16} className="text-gold" /> Aktif Gönüllüler
        </h2>
        <div className="card overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="text-left text-xs font-mono text-muted border-b border-border/60">
                <th className="pb-2 pr-4">Gönüllü</th>
                <th className="pb-2 pr-4">Şehir</th>
                <th className="pb-2 pr-4">Rozet</th>
                <th className="pb-2 pr-4">Etkinlik</th>
                <th className="pb-2 pr-4">Saat</th>
                <th className="pb-2">Katılım</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border/30">
              {volunteers.filter(v=>v.status==='active').map(v => (
                <tr key={v.id} className="hover:bg-surface2/50">
                  <td className="py-2.5 pr-4">
                    <div>
                      <p className="font-medium">{v.name}</p>
                      <p className="text-xs text-muted">{v.email}</p>
                    </div>
                  </td>
                  <td className="py-2.5 pr-4 text-muted">{v.city ?? '—'}</td>
                  <td className="py-2.5 pr-4">
                    <span className="text-xs">{badgeLevels[v.badge_level] ?? v.badge_level}</span>
                  </td>
                  <td className="py-2.5 pr-4 font-mono text-center">{v.total_events}</td>
                  <td className="py-2.5 pr-4 font-mono text-center">{v.total_hours}</td>
                  <td className="py-2.5 text-xs text-muted">{formatDate(v.joined_at)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </section>
    </div>
  )
}
