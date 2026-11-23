'use client'

import Link from 'next/link'
import { useState, useEffect } from 'react'
import { Menu, X, Heart } from 'lucide-react'
import { cn } from '@/lib/utils'

const NAV = [
  { label: 'Etkinlikler', href: '/etkinlikler' },
  { label: 'Projeler',    href: '/projeler' },
  { label: 'Gönüllü Ol', href: '/gonullu' },
  { label: 'Hakkımızda', href: '/hakkimizda' },
  { label: 'Blog',        href: '/blog' },
]

export function Header() {
  const [open, setOpen]       = useState(false)
  const [scrolled, setScrolled] = useState(false)

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40)
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  return (
    <header className={cn(
      'fixed top-0 inset-x-0 z-50 transition-all duration-300',
      scrolled ? 'glass border-b border-border/60 py-3' : 'py-5'
    )}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between">
        {/* Logo */}
        <Link href="/" className="flex items-center gap-2.5 group">
          <span className="w-8 h-8 rounded-lg bg-gold flex items-center justify-center text-ground font-bold text-sm font-mono transition-transform group-hover:scale-110">
            ☺
          </span>
          <span className="font-display font-semibold text-ink leading-none">
            Tebessüm<br/>
            <span className="text-gold text-[11px] font-mono tracking-widest font-normal uppercase">Bin Mutluluk</span>
          </span>
        </Link>

        {/* Desktop Nav */}
        <nav className="hidden lg:flex items-center gap-1">
          {NAV.map(item => (
            <Link key={item.href} href={item.href}
              className="px-4 py-2 text-sm text-muted hover:text-ink hover:bg-surface rounded-lg transition-all">
              {item.label}
            </Link>
          ))}
        </nav>

        {/* CTA */}
        <div className="hidden lg:flex items-center gap-3">
          <Link href="/bagis" className="btn-primary text-sm gap-2">
            <Heart size={15} className="fill-current" /> Bağış Yap
          </Link>
        </div>

        {/* Mobile Toggle */}
        <button onClick={() => setOpen(v => !v)}
          className="lg:hidden p-2 rounded-lg hover:bg-surface transition-colors"
          aria-label="Menü">
          {open ? <X size={20} /> : <Menu size={20} />}
        </button>
      </div>

      {/* Mobile Menu */}
      {open && (
        <div className="lg:hidden glass border-t border-border/60 px-4 py-4 space-y-1">
          {NAV.map(item => (
            <Link key={item.href} href={item.href} onClick={() => setOpen(false)}
              className="block px-4 py-3 text-muted hover:text-ink hover:bg-surface rounded-lg transition-all">
              {item.label}
            </Link>
          ))}
          <Link href="/bagis" onClick={() => setOpen(false)}
            className="btn-primary w-full justify-center mt-3 gap-2">
            <Heart size={15} className="fill-current" /> Bağış Yap
          </Link>
        </div>
      )}
    </header>
  )
}
