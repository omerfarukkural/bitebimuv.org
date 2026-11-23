import type { Metadata } from 'next'
import { sanityFetch } from '@/lib/sanity/client'
import { EVENTS_QUERY } from '@/lib/sanity/queries'
import { EventCard } from '@/components/events/EventCard'
import { Calendar, Filter } from 'lucide-react'
import { EVENT_CATEGORIES } from '@/lib/utils'

export const metadata: Metadata = {
  title: 'Etkinlikler',
  description: 'Tebessüm Bin Mutluluk Derneği etkinlikleri — gönüllü olun, çocuklara mutluluk götürün.',
}

export const revalidate = 60

export default async function EventsPage() {
  const events = await sanityFetch<any[]>(EVENTS_QUERY).catch(() => [])

  const upcoming = events.filter(e => new Date(e.eventDate) >= new Date())
  const past     = events.filter(e => new Date(e.eventDate) < new Date())

  return (
    <div className="min-h-screen pt-24 pb-20">
      <div className="max-w-7xl mx-auto px-4 sm:px-6">

        {/* Header */}
        <div className="max-w-2xl mb-12">
          <div className="section-label flex items-center gap-2 mb-3">
            <Calendar size={12} /> Etkinlikler
          </div>
          <h1 className="font-display text-4xl sm:text-5xl font-light tracking-tight mb-4">
            Bir günün onlar için <em className="text-gold italic">her şeydir</em>
          </h1>
          <p className="text-muted text-lg leading-relaxed">
            Gönüllü olarak katıldığınız her etkinlikte bir çocuğun yüzü gülümsüyor.
            Yaklaşan etkinliklere kayıt olun, fark yaratın.
          </p>
        </div>

        {/* Kategori Filtreleri */}
        <div className="flex flex-wrap gap-2 mb-10">
          <button className="badge badge-gold">Tümü</button>
          {Object.entries(EVENT_CATEGORIES).map(([key, cat]) => (
            <button key={key} className="badge badge-muted hover:badge-gold transition-all cursor-pointer">
              {cat.emoji} {cat.label}
            </button>
          ))}
        </div>

        {/* Yaklaşan Etkinlikler */}
        {upcoming.length > 0 && (
          <section className="mb-16">
            <h2 className="font-display text-2xl font-semibold mb-6 flex items-center gap-3">
              <span className="w-2 h-2 rounded-full bg-sage animate-pulse" />
              Yaklaşan Etkinlikler
              <span className="badge badge-sage ml-1">{upcoming.length}</span>
            </h2>
            <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {upcoming.map(event => <EventCard key={event._id} event={event} />)}
            </div>
          </section>
        )}

        {/* Boş durum */}
        {upcoming.length === 0 && (
          <div className="text-center py-20 border border-border/40 rounded-2xl mb-16">
            <p className="text-4xl mb-4">📅</p>
            <h3 className="font-display text-xl font-semibold mb-2">Yaklaşan etkinlik yok</h3>
            <p className="text-muted text-sm">Yeni etkinlikler için bültene abone olun.</p>
          </div>
        )}

        {/* Geçmiş Etkinlikler */}
        {past.length > 0 && (
          <section>
            <h2 className="font-display text-xl font-semibold mb-6 text-muted">Geçmiş Etkinlikler</h2>
            <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
              {past.slice(0, 8).map(event => (
                <EventCard key={event._id} event={event} variant="compact" />
              ))}
            </div>
          </section>
        )}
      </div>
    </div>
  )
}
