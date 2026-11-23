import Link from 'next/link'
import Image from 'next/image'
import { MapPin, Users, Calendar } from 'lucide-react'
import { EventCountdown } from './EventCountdown'
import { formatDateTime, EVENT_CATEGORIES, cn } from '@/lib/utils'

interface EventCardProps {
  event: {
    _id: string
    title: string
    slug: { current: string }
    category: string
    shortDescription?: string
    image?: string
    eventDate: string
    location?: string
    maxParticipants?: number
    isRegistrationOpen: boolean
    isFeatured?: boolean
  }
  variant?: 'default' | 'featured' | 'compact'
}

export function EventCard({ event, variant = 'default' }: EventCardProps) {
  const cat = EVENT_CATEGORIES[event.category] ?? { label: event.category, emoji: '📅', color: 'badge-muted' }
  const isPast = new Date(event.eventDate) < new Date()

  if (variant === 'compact') {
    return (
      <Link href={`/etkinlikler/${event.slug.current}`}
        className="flex items-center gap-4 p-4 rounded-xl bg-surface border border-border/60 hover:border-gold/30 transition-all group">
        <div className="text-2xl">{cat.emoji}</div>
        <div className="flex-1 min-w-0">
          <p className="font-semibold text-sm truncate group-hover:text-gold transition-colors">{event.title}</p>
          <p className="text-xs text-muted mt-0.5">{formatDateTime(event.eventDate)}</p>
        </div>
        {!isPast && <EventCountdown eventDate={event.eventDate} size="sm" />}
      </Link>
    )
  }

  if (variant === 'featured') {
    return (
      <Link href={`/etkinlikler/${event.slug.current}`}
        className="group relative rounded-2xl overflow-hidden border border-border/60 hover:border-gold/40 transition-all block">
        {event.image && (
          <div className="relative h-64 overflow-hidden">
            <Image src={event.image} alt={event.title} fill className="object-cover transition-transform duration-500 group-hover:scale-105" />
            <div className="absolute inset-0 bg-gradient-to-t from-ground/95 via-ground/40 to-transparent" />
          </div>
        )}
        <div className="p-6 relative">
          <div className="flex items-center gap-2 mb-3">
            <span className={cn('badge', cat.color)}>{cat.emoji} {cat.label}</span>
            {event.isFeatured && <span className="badge badge-gold">⭐ Öne Çıkan</span>}
          </div>
          <h3 className="font-display text-xl font-semibold mb-2 group-hover:text-gold transition-colors">{event.title}</h3>
          {event.shortDescription && <p className="text-muted text-sm line-clamp-2 mb-4">{event.shortDescription}</p>}
          <div className="flex items-center justify-between">
            <div className="space-y-1.5">
              <p className="flex items-center gap-1.5 text-xs text-muted">
                <Calendar size={12} className="text-gold" />
                {formatDateTime(event.eventDate)}
              </p>
              {event.location && (
                <p className="flex items-center gap-1.5 text-xs text-muted">
                  <MapPin size={12} className="text-gold" />
                  {event.location}
                </p>
              )}
            </div>
            {!isPast && <EventCountdown eventDate={event.eventDate} size="sm" />}
          </div>
          {event.isRegistrationOpen && !isPast && (
            <div className="mt-4 btn-primary text-sm text-center w-full">Kayıt Ol →</div>
          )}
        </div>
      </Link>
    )
  }

  return (
    <Link href={`/etkinlikler/${event.slug.current}`}
      className="group card hover:gold-glow block">
      {event.image && (
        <div className="relative -mx-6 -mt-6 h-48 mb-5 rounded-t-xl overflow-hidden">
          <Image src={event.image} alt={event.title} fill className="object-cover transition-transform duration-500 group-hover:scale-105" />
          <div className="absolute inset-0 bg-gradient-to-t from-surface/90 to-transparent" />
          <div className="absolute bottom-3 left-4">
            <span className={cn('badge', cat.color)}>{cat.emoji} {cat.label}</span>
          </div>
        </div>
      )}
      <h3 className="font-semibold text-base mb-2 group-hover:text-gold transition-colors line-clamp-2">{event.title}</h3>
      {event.shortDescription && <p className="text-muted text-sm line-clamp-2 mb-4">{event.shortDescription}</p>}
      <div className="space-y-1.5 mb-4">
        <p className="flex items-center gap-2 text-xs text-muted">
          <Calendar size={12} className="text-gold flex-shrink-0" />
          {formatDateTime(event.eventDate)}
        </p>
        {event.location && (
          <p className="flex items-center gap-2 text-xs text-muted">
            <MapPin size={12} className="text-gold flex-shrink-0" />
            {event.location}
          </p>
        )}
        {event.maxParticipants && (
          <p className="flex items-center gap-2 text-xs text-muted">
            <Users size={12} className="text-gold flex-shrink-0" />
            Maks. {event.maxParticipants} katılımcı
          </p>
        )}
      </div>
      {!isPast && <EventCountdown eventDate={event.eventDate} size="sm" className="mb-4" />}
      {event.isRegistrationOpen && !isPast
        ? <div className="btn-primary text-sm text-center w-full">Hemen Kayıt Ol →</div>
        : <div className="btn-secondary text-sm text-center w-full pointer-events-none opacity-60">
            {isPast ? 'Etkinlik Tamamlandı' : 'Kayıt Kapalı'}
          </div>
      }
    </Link>
  )
}
