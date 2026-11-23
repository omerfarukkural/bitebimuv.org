'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'
import {
  LayoutDashboard, Calendar, Users, Heart, BookOpen, FolderKanban,
  Megaphone, Mail, Settings, LogOut, ExternalLink, BarChart3, Shield
} from 'lucide-react'
import { cn } from '@/lib/utils'

const NAV_ITEMS = [
  { href: '/admin',            label: 'Dashboard',       icon: LayoutDashboard, exact: true },
  { href: '/admin/etkinlikler',label: 'Etkinlikler',     icon: Calendar },
  { href: '/admin/gonulluler', label: 'Gönüllüler',      icon: Users },
  { href: '/admin/bagislar',   label: 'Bağışlar',        icon: Heart },
  { href: '/admin/blog',       label: 'Blog & Haberler', icon: BookOpen },
  { href: '/admin/projeler',   label: 'Projeler',        icon: FolderKanban },
  { href: '/admin/duyurular',  label: 'Duyurular',       icon: Megaphone },
  { href: '/admin/mesajlar',   label: 'Mesajlar',        icon: Mail },
  { href: '/admin/analytics',  label: 'Analitik',        icon: BarChart3 },
  { href: '/admin/kullanicilar', label: 'Kullanıcılar',  icon: Shield },
  { href: '/admin/ayarlar',    label: 'Ayarlar',         icon: Settings },
]

interface Props { user: { email?: string }; role: string }

export function AdminSidebar({ user, role }: Props) {
  const pathname = usePathname()

  return (
    <aside className="fixed left-0 top-0 bottom-0 w-64 bg-surface border-r border-border/60 flex flex-col z-40">
      {/* Logo */}
      <div className="p-5 border-b border-border/60">
        <div className="flex items-center gap-2.5">
          <span className="w-8 h-8 rounded-lg bg-gold flex items-center justify-center text-ground font-bold text-sm">☺</span>
          <div>
            <p className="text-sm font-semibold leading-none">Admin Paneli</p>
            <p className="text-[10px] font-mono text-gold uppercase tracking-wider mt-0.5">{role}</p>
          </div>
        </div>
      </div>

      {/* Nav */}
      <nav className="flex-1 p-3 overflow-y-auto space-y-0.5">
        {NAV_ITEMS.map(({ href, label, icon: Icon, exact }) => {
          const active = exact ? pathname === href : pathname.startsWith(href) && href !== '/admin'
          return (
            <Link key={href} href={href} className={cn(
              'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all',
              active ? 'bg-gold/10 text-gold border border-gold/20' : 'text-muted hover:text-ink hover:bg-surface2'
            )}>
              <Icon size={16} className={active ? 'text-gold' : ''} />
              {label}
            </Link>
          )
        })}
      </nav>

      {/* Footer */}
      <div className="p-3 border-t border-border/60 space-y-1">
        <a href="/" target="_blank" rel="noreferrer"
          className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-muted hover:text-ink hover:bg-surface2 transition-all">
          <ExternalLink size={16} /> Siteyi Gör
        </a>
        <form action="/api/admin/logout" method="POST">
          <button type="submit" className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-muted hover:text-ember hover:bg-ember/5 transition-all w-full">
            <LogOut size={16} /> Çıkış Yap
          </button>
        </form>
        <div className="px-3 py-2">
          <p className="text-[11px] text-muted truncate">{user.email}</p>
        </div>
      </div>
    </aside>
  )
}
