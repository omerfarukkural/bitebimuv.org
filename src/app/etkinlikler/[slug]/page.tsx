import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import Image from 'next/image'
import Link from 'next/link'
import { MapPin, Calendar, Users, Clock, ArrowLeft, ExternalLink } from 'lucide-react'
import { sanityFetch } from '@/lib/sanity/client'
import { EVENT_BY_SLUG_QUERY } from '@/lib/sanity/queries'
import { EventCountdown } from '@/components/events/EventCountdown'
import { EventRegistrationForm } from '@/components/events/EventRegistrationForm'
import { createClient } from '@/lib/supabase/server'
import { formatDateTime, EVENT_CATEGORIES, cn } from '@/lib/utils'
import { PortableText } from '@portabletext/react'

export const revalidate = 60

export async function generateMetadata({ params }: { params: { slug: string } }): Promise<Metadata> {
  const event = await sanityFetch<any>(EVENT_BY_SLUG_QUERY, { slug: params.slug }).catch(() => null)
  if (!event) return { title: 'Etkinlik Bulunamadı' }
  return {
    title: event.title,
    description: event.shortDescription ?? `${event.title} etkinliğine katılın.`,
    openGraph: { images: event.image ? [{ url: event.image }] : [] },
  }
}

export default async function EventDetailPage({ params }: { params: { slug: string } }) {
  const [event, supabase] = await Promise.all([
    sanityFetch<any>(EVENT_BY_SLUG_QUERY, { slug: params.slug }).catch(() => null),
    createClient(),
  ])

  if (!event) notFound()

  const { count } = await supabase
    .from('event_registrations')
    .select('*', { count: 'exact', head: true })
    .eq('event_id', event._id)
    .neq('status', 'cancelled')

  const currentCount = count ?? 0
  const cat = EVENT_CATEGORIES[event.category] ?? { label: event.category, emoji: '📅', color: 'badge-muted' }
  const isPast = new Date(event.eventDate) < new Date()
  const spotsLeft = event.maxParticipants ? event.maxParticipants - currentCount : null
  const isFull = spotsLeft !== null && spotsLeft <= 0

  return (
    <div className="min-h-screen pt-24 pb-20">
      <div className="max-w-7xl mx-auto px-4 sm:px-6">

        <Link href="/etkinlikler" className="inline-flex items-center gap-2 text-sm text-muted hover:text-ink mb-8 transition-colors">
          <ArrowLeft size={14} /> Tüm Etkinlikler
        </Link>

        <div className="grid lg:grid-cols-3 gap-10">

          {/* Sol: Detay */}
          <div className="lg:col-span-2">
            {event.image && (
              <div className="relative h-72 sm:h-96 rounded-2xl overflow-hidden mb-8">
                <Image src={event.image} alt={event.title} fill className="object-cover" />
                <div className="absolute inset-0 bg-gradient-to-t from-ground/80 to-transparent" />
                {event.isFeatured && (
                  <div className="absolute top-4 left-4">
                    <span className="badge badge-gold">⭐ Öne Çıkan Etkinlik</span>
                  </div>
                )}
              </div>
            )}

            <div className="flex flex-wrap gap-2 mb-4">
              <span className={cn('badge', cat.color)}>{cat.emoji} {cat.label}</span>
              {isPast && <span className="badge badge-muted">Tamamlandı</span>}
              {isFull && !isPast && <span className="badge badge-ember">Kontenjan Doldu</span>}
            </div>

            <h1 className="font-display text-3xl sm:text-4xl font-semibold tracking-tight mb-6">{event.title}</h1>

            {/* Meta bilgiler */}
            <div className="grid sm:grid-cols-2 gap-4 mb-8">
              <div className="flex items-center gap-3 p-4 bg-surface rounded-xl border border-border/60">
                <Calendar className="text-gold flex-shrink-0" size={20} />
                <div>
                  <p className="text-xs text-muted mb-0.5">Tarih & Saat</p>
                  <p className="text-sm font-medium">{formatDateTime(event.eventDate)}</p>
                </div>
              </div>
              {event.location && (
                <div className="flex items-center gap-3 p-4 bg-surface rounded-xl border border-border/60">
                  <MapPin className="text-gold flex-shrink-0" size={20} />
                  <div className="flex-1 min-w-0">
                    <p className="text-xs text-muted mb-0.5">Konum</p>
                    <p className="text-sm font-medium">{event.location}</p>
                  </div>
                  {event.locationUrl && (
                    <a href={event.locationUrl} target="_blank" rel="noreferrer"
                      className="text-muted hover:text-gold transition-colors">
                      <ExternalLink size={14} />
                    </a>
                  )}
                </div>
              )}
              {event.maxParticipants && (
                <div className="flex items-center gap-3 p-4 bg-surface rounded-xl border border-border/60">
                  <Users className="text-gold flex-shrink-0" size={20} />
                  <div>
                    <p className="text-xs text-muted mb-0.5">Katılımcı</p>
                    <p className="text-sm font-medium">
                      {currentCount}/{event.maxParticipants}
                      {spotsLeft !== null && spotsLeft <= 5 && spotsLeft > 0 && (
                        <span className="text-ember ml-2">({spotsLeft} yer kaldı!)</span>
                      )}
                    </p>
                  </div>
                </div>
              )}
              {event.volunteerNeeded && (
                <div className="flex items-center gap-3 p-4 bg-surface rounded-xl border border-border/60">
                  <span className="text-xl flex-shrink-0">🤝</span>
                  <div>
                    <p className="text-xs text-muted mb-0.5">Gönüllü İhtiyacı</p>
                    <p className="text-sm font-medium">{event.volunteerNeeded} gönüllü</p>
                  </div>
                </div>
              )}
            </div>

            {/* Geri sayım */}
            {!isPast && (
              <div className="mb-8 p-6 bg-surface rounded-2xl border border-gold/20 gold-glow">
                <p className="section-label mb-4">Etkinliğe Kalan Süre</p>
                <EventCountdown eventDate={event.eventDate} size="lg" />
              </div>
            )}

            {/* Açıklama */}
            {event.description && (
              <div className="prose prose-invert prose-gold max-w-none">
                <PortableText value={event.description} />
              </div>
            )}
          </div>

          {/* Sağ: Kayıt Formu */}
          <div className="lg:col-span-1">
            <div className="sticky top-24">
              <div className="bg-surface border border-border/60 rounded-2xl p-6">
                {isPast ? (
                  <div className="text-center py-6">
                    <p className="text-3xl mb-3">✅</p>
                    <h3 className="font-display font-semibold mb-2">Etkinlik Tamamlandı</h3>
                    <p className="text-sm text-muted">Bu etkinlik sona erdi. Diğer etkinliklere göz atın.</p>
                    <Link href="/etkinlikler" className="btn-primary mt-4 text-sm text-center block">
                      Diğer Etkinlikler →
                    </Link>
                  </div>
                ) : isFull ? (
                  <div className="text-center py-6">
                    <p className="text-3xl mb-3">⚡</p>
                    <h3 className="font-display font-semibold mb-2">Kontenjan Doldu</h3>
                    <p className="text-sm text-muted">Bu etkinlik için kontenjan tükendi.</p>
                  </div>
                ) : !event.isRegistrationOpen ? (
                  <div className="text-center py-6">
                    <p className="text-3xl mb-3">🔒</p>
                    <h3 className="font-display font-semibold mb-2">Kayıt Kapalı</h3>
                    <p className="text-sm text-muted">Bu etkinlik için kayıt şu an kapalı.</p>
                  </div>
                ) : (
                  <>
                    <h2 className="font-display text-xl font-semibold mb-1">
                      {event.requiresApproval ? 'Başvur' : 'Kayıt Ol'}
                    </h2>
                    <p className="text-sm text-muted mb-6">
                      {event.requiresApproval ? 'Başvurunuz değerlendirilecek.' : 'Ücretsiz — hemen kayıt olun.'}
                    </p>
                    <EventRegistrationForm
                      eventId={event._id}
                      eventTitle={event.title}
                      eventDate={event.eventDate}
                      maxParticipants={event.maxParticipants}
                      currentCount={currentCount}
                      requiresApproval={event.requiresApproval}
                    />
                  </>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
