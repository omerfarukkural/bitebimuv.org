'use client'

import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import toast from 'react-hot-toast'
import { Loader2, CheckCircle, User, Mail, Phone, MapPin, MessageSquare } from 'lucide-react'
import { cn } from '@/lib/utils'

const schema = z.object({
  name:         z.string().min(2, 'Ad Soyad en az 2 karakter olmalıdır'),
  email:        z.string().email('Geçerli bir e-posta adresi girin'),
  phone:        z.string().min(10, 'Geçerli bir telefon numarası girin').optional().or(z.literal('')),
  age:          z.coerce.number().min(15, 'En az 15 yaşında olmalısınız').max(100).optional(),
  city:         z.string().min(2, 'Şehir giriniz').optional().or(z.literal('')),
  is_volunteer: z.boolean().default(false),
  message:      z.string().max(500).optional().or(z.literal('')),
  kvkk:         z.boolean().refine(v => v === true, 'KVKK metnini onaylamanız gerekiyor'),
})

type FormData = z.infer<typeof schema>

interface Props {
  eventId: string
  eventTitle: string
  eventDate: string
  maxParticipants?: number
  currentCount?: number
  requiresApproval?: boolean
  onSuccess?: () => void
}

export function EventRegistrationForm({
  eventId, eventTitle, eventDate, maxParticipants, currentCount = 0, requiresApproval, onSuccess,
}: Props) {
  const [submitted, setSubmitted] = useState(false)
  const spotsLeft = maxParticipants ? maxParticipants - currentCount : null

  const { register, handleSubmit, watch, formState: { errors, isSubmitting } } = useForm<FormData>({
    resolver: zodResolver(schema),
  })

  const isVolunteer = watch('is_volunteer')

  const onSubmit = async (data: FormData) => {
    try {
      const res = await fetch('/api/events/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...data, event_id: eventId, event_title: eventTitle, event_date: eventDate }),
      })
      const json = await res.json()
      if (!res.ok) throw new Error(json.error ?? 'Kayıt sırasında hata oluştu')
      setSubmitted(true)
      toast.success(requiresApproval ? 'Başvurunuz alındı — onay sonrası bilgilendirileceksiniz.' : 'Kayıt başarılı! Görüşmek üzere 🎉')
      onSuccess?.()
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : 'Bir hata oluştu, tekrar deneyin.')
    }
  }

  if (submitted) {
    return (
      <div className="text-center py-10 px-6">
        <div className="w-16 h-16 rounded-full bg-sage/10 border border-sage/30 flex items-center justify-center mx-auto mb-5">
          <CheckCircle className="text-sage" size={32} />
        </div>
        <h3 className="font-display text-xl font-semibold mb-2">
          {requiresApproval ? 'Başvurunuz Alındı!' : 'Kayıt Tamamlandı!'}
        </h3>
        <p className="text-muted text-sm max-w-sm mx-auto">
          {requiresApproval
            ? 'Başvurunuzu inceleyeceğiz ve e-posta ile bilgilendireceğiz.'
            : `"${eventTitle}" etkinliğine kaydoldunuz. Hatırlatma e-postası gönderildi.`}
        </p>
      </div>
    )
  }

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      {spotsLeft !== null && spotsLeft <= 10 && spotsLeft > 0 && (
        <div className="bg-ember/10 border border-ember/30 rounded-lg px-4 py-2.5 text-sm text-ember flex items-center gap-2">
          <span>⚡</span> Son <strong>{spotsLeft}</strong> yer kaldı!
        </div>
      )}

      {/* Ad Soyad */}
      <div>
        <label className="label">Ad Soyad *</label>
        <div className="relative">
          <User size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-muted" />
          <input {...register('name')} placeholder="Adınız Soyadınız"
            className={cn('input pl-9', errors.name && 'border-ember/60 focus:border-ember/60')} />
        </div>
        {errors.name && <p className="text-xs text-ember mt-1">{errors.name.message}</p>}
      </div>

      {/* E-posta */}
      <div>
        <label className="label">E-posta *</label>
        <div className="relative">
          <Mail size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-muted" />
          <input {...register('email')} type="email" placeholder="ornek@email.com"
            className={cn('input pl-9', errors.email && 'border-ember/60')} />
        </div>
        {errors.email && <p className="text-xs text-ember mt-1">{errors.email.message}</p>}
      </div>

      {/* Telefon + Şehir */}
      <div className="grid grid-cols-2 gap-3">
        <div>
          <label className="label">Telefon</label>
          <div className="relative">
            <Phone size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-muted" />
            <input {...register('phone')} placeholder="05xx xxx xx xx"
              className="input pl-9" />
          </div>
          {errors.phone && <p className="text-xs text-ember mt-1">{errors.phone.message}</p>}
        </div>
        <div>
          <label className="label">Şehir</label>
          <div className="relative">
            <MapPin size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-muted" />
            <input {...register('city')} placeholder="İstanbul"
              className="input pl-9" />
          </div>
        </div>
      </div>

      {/* Yaş */}
      <div>
        <label className="label">Yaş</label>
        <input {...register('age')} type="number" min={15} max={100} placeholder="25"
          className="input w-24" />
        {errors.age && <p className="text-xs text-ember mt-1">{errors.age.message}</p>}
      </div>

      {/* Gönüllü olmak istiyorum */}
      <label className="flex items-start gap-3 p-4 bg-surface2 border border-border/60 rounded-xl cursor-pointer hover:border-gold/30 transition-all group">
        <input {...register('is_volunteer')} type="checkbox"
          className="w-4 h-4 mt-0.5 accent-gold rounded" />
        <div>
          <p className="text-sm font-medium group-hover:text-gold transition-colors">Gönüllü olarak katılmak istiyorum</p>
          <p className="text-xs text-muted mt-0.5">Etkinlikte aktif görev almak istiyorum</p>
        </div>
      </label>

      {/* Mesaj */}
      <div>
        <label className="label">Mesajınız (opsiyonel)</label>
        <div className="relative">
          <MessageSquare size={15} className="absolute left-3 top-3 text-muted" />
          <textarea {...register('message')} rows={3} placeholder="Sormak istediğiniz bir şey var mı?"
            className="input pl-9 resize-none" />
        </div>
        {errors.message && <p className="text-xs text-ember mt-1">{errors.message.message}</p>}
      </div>

      {/* KVKK */}
      <label className="flex items-start gap-3 cursor-pointer">
        <input {...register('kvkk')} type="checkbox"
          className="w-4 h-4 mt-0.5 accent-gold rounded flex-shrink-0" />
        <p className="text-xs text-muted leading-relaxed">
          <a href="/kvkk" target="_blank" className="text-gold hover:underline">KVKK Aydınlatma Metni</a>'ni
          okudum ve kişisel verilerimin işlenmesine onay veriyorum. *
        </p>
      </label>
      {errors.kvkk && <p className="text-xs text-ember -mt-2">{errors.kvkk.message}</p>}

      <button type="submit" disabled={isSubmitting || (spotsLeft !== null && spotsLeft <= 0)}
        className={cn('btn-primary w-full justify-center text-base',
          (spotsLeft !== null && spotsLeft <= 0) && 'opacity-50 cursor-not-allowed')}>
        {isSubmitting ? <><Loader2 size={16} className="animate-spin" /> Kaydediliyor...</>
          : spotsLeft !== null && spotsLeft <= 0 ? 'Kontenjan Doldu'
          : requiresApproval ? 'Başvur →'
          : 'Hemen Kayıt Ol →'}
      </button>

      {requiresApproval && (
        <p className="text-xs text-muted text-center">
          Bu etkinlik için başvurunuz değerlendirilerek onaylanacaktır.
        </p>
      )}
    </form>
  )
}
