import { type ClassValue, clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function formatDate(date: string | Date, opts?: Intl.DateTimeFormatOptions) {
  return new Date(date).toLocaleDateString('tr-TR', {
    day: 'numeric', month: 'long', year: 'numeric', ...opts,
  })
}

export function formatDateTime(date: string | Date) {
  return new Date(date).toLocaleDateString('tr-TR', {
    day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit',
  })
}

export function formatCurrency(amount: number, currency = 'TRY') {
  return new Intl.NumberFormat('tr-TR', { style: 'currency', currency }).format(amount)
}

export function slugify(text: string) {
  return text.toLowerCase()
    .replace(/ğ/g,'g').replace(/ü/g,'u').replace(/ş/g,'s')
    .replace(/ı/g,'i').replace(/ö/g,'o').replace(/ç/g,'c')
    .replace(/[^a-z0-9]/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'')
}

export const EVENT_CATEGORIES: Record<string, { label: string; color: string; emoji: string }> = {
  hasta_ziyareti: { label: 'Hasta Çocuk Ziyareti', color: 'badge-ember',  emoji: '🏥' },
  egitim:         { label: 'Eğitim',               color: 'badge-gold',   emoji: '📚' },
  kultur_sanat:   { label: 'Kültür & Sanat',        color: 'badge-muted',  emoji: '🎭' },
  spor:           { label: 'Spor',                  color: 'badge-sage',   emoji: '⚽' },
  cevre:          { label: 'Çevre',                 color: 'badge-sage',   emoji: '🌱' },
  toplanti:       { label: 'Toplantı',              color: 'badge-muted',  emoji: '🤝' },
  kurumsal:       { label: 'Kurumsal',              color: 'badge-gold',   emoji: '💼' },
}
