import Link from 'next/link'
import { Instagram, Twitter, Facebook, Youtube, Linkedin, Heart } from 'lucide-react'

const LINKS = {
  Keşfet: [
    { label: 'Etkinlikler',     href: '/etkinlikler' },
    { label: 'Projeler',        href: '/projeler' },
    { label: 'Başarı Hikayeleri', href: '/hikayeler' },
    { label: 'Blog',            href: '/blog' },
    { label: 'Etki Paneli',     href: '/etki' },
  ],
  Katıl: [
    { label: 'Bağış Yap',       href: '/bagis' },
    { label: 'Gönüllü Ol',      href: '/gonullu' },
    { label: 'Kurumsal',        href: '/kurumsal' },
    { label: 'Etkinliğe Kaydol',href: '/etkinlikler' },
    { label: 'Bülten',          href: '/bulten' },
  ],
  Hakkımızda: [
    { label: 'Misyon & Vizyon', href: '/misyon' },
    { label: 'Ekibimiz',        href: '/ekip' },
    { label: 'Şeffaflık',       href: '/seffaflik' },
    { label: 'Basın Odası',     href: '/basin' },
    { label: 'İletişim',        href: '/iletisim' },
  ],
  Yasal: [
    { label: 'Gizlilik',        href: '/gizlilik' },
    { label: 'KVKK',            href: '/kvkk' },
    { label: 'Çerez Politikası',href: '/cerez' },
    { label: 'Dernek Tüzüğü',  href: '/tuzuk' },
    { label: 'Yıllık Rapor',    href: '/raporlar' },
  ],
}

export function Footer() {
  return (
    <footer className="border-t border-border/60 mt-24">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 py-16">
        <div className="grid grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
          {/* Brand */}
          <div className="col-span-2 lg:col-span-1">
            <Link href="/" className="flex items-center gap-2 mb-4">
              <span className="w-8 h-8 rounded-lg bg-gold flex items-center justify-center text-ground font-bold">☺</span>
              <span className="font-display font-semibold text-sm leading-tight">
                Tebessüm Bin<br/>Mutluluk Derneği
              </span>
            </Link>
            <p className="text-muted text-sm leading-relaxed mb-6">
              Bir tebessüm, bin mutluluk. Gönüllü olun veya bağış yapın.
            </p>
            <div className="flex gap-3">
              {[
                { Icon: Instagram, href: 'https://instagram.com/bitebimuv' },
                { Icon: Twitter,   href: 'https://twitter.com/bitebimuv' },
                { Icon: Facebook,  href: 'https://facebook.com/bitebimuv' },
                { Icon: Youtube,   href: 'https://youtube.com/bitebimuv' },
              ].map(({ Icon, href }) => (
                <a key={href} href={href} target="_blank" rel="noreferrer"
                  className="w-9 h-9 rounded-lg bg-surface border border-border/60 flex items-center justify-center text-muted hover:text-gold hover:border-gold/40 transition-all">
                  <Icon size={16} />
                </a>
              ))}
            </div>
          </div>

          {/* Links */}
          {Object.entries(LINKS).map(([title, items]) => (
            <div key={title}>
              <p className="section-label mb-4">{title}</p>
              <ul className="space-y-2">
                {items.map(item => (
                  <li key={item.href}>
                    <Link href={item.href}
                      className="text-sm text-muted hover:text-ink transition-colors">
                      {item.label}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        <div className="border-t border-border/60 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-muted">
          <p>© 2024 Tebessüm Bin Mutluluk Derneği. Tüm hakları saklıdır.</p>
          <p className="flex items-center gap-1.5">
            Made with <Heart size={11} className="text-ember fill-current" /> for every child's smile
          </p>
        </div>
      </div>
    </footer>
  )
}
