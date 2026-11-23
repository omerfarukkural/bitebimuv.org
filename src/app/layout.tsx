import type { Metadata } from 'next'
import { Fraunces, Plus_Jakarta_Sans, JetBrains_Mono } from 'next/font/google'
import '@/styles/globals.css'
import { Header } from '@/components/layout/Header'
import { Footer } from '@/components/layout/Footer'
import { Toaster } from 'react-hot-toast'

const fraunces = Fraunces({
  subsets: ['latin'],
  variable: '--font-fraunces',
  axes: ['opsz'],
  display: 'swap',
})
const jakarta = Plus_Jakarta_Sans({
  subsets: ['latin'],
  variable: '--font-jakarta',
  display: 'swap',
})
const mono = JetBrains_Mono({
  subsets: ['latin'],
  variable: '--font-mono',
  display: 'swap',
})

export const metadata: Metadata = {
  metadataBase: new URL('https://www.bitebimuv.org'),
  title: { default: 'Tebessüm Bin Mutluluk Derneği', template: '%s | Tebessüm Bin Mutluluk' },
  description: 'Bir tebessüm bin mutluluk. Gönüllü olun veya bağış yapın — her katkı bir çocuğun yüzünü güldürüyor.',
  keywords: ['dernek','gönüllü','bağış','çocuk','tebessüm','yardım','sosyal sorumluluk'],
  openGraph: {
    type: 'website',
    locale: 'tr_TR',
    url: 'https://www.bitebimuv.org',
    siteName: 'Tebessüm Bin Mutluluk Derneği',
    images: [{ url: '/og-image.png', width: 1200, height: 630 }],
  },
  twitter: { card: 'summary_large_image', site: '@bitebimuv' },
  robots: { index: true, follow: true },
}

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="tr" className={`${fraunces.variable} ${jakarta.variable} ${mono.variable}`}>
      <body className="bg-ground text-ink antialiased">
        <Header />
        <main>{children}</main>
        <Footer />
        <Toaster
          position="bottom-right"
          toastOptions={{
            style: { background: '#16233F', color: '#EEE8DE', border: '1px solid rgba(244,198,61,0.2)' },
            success: { iconTheme: { primary: '#F4C63D', secondary: '#0D1526' } },
            error:   { iconTheme: { primary: '#E55243', secondary: '#0D1526' } },
          }}
        />
      </body>
    </html>
  )
}
