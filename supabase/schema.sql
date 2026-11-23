-- =============================================
-- BITEBIMUV.ORG — Supabase Veritabanı Şeması
-- =============================================

-- Etkinlik Kayıtları
CREATE TABLE IF NOT EXISTS event_registrations (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  event_id      TEXT NOT NULL,              -- Sanity _id
  event_title   TEXT NOT NULL,
  event_date    TIMESTAMPTZ,
  name          TEXT NOT NULL,
  email         TEXT NOT NULL,
  phone         TEXT,
  age           INT,
  city          TEXT,
  message       TEXT,
  is_volunteer  BOOLEAN DEFAULT false,
  status        TEXT DEFAULT 'pending'
                CHECK (status IN ('pending','approved','rejected','cancelled','attended')),
  registered_at TIMESTAMPTZ DEFAULT NOW(),
  notes         TEXT                        -- admin notları
);

-- Gönüllüler
CREATE TABLE IF NOT EXISTS volunteers (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  user_id       UUID REFERENCES auth.users(id) ON DELETE SET NULL,
  name          TEXT NOT NULL,
  email         TEXT UNIQUE NOT NULL,
  phone         TEXT,
  city          TEXT,
  birth_year    INT,
  skills        TEXT[],
  availability  TEXT,
  motivation    TEXT,
  status        TEXT DEFAULT 'pending'
                CHECK (status IN ('pending','active','inactive','banned')),
  badge_level   TEXT DEFAULT 'beginner'
                CHECK (badge_level IN ('beginner','helper','champion','legend')),
  total_hours   INT DEFAULT 0,
  total_events  INT DEFAULT 0,
  joined_at     TIMESTAMPTZ DEFAULT NOW(),
  approved_at   TIMESTAMPTZ,
  avatar_url    TEXT
);

-- Gönüllü Rozetleri
CREATE TABLE IF NOT EXISTS volunteer_badges (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  volunteer_id  UUID REFERENCES volunteers(id) ON DELETE CASCADE,
  badge_type    TEXT NOT NULL,
  badge_name    TEXT NOT NULL,
  earned_at     TIMESTAMPTZ DEFAULT NOW()
);

-- Bağışlar
CREATE TABLE IF NOT EXISTS donations (
  id                UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  amount            DECIMAL(10,2) NOT NULL,
  currency          TEXT DEFAULT 'TRY',
  donor_name        TEXT,
  donor_email       TEXT,
  donor_phone       TEXT,
  donation_type     TEXT DEFAULT 'one_time'
                    CHECK (donation_type IN ('one_time','monthly','corporate','memorial','gift')),
  payment_provider  TEXT CHECK (payment_provider IN ('iyzico','stripe','manual')),
  payment_status    TEXT DEFAULT 'pending'
                    CHECK (payment_status IN ('pending','completed','failed','refunded')),
  payment_id        TEXT,
  is_anonymous      BOOLEAN DEFAULT false,
  message           TEXT,
  project_id        TEXT,                   -- Sanity proje _id
  in_memory_of      TEXT,                   -- Anısına bağış için
  created_at        TIMESTAMPTZ DEFAULT NOW()
);

-- Aboneler (bülten)
CREATE TABLE IF NOT EXISTS subscribers (
  id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  email       TEXT UNIQUE NOT NULL,
  name        TEXT,
  status      TEXT DEFAULT 'active' CHECK (status IN ('active','unsubscribed')),
  source      TEXT DEFAULT 'website',
  created_at  TIMESTAMPTZ DEFAULT NOW()
);

-- İletişim Mesajları
CREATE TABLE IF NOT EXISTS contact_messages (
  id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name        TEXT NOT NULL,
  email       TEXT NOT NULL,
  phone       TEXT,
  subject     TEXT,
  message     TEXT NOT NULL,
  is_read     BOOLEAN DEFAULT false,
  replied_at  TIMESTAMPTZ,
  created_at  TIMESTAMPTZ DEFAULT NOW()
);

