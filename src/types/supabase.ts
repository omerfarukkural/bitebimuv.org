// Auto-generate with: npx supabase gen types typescript --project-id YOUR_PROJECT_ID
// For now, using a basic type definition
export type Database = {
  public: {
    Tables: {
      event_registrations: { Row: EventRegistration; Insert: Partial<EventRegistration>; Update: Partial<EventRegistration> }
      volunteers: { Row: Volunteer; Insert: Partial<Volunteer>; Update: Partial<Volunteer> }
      volunteer_applications: { Row: VolunteerApplication; Insert: Partial<VolunteerApplication>; Update: Partial<VolunteerApplication> }
      donations: { Row: Donation; Insert: Partial<Donation>; Update: Partial<Donation> }
      subscribers: { Row: Subscriber; Insert: Partial<Subscriber>; Update: Partial<Subscriber> }
      contact_messages: { Row: ContactMessage; Insert: Partial<ContactMessage>; Update: Partial<ContactMessage> }
      admin_roles: { Row: AdminRole; Insert: Partial<AdminRole>; Update: Partial<AdminRole> }
      honor_wall: { Row: HonorWallEntry; Insert: Partial<HonorWallEntry>; Update: Partial<HonorWallEntry> }
    }
    Views: Record<string, never>
    Functions: Record<string, never>
    Enums: Record<string, never>
  }
}

export interface EventRegistration {
  id: string
  event_id: string
  event_title: string
  event_date: string | null
  name: string
  email: string
  phone: string | null
  age: number | null
  city: string | null
  message: string | null
  is_volunteer: boolean
  status: 'pending' | 'approved' | 'rejected' | 'cancelled' | 'attended'
  registered_at: string
  notes: string | null
}

export interface Volunteer {
  id: string
  user_id: string | null
  name: string
  email: string
  phone: string | null
  city: string | null
  birth_year: number | null
  skills: string[] | null
  availability: string | null
  motivation: string | null
  status: 'pending' | 'active' | 'inactive' | 'banned'
  badge_level: 'beginner' | 'helper' | 'champion' | 'legend'
  total_hours: number
  total_events: number
  joined_at: string
  approved_at: string | null
  avatar_url: string | null
}

export interface VolunteerApplication {
  id: string
  name: string
  email: string
  phone: string | null
  city: string | null
  birth_year: number | null
  occupation: string | null
  skills: string[] | null
  availability: string | null
  motivation: string
  how_heard: string | null
  status: 'pending' | 'approved' | 'rejected' | 'waitlist'
  applied_at: string
  reviewed_at: string | null
  reviewer_note: string | null
}

export interface Donation {
  id: string
  amount: number
  currency: string
  donor_name: string | null
  donor_email: string | null
  donor_phone: string | null
  donation_type: 'one_time' | 'monthly' | 'corporate' | 'memorial' | 'gift'
  payment_provider: 'iyzico' | 'stripe' | 'manual' | null
  payment_status: 'pending' | 'completed' | 'failed' | 'refunded'
  payment_id: string | null
  is_anonymous: boolean
  message: string | null
  project_id: string | null
  in_memory_of: string | null
  created_at: string
}

export interface Subscriber {
  id: string
  email: string
  name: string | null
  status: 'active' | 'unsubscribed'
  source: string
  created_at: string
}

export interface ContactMessage {
  id: string
  name: string
  email: string
  phone: string | null
  subject: string | null
  message: string
  is_read: boolean
  replied_at: string | null
  created_at: string
}

export interface AdminRole {
  user_id: string
  role: 'superadmin' | 'admin' | 'editor' | 'volunteer_manager'
  created_at: string
}

export interface HonorWallEntry {
  id: string
  display_name: string
  amount: number | null
  message: string | null
  donation_id: string | null
  is_visible: boolean
  created_at: string
}
