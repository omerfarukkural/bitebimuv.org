import { sanityFetch } from '@/lib/sanity/client'
import { UPCOMING_EVENTS_QUERY, SITE_SETTINGS_QUERY } from '@/lib/sanity/queries'
import { EventCard } from '@/components/events/EventCard'
import Link from 'next/link'
import { Heart, Users, Calendar, ArrowRight } from 'lucide-react'

export const revalidate = 60

export default async function HomePage() {
  const [events, settings] = await Promise.all([
    sanityFetch<any[]>(UPCOMING_EVENTS_QUERY).catch(() => []),
    sanityFetch<any>(SITE_SETTINGS_QUERY).catch(() => null),
  ])

  const stats = settings?.impactStats ?? { childrenHelped: 1247, volunteers: 342, events: 89, cities: 18 }

  return (
    <main>
      {/* HERO */}
      <section className="min-h-screen flex flex-col items-center justify-center text-center px-4 pt-20 pb-10 relative overflow-hidden">
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(244,198,61,0.08),transparent)]" />
        <div className="relative z-10 max-w-3xl">
          <div className="section-label mb-6 flex items-center justify-center gap-2">
            <span className="w-1.5 h-1.5 rounded-full bg-sage animate-pulse" />
            Türkiye'nin Mutluluk Derneği
          </div>
          <h1 className="font-display text-5xl sm:text-6xl lg:text-7xl font-light tracking-tight leading-[1.05] mb-6">
            Bir tebessüm,<br/>
            <em className="italic text-gold">bin mutluluk.</em>
          </h1>
          <p className="text-muted text-lg sm:text-xl leading-relaxed max-w-lg mx-auto mb-10">
            Gönüllü olun veya bağış yapın. Her katkınız bir çocuğun yüzüne gülümseme getiriyor.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link href="/bagis" className="btn-primary text-base gap-2">
              <Heart size={17} className="fill-current" /> Bağış Yap
            </Link>
            <Link href="/gonullu" className="btn-secondary text-base gap-2">
              <Users size={17} /> Gönüllü Ol
            </Link>
          </div>
        </div>
      </section>

      {/* ETKİ SAYAÇLARI */}
      <section className="border-y border-border/60 bg-surface/40">
        <div className="max-w-5xl mx-auto px-4 grid grid-cols-2 sm:grid-cols-4">
          {[
            { num: stats.childrenHelped?.toLocaleString('tr'), label: 'Mutlu Çocuk', emoji: '😊' },
            { num: stats.volunteers?.toLocaleString('tr'),     label: 'Gönüllü',     emoji: '🤝' },
            { num: stats.events?.toLocaleString('tr'),         label: 'Etkinlik',    emoji: '🎉' },
            { num: stats.cities?.toLocaleString('tr'),         label: 'Şehir',       emoji: '📍' },
          ].map(s => (
            <div key={s.label} className="text-center py-8 px-4 border-r last:border-r-0 border-border/60">
              <p className="text-2xl mb-2">{s.emoji}</p>
              <p className="font-display text-3xl font-semibold text-gold">{s.num}</p>
              <p className="text-xs text-muted font-mono uppercase tracking-wider mt-1">{s.label}</p>
            </div>
          ))}
        </div>
      </section>

      {/* YAKLAŞAN ETKİNLİKLER */}
      {events.length > 0 && (
        <section className="py-20 px-4">
          <div className="max-w-7xl mx-auto">
            <div className="flex items-end justify-between mb-10">
              <div>
                <div className="section-label mb-2 flex items-center gap-2">
                  <Calendar size={11} /> Yaklaşan Etkinlikler
                </div>
                <h2 className="font-display text-3xl sm:text-4xl font-light tracking-tight">
                  Yakında <em className="italic text-gold">sizi bekliyoruz</em>
                </h2>
              </div>
              <Link href="/etkinlikler" className="btn-ghost gap-1.5 hidden sm:flex">
                Tümünü Gör <ArrowRight size={15} />
              </Link>
            </div>
            <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {events.slice(0, 3).map(event => (
                <EventCard key={event._id} event={event} variant="featured" />
              ))}
            </div>
            <div className="mt-6 text-center sm:hidden">
              <Link href="/etkinlikler" className="btn-secondary gap-1.5">
                Tüm Etkinlikler <ArrowRight size={15} />
              </Link>
            </div>
          </div>
        </section>
      )}

      {/* BAĞIŞ CTA */}
      <section className="py-20 px-4">
        <div className="max-w-3xl mx-auto text-center">
          <div className="bg-surface border border-gold/20 rounded-3xl p-10 gold-glow">
            <p className="text-5xl mb-6">☺</p>
            <h2 className="font-display text-3xl sm:text-4xl font-light tracking-tight mb-4">
              Her bağış bir çocuğun <em className="italic text-gold">gülüşüdür</em>
            </h2>
            <p className="text-muted text-base leading-relaxed mb-8 max-w-md mx-auto">
              50₺ = 1 çocuğun öğle yemeği. 200₺ = 1 çocuğun kırtasiyesi. Her miktar fark yaratır.
            </p>
            <Link href="/bagis" className="btn-primary text-lg gap-3 px-8 py-4">
              <Heart size={20} className="fill-current" /> Şimdi Bağış Yap
            </Link>
          </div>
        </div>
      </section>
    </main>
  )
}