-- Gönüllü Başvuruları (genel, etkinliğe özel değil)
CREATE TABLE IF NOT EXISTS volunteer_applications (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name          TEXT NOT NULL,
  email         TEXT NOT NULL,
  phone         TEXT,
  city          TEXT,
  birth_year    INT,
  occupation    TEXT,
  skills        TEXT[],
  availability  TEXT,
  motivation    TEXT NOT NULL,
  how_heard     TEXT,
  status        TEXT DEFAULT 'pending'
                CHECK (status IN ('pending','approved','rejected','waitlist')),
  applied_at    TIMESTAMPTZ DEFAULT NOW(),
  reviewed_at   TIMESTAMPTZ,
  reviewer_note TEXT
);

-- Admin Kullanıcı Rolleri
CREATE TABLE IF NOT EXISTS admin_roles (
  user_id   UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
  role      TEXT DEFAULT 'editor'
            CHECK (role IN ('superadmin','admin','editor','volunteer_manager')),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Bağış Abonelikler (aylık)
CREATE TABLE IF NOT EXISTS donation_subscriptions (
  id                UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  donor_email       TEXT NOT NULL,
  donor_name        TEXT,
  amount            DECIMAL(10,2) NOT NULL,
  currency          TEXT DEFAULT 'TRY',
  payment_provider  TEXT,
  subscription_id   TEXT,                 -- Stripe/iyzico sub ID
  status            TEXT DEFAULT 'active' CHECK (status IN ('active','paused','cancelled')),
  next_billing_date DATE,
  created_at        TIMESTAMPTZ DEFAULT NOW(),
  cancelled_at      TIMESTAMPTZ
);

-- Onur Duvarı (public bağışçı listesi)
CREATE TABLE IF NOT EXISTS honor_wall (
  id           UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  display_name TEXT NOT NULL,
  amount       DECIMAL(10,2),
  message      TEXT,
  donation_id  UUID REFERENCES donations(id),
  is_visible   BOOLEAN DEFAULT true,
  created_at   TIMESTAMPTZ DEFAULT NOW()
);

-- =============================================
-- ROW LEVEL SECURITY
-- =============================================

ALTER TABLE event_registrations ENABLE ROW LEVEL SECURITY;
ALTER TABLE volunteers           ENABLE ROW LEVEL SECURITY;
ALTER TABLE donations            ENABLE ROW LEVEL SECURITY;
ALTER TABLE subscribers          ENABLE ROW LEVEL SECURITY;
ALTER TABLE contact_messages     ENABLE ROW LEVEL SECURITY;
ALTER TABLE volunteer_applications ENABLE ROW LEVEL SECURITY;
ALTER TABLE honor_wall           ENABLE ROW LEVEL SECURITY;

-- Herkes kayıt yaptırabilir
CREATE POLICY "Anyone can register for events"
  ON event_registrations FOR INSERT WITH CHECK (true);

-- Herkes bağış yapabilir
CREATE POLICY "Anyone can donate"
  ON donations FOR INSERT WITH CHECK (true);

-- Herkes abone olabilir
CREATE POLICY "Anyone can subscribe"
  ON subscribers FOR INSERT WITH CHECK (true);

-- Herkes mesaj gönderebilir
CREATE POLICY "Anyone can send message"
  ON contact_messages FOR INSERT WITH CHECK (true);

-- Herkes gönüllü başvurabilir
CREATE POLICY "Anyone can apply as volunteer"
  ON volunteer_applications FOR INSERT WITH CHECK (true);

-- Onur duvarı herkese açık
CREATE POLICY "Honor wall is public"
  ON honor_wall FOR SELECT USING (is_visible = true);

-- Admin: service_role ile tüm erişim (RLS bypass)
-- =============================================
-- İNDEKSLER
-- =============================================

CREATE INDEX IF NOT EXISTS idx_registrations_event   ON event_registrations(event_id);
CREATE INDEX IF NOT EXISTS idx_registrations_email   ON event_registrations(email);
CREATE INDEX IF NOT EXISTS idx_registrations_status  ON event_registrations(status);
CREATE INDEX IF NOT EXISTS idx_donations_email        ON donations(donor_email);
CREATE INDEX IF NOT EXISTS idx_donations_status       ON donations(payment_status);
CREATE INDEX IF NOT EXISTS idx_donations_created      ON donations(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_volunteers_status      ON volunteers(status);
CREATE INDEX IF NOT EXISTS idx_subscribers_email      ON subscribers(email);
