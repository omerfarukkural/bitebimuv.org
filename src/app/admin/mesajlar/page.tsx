import { createAdminClient } from '@/lib/supabase/server'
import { formatDateTime } from '@/lib/utils'
import { MessageSquare, Mail, Phone } from 'lucide-react'
import type { ContactMessage } from '@/types/supabase'

export default async function AdminMessagesPage() {
  const supabase = await createAdminClient()
  const { data } = await supabase
    .from('contact_messages')
    .select('*')
    .order('created_at', { ascending: false })

  const messages = (data ?? []) as ContactMessage[]
  const unread = messages.filter(m => !m.is_read)

  return (
    <div>
      <h1 className="font-display text-3xl font-semibold mb-8">
        İletişim Mesajları
        {unread.length > 0 && (
          <span className="ml-3 badge badge-ember text-base">{unread.length} yeni</span>
        )}
      </h1>
      <div className="space-y-3">
        {messages.map(m => (
          <div key={m.id} className={`card ${!m.is_read ? 'border-gold/30 bg-gold/3' : ''}`}>
            <div className="flex items-start justify-between gap-4">
              <div className="flex-1">
                <div className="flex items-center gap-3 mb-2">
                  {!m.is_read && <span className="w-2 h-2 rounded-full bg-gold flex-shrink-0" />}
                  <p className="font-semibold">{m.name}</p>
                  {m.subject && <span className="text-sm text-muted">— {m.subject}</span>}
                </div>
                <div className="flex gap-4 text-xs text-muted mb-3">
                  <span className="flex items-center gap-1"><Mail size={11} /> {m.email}</span>
                  {m.phone && <span className="flex items-center gap-1"><Phone size={11} /> {m.phone}</span>}
                </div>
                <p className="text-sm text-muted leading-relaxed">{m.message}</p>
              </div>
              <div className="flex flex-col items-end gap-2 flex-shrink-0">
                <p className="text-xs text-muted">{formatDateTime(m.created_at)}</p>
                <div className="flex gap-2">
                  {!m.is_read && (
                    <form action="/api/admin/messages/read" method="POST">
                      <input type="hidden" name="id" value={m.id} />
                      <button className="btn-secondary text-xs py-1.5 px-3">Okundu İşaretle</button>
                    </form>
                  )}
                  <a href={`mailto:${m.email}?subject=Re: ${m.subject ?? 'İletişim'}`}
                    className="btn-primary text-xs py-1.5 px-3">
                    Yanıtla
                  </a>
                </div>
              </div>
            </div>
          </div>
        ))}
        {messages.length === 0 && (
          <div className="text-center py-16 text-muted">
            <MessageSquare size={40} className="mx-auto mb-4 opacity-30" />
            <p>Henüz mesaj yok</p>
          </div>
        )}
      </div>
    </div>
  )
}
