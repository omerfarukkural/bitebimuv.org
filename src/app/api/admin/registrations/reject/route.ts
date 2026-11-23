import { NextRequest, NextResponse } from 'next/server'
import { createAdminClient } from '@/lib/supabase/server'

export async function POST(req: NextRequest) {
  const data = await req.formData()
  const id = data.get('id') as string
  const supabase = await createAdminClient()
  await (supabase.from('event_registrations') as any).update({ status: 'rejected' }).eq('id', id)
  return NextResponse.redirect(new URL('/admin/etkinlikler', req.url))
}
