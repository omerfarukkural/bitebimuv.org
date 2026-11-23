import { createAdminClient } from '@/lib/supabase/server'
import { formatCurrency, formatDate } from '@/lib/utils'
import { Heart, TrendingUp, Download } from 'lucide-react'
import type { Donation } from '@/types/supabase'

export default async function AdminDonationsPage() {
  const supabase = await createAdminClient()
  const { data: rawDonations } = await supabase
    .from('donations')
    .select('*')
    .order('created_at', { ascending: false })
  const donations = (rawDonations ?? []) as Donation[]

  const all = donations
  const completed = all.filter(d => d.payment_status === 'completed')
  const total = completed.reduce((s, d) => s + Number(d.amount), 0)
  const monthly = completed.filter(d => d.donation_type === 'monthly').reduce((s,d)=>s+Number(d.amount),0)
  const thisMonth = completed.filter(d => {
    const m = new Date(d.created_at)
    const n = new Date()
    return m.getMonth() === n.getMonth() && m.getFullYear() === n.getFullYear()
  }).reduce((s,d)=>s+Number(d.amount),0)

  const STATUS_STYLE: Record<string,string> = {
    completed: 'text-sage border-sage/30 bg-sage/10',
    pending: 'text-gold border-gold/30 bg-gold/10',
    failed: 'text-ember border-ember/30 bg-ember/10',
    refunded: 'text-muted border-border/60',
  }

  return (
    <div>
      <div className="flex items-center justify-between mb-8">
        <div>
          <h1 className="font-display text-3xl font-semibold">Bağış Takibi</h1>
          <p className="text-muted mt-1">Tüm bağışlar ve ödeme durumları</p>
        </div>
        <button className="btn-secondary text-sm gap-2">
          <Download size={14} /> CSV İndir
        </button>
      </div>

      {/* Özet */}
      <div className="grid grid-cols-4 gap-4 mb-8">
        <div className="card">
          <Heart size={18} className="text-gold mb-3" />
          <p className="font-display text-2xl font-semibold text-gold">{formatCurrency(total)}</p>
          <p className="text-xs text-muted mt-1">Toplam Bağış</p>
        </div>
        <div className="card">
          <TrendingUp size={18} className="text-sage mb-3" />
          <p className="font-display text-2xl font-semibold text-sage">{formatCurrency(thisMonth)}</p>
          <p className="text-xs text-muted mt-1">Bu Ay</p>
        </div>
        <div className="card">
          <p className="text-xl mb-3">🔄</p>
          <p className="font-display text-2xl font-semibold">{formatCurrency(monthly)}</p>
          <p className="text-xs text-muted mt-1">Aylık Abonelik</p>
        </div>
        <div className="card">
          <p className="text-xl mb-3">📊</p>
          <p className="font-display text-2xl font-semibold">{completed.length}</p>
          <p className="text-xs text-muted mt-1">Tamamlanan İşlem</p>
        </div>
      </div>

      {/* Tablo */}
      <div className="card overflow-x-auto">
        <table className="w-full text-sm">
          <thead>
            <tr className="text-left text-xs font-mono text-muted border-b border-border/60">
              <th className="pb-2 pr-4">Bağışçı</th>
              <th className="pb-2 pr-4">Miktar</th>
              <th className="pb-2 pr-4">Tür</th>
              <th className="pb-2 pr-4">Sağlayıcı</th>
              <th className="pb-2 pr-4">Durum</th>
              <th className="pb-2">Tarih</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-border/30">
            {all.map(d => (
              <tr key={d.id} className="hover:bg-surface2/50">
                <td className="py-2.5 pr-4">
                  <p className="font-medium">{d.is_anonymous ? 'Anonim' : (d.donor_name ?? 'İsimsiz')}</p>
                  <p className="text-xs text-muted">{d.is_anonymous ? '' : d.donor_email}</p>
                </td>
                <td className="py-2.5 pr-4 font-mono font-semibold text-gold">
                  {formatCurrency(Number(d.amount), d.currency)}
                </td>
                <td className="py-2.5 pr-4 text-muted capitalize">
                  {d.donation_type?.replace('_',' ')}
                </td>
                <td className="py-2.5 pr-4 text-muted capitalize">{d.payment_provider ?? '—'}</td>
                <td className="py-2.5 pr-4">
                  <span className={`text-[10px] font-mono px-2 py-0.5 rounded-full border ${STATUS_STYLE[d.payment_status] ?? ''}`}>
                    {d.payment_status}
                  </span>
                </td>
                <td className="py-2.5 text-xs text-muted">{formatDate(d.created_at)}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
