'use client'

import { useEffect, useState } from 'react'
import { cn } from '@/lib/utils'

interface TimeLeft { days: number; hours: number; minutes: number; seconds: number }

function getTimeLeft(eventDate: string): TimeLeft | null {
  const diff = new Date(eventDate).getTime() - Date.now()
  if (diff <= 0) return null
  return {
    days:    Math.floor(diff / (1000 * 60 * 60 * 24)),
    hours:   Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
    minutes: Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)),
    seconds: Math.floor((diff % (1000 * 60)) / 1000),
  }
}

function Flip({ value, label }: { value: number; label: string }) {
  const [prev, setPrev] = useState(value)
  const [flipping, setFlipping] = useState(false)

  useEffect(() => {
    if (value !== prev) {
      setFlipping(true)
      const t = setTimeout(() => { setPrev(value); setFlipping(false) }, 400)
      return () => clearTimeout(t)
    }
  }, [value, prev])

  return (
    <div className="flex flex-col items-center gap-1.5">
      <div className={cn(
        'relative w-16 h-16 bg-surface2 rounded-xl border border-border/60 flex items-center justify-center overflow-hidden',
        'shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]'
      )}>
        {/* Top half line */}
        <div className="absolute inset-x-0 top-1/2 h-px bg-ground/60 z-10" />
        <span className={cn(
          'font-display text-3xl font-semibold text-gold tabular-nums',
          flipping && 'countdown-flip'
        )}>
          {String(value).padStart(2, '0')}
        </span>
      </div>
      <span className="font-mono text-[9px] tracking-[0.15em] uppercase text-muted">{label}</span>
    </div>
  )
}

interface Props {
  eventDate: string
  size?: 'sm' | 'md' | 'lg'
  className?: string
}

export function EventCountdown({ eventDate, size = 'md', className }: Props) {
  const [timeLeft, setTimeLeft] = useState<TimeLeft | null>(getTimeLeft(eventDate))

  useEffect(() => {
    const id = setInterval(() => setTimeLeft(getTimeLeft(eventDate)), 1000)
    return () => clearInterval(id)
  }, [eventDate])

  if (!timeLeft) {
    return (
      <div className={cn('inline-flex items-center gap-2 text-sm font-medium', className)}>
        <span className="w-2 h-2 rounded-full bg-sage animate-pulse" />
        <span className="text-sage">Etkinlik başladı!</span>
      </div>
    )
  }

  if (size === 'sm') {
    return (
      <div className={cn('flex items-center gap-1.5 font-mono text-sm text-muted', className)}>
        <span className="text-gold font-semibold">{timeLeft.days}g</span>
        <span>{String(timeLeft.hours).padStart(2,'0')}:</span>
        <span>{String(timeLeft.minutes).padStart(2,'0')}:</span>
        <span>{String(timeLeft.seconds).padStart(2,'0')}</span>
      </div>
    )
  }

  return (
    <div className={cn('', className)}>
      {timeLeft.days > 0 && (
        <p className="font-mono text-[10px] tracking-widest uppercase text-muted mb-3 text-center">
          Etkinliğe kalan süre
        </p>
      )}
      <div className="flex items-start gap-2">
        {timeLeft.days > 0 && <Flip value={timeLeft.days} label="Gün" />}
        <Flip value={timeLeft.hours} label="Saat" />
        <Flip value={timeLeft.minutes} label="Dak" />
        <Flip value={timeLeft.seconds} label="San" />
      </div>
    </div>
  )
}
