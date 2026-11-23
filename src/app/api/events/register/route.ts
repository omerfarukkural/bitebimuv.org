import { NextRequest, NextResponse } from 'next/server'
import { createAdminClient } from '@/lib/supabase/server'

export async function POST(req: NextRequest) {
  try {
    const body = await req.json()
    const { event_id, event_title, event_date, name, email, phone, age, city, is_volunteer, message } = body

    if (!event_id || !name || !email) {
      return NextResponse.json({ error: 'Zorunlu alanlar eksik' }, { status: 400 })
    }

    const supabase = await createAdminClient()

    // Daha önce kayıt olmuş mu kontrol et
    const { data: existing } = await supabase
      .from('event_registrations')
      .select('id')
      .eq('event_id', event_id)
      .eq('email', email.toLowerCase())
      .maybeSingle()

    if (existing) {
      return NextResponse.json({ error: 'Bu e-posta ile zaten kayıt olunmuş' }, { status: 409 })
    }

    const { error } = await (supabase.from('event_registrations') as any).insert({
      event_id,
      event_title,
      event_date,
      name,
      email:        email.toLowerCase(),
      phone:        phone || null,
      age:          age || null,
      city:         city || null,
      is_volunteer: is_volunteer ?? false,
      message:      message || null,
      status:       'pending',
    })

    if (error) throw error

    // E-posta gönder (arka planda, hata dönmesini engelleme)
    try {
      await sendConfirmationEmail({ name, email, event_title, event_date })
    } catch (emailErr) {
      console.error('Confirmation email failed:', emailErr)
    }

    return NextResponse.json({ success: true })
  } catch (err: unknown) {
    console.error('Registration error:', err)
    return NextResponse.json({ error: 'Kayıt sırasında hata oluştu' }, { status: 500 })
  }
}

async function sendConfirmationEmail({ name, email, event_title, event_date }: {
  name: string; email: string; event_title: string; event_date: string
}) {
  if (!process.env.RESEND_API_KEY) return

  const { Resend } = await import('resend')
  const resend = new Resend(process.env.RESEND_API_KEY)

  const dateStr = new Date(event_date).toLocaleDateString('tr-TR', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit',
  })

  await resend.emails.send({
    from:    `Tebessüm Bin Mutluluk <${process.env.RESEND_FROM_EMAIL}>`,
    to:      email,
    subject: `✅ "${event_title}" etkinliğine kaydoldunuz!`,
    html: `
      <div style="font-family:sans-serif;max-width:520px;margin:0 auto;background:#0D1526;color:#EEE8DE;padding:32px;border-radius:12px">
        <h2 style="color:#F4C63D;margin:0 0 16px">Merhaba ${name}! 👋</h2>
        <p style="color:#8A9BB8;margin:0 0 24px">"<strong style="color:#EEE8DE">${event_title}</strong>" etkinliğine başarıyla kayıt oldunuz.</p>
        <div style="background:#16233F;border:1px solid rgba(244,198,61,0.2);border-radius:8px;padding:16px;margin:0 0 24px">
          <p style="margin:0;font-size:14px">📅 <strong>${dateStr}</strong></p>
        </div>
        <p style="color:#8A9BB8;font-size:13px">Etkinlik öncesinde hatırlatma e-postası alacaksınız.</p>
        <a href="https://www.bitebimuv.org/etkinlikler" style="display:inline-block;margin-top:24px;background:#F4C63D;color:#0D1526;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px">
          Tüm Etkinlikleri Gör →
        </a>
      </div>
    `,
  })
}
