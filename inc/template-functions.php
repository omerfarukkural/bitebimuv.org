<?php
/**
 * BiteBiMuv — Şablon Yardımcı Fonksiyonlar
 */

/* ── SVG Smiley Generator ── */
function bbm_get_smiley( string $size = 'medium', string $id = 'bbm' ): string {
    $sizes = [ 'small'=>80, 'medium'=>140, 'large'=>220, 'hero'=>300, 'xlarge'=>400 ];
    $px    = $sizes[$size] ?? 140;
    $h     = $px;
    return <<<SVG
<svg id="{$id}" class="bbm-smiley bbm-smiley--{$size}" width="{$px}" height="{$h}"
     viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"
     role="img" aria-label="Gülen yüz maskotu">
  <defs>
    <radialGradient id="{$id}-face-grad" cx="45%" cy="35%" r="65%">
      <stop offset="0%"   stop-color="#FFE566"/>
      <stop offset="60%"  stop-color="#FFD93D"/>
      <stop offset="100%" stop-color="#F5A623"/>
    </radialGradient>
    <radialGradient id="{$id}-blush-grad" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#FF8BA0" stop-opacity="0.8"/>
      <stop offset="100%" stop-color="#FF8BA0" stop-opacity="0"/>
    </radialGradient>
    <filter id="{$id}-shadow" x="-20%" y="-20%" width="140%" height="150%">
      <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="rgba(0,0,0,0.2)"/>
    </filter>
    <filter id="{$id}-glow">
      <feGaussianBlur stdDeviation="3" result="blur"/>
      <feComposite in="SourceGraphic" in2="blur" operator="over"/>
    </filter>
    <clipPath id="{$id}-face-clip">
      <circle cx="100" cy="100" r="82"/>
    </clipPath>
  </defs>

  <!-- Shadow -->
  <ellipse cx="100" cy="190" rx="58" ry="10" fill="rgba(0,0,0,0.15)"/>

  <!-- Face base -->
  <circle cx="100" cy="100" r="84" fill="url(#{$id}-face-grad)" filter="url(#{$id}-shadow)"/>

  <!-- Shine -->
  <ellipse cx="74" cy="68" rx="18" ry="12" fill="rgba(255,255,255,0.35)" transform="rotate(-30 74 68)"/>

  <!-- Hair strands -->
  <g fill="#8B5E3C" opacity="0.7">
    <ellipse cx="60" cy="22" rx="5" ry="12" transform="rotate(-15 60 22)"/>
    <ellipse cx="78" cy="16" rx="4" ry="10" transform="rotate(-5 78 16)"/>
    <ellipse cx="96" cy="14" rx="4" ry="11"/>
    <ellipse cx="114" cy="16" rx="4" ry="10" transform="rotate(5 114 16)"/>
    <ellipse cx="130" cy="24" rx="4" ry="10" transform="rotate(18 130 24)"/>
  </g>

  <!-- Left Eye white -->
  <ellipse id="{$id}-left-eye" cx="72" cy="88" rx="14" ry="16" fill="white"/>
  <!-- Right Eye white -->
  <ellipse id="{$id}-right-eye" cx="128" cy="88" rx="14" ry="16" fill="white"/>

  <!-- Left Pupil -->
  <circle id="{$id}-left-pupil" cx="72" cy="90" r="7" fill="#2D3561"/>
  <circle cx="0" cy="0" r="2.5" fill="white" id="{$id}-left-shine" transform="translate(69,87)"/>
  <!-- Right Pupil -->
  <circle id="{$id}-right-pupil" cx="128" cy="90" r="7" fill="#2D3561"/>
  <circle cx="0" cy="0" r="2.5" fill="white" id="{$id}-right-shine" transform="translate(125,87)"/>

  <!-- Eyelids (blink) -->
  <ellipse id="{$id}-left-lid"  cx="72"  cy="84" rx="14" ry="0" fill="#FFD93D"/>
  <ellipse id="{$id}-right-lid" cx="128" cy="84" rx="14" ry="0" fill="#FFD93D"/>

  <!-- Eyebrows -->
  <path id="{$id}-left-brow"  d="M58,74 Q72,68 86,72" stroke="#6B4F2A" stroke-width="3.5" stroke-linecap="round" fill="none"/>
  <path id="{$id}-right-brow" d="M114,72 Q128,68 142,74" stroke="#6B4F2A" stroke-width="3.5" stroke-linecap="round" fill="none"/>

  <!-- Nose -->
  <ellipse cx="100" cy="110" rx="5" ry="3.5" fill="#F0A020" opacity="0.6"/>

  <!-- Smile -->
  <path id="{$id}-smile" d="M72,126 Q100,148 128,126" stroke="#6B4F2A" stroke-width="3.5" stroke-linecap="round" fill="none"/>
  <!-- Teeth -->
  <path id="{$id}-teeth" d="M80,128 Q100,140 120,128" fill="white" stroke="#ddd" stroke-width="0.5" clip-path="url(#{$id}-face-clip)" opacity="0"/>

  <!-- Cheeks -->
  <ellipse id="{$id}-left-cheek"  cx="52"  cy="118" rx="18" ry="12" fill="url(#{$id}-blush-grad)" opacity="0"/>
  <ellipse id="{$id}-right-cheek" cx="148" cy="118" rx="18" ry="12" fill="url(#{$id}-blush-grad)" opacity="0"/>

  <!-- Freckles -->
  <g fill="#D4891A" opacity="0.4">
    <circle cx="48"  cy="108" r="2"/><circle cx="44"  cy="116" r="1.5"/><circle cx="52"  cy="114" r="1.5"/>
    <circle cx="152" cy="108" r="2"/><circle cx="156" cy="116" r="1.5"/><circle cx="148" cy="114" r="1.5"/>
  </g>
</svg>
SVG;
}

/* ── Upcoming Events ── */
function bbm_get_upcoming_events( int $count = 4 ): WP_Query {
    return new WP_Query( [
        'post_type'      => 'bbm_event',
        'posts_per_page' => $count,
        'meta_key'       => '_bbm_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [[
            'key'     => '_bbm_event_date',
            'value'   => date('Y-m-d'),
            'compare' => '>=',
            'type'    => 'DATE',
        ]],
    ] );
}

/* ── Turkish formatted event date ── */
function bbm_get_event_date_formatted( int $post_id = 0, string $format = 'full' ): string {
    $post_id = $post_id ?: get_the_ID();
    $raw     = get_post_meta( $post_id, '_bbm_event_date', true );
    if ( ! $raw ) return __( 'Tarih belirtilmedi', 'bitebimuv-dernek' );

    $months_short = ['Oca','Şub','Mar','Nis','May','Haz','Tem','Ağu','Eyl','Eki','Kas','Ara'];
    $months_long  = ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
    $days         = ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'];

    $ts      = strtotime($raw);
    $day     = (int) date('j',  $ts);
    $monthI  = (int) date('n',  $ts) - 1;
    $year    = (int) date('Y',  $ts);
    $dow     = (int) date('w',  $ts);
    $time    = get_post_meta( $post_id, '_bbm_event_time', true ) ?: '';

    return match ($format) {
        'short'   => "{$day} {$months_short[$monthI]}",
        'medium'  => "{$day} {$months_long[$monthI]} {$year}",
        'day'     => (string) $day,
        'month'   => $months_short[$monthI],
        'year'    => (string) $year,
        'dayname' => $days[$dow],
        default   => "{$days[$dow]}, {$day} {$months_long[$monthI]} {$year}" . ($time ? " — {$time}" : ''),
    };
}

/* ── Social Links — raw data array ── */
function bbm_get_social_links(): array {
    $icons = [
        'facebook'  => ['Facebook',  'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z'],
        'instagram' => ['Instagram', 'M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37M17.5 6.5h.01M3 6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3z'],
        'twitter'   => ['Twitter',   'M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z'],
        'youtube'   => ['YouTube',   'M22.54 6.42A2.78 2.78 0 0 0 20.59 4.47C18.88 4 12 4 12 4s-6.88 0-8.59.47a2.78 2.78 0 0 0-2 1.95A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58M10 15.5v-7l6 3.5z'],
        'linkedin'  => ['LinkedIn',  'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6M2 9h4v12H2z M4 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4'],
        'whatsapp'  => ['WhatsApp',  'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347'],
        'telegram'  => ['Telegram',  'M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0m4.962 7.069-4.996 2.287-6.337-2.43c-.145-.05-.266-.15-.26-.266.013-.185.204-.27.427-.34l15.294-5.877c.313-.115.615.077.478.44l-2.607 13.092c-.205.814-.57 1.035-1 .685L13.97 13.8l-2.19 2.11c-.242.233-.447.362-.713.362-.22 0-.48-.142-.617-.435l-1.64-5.41 5.497 2.09c.207.079.267.005.267-.005L16.906 7.07z'],
        'tiktok'    => ['TikTok',    'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.5V6.73a4.85 4.85 0 0 1-1.01-.04'],
    ];

    $fill_platforms   = ['facebook','instagram','youtube','whatsapp','telegram','tiktok'];
    $stroke_platforms = ['twitter','linkedin'];
    $result = [];

    foreach ( $icons as $platform => [$label, $path] ) {
        $url = get_theme_mod( "bbm_social_{$platform}", '' );
        if ( ! $url ) continue;
        if ( $platform === 'whatsapp' ) {
            $url = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $url );
        }
        $fill   = in_array( $platform, $fill_platforms, true ) ? 'currentColor' : 'none';
        $stroke = in_array( $platform, $stroke_platforms, true ) ? 'currentColor' : 'none';
        $icon   = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="%s" stroke="%s" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="%s"/></svg>',
            esc_attr( $fill ), esc_attr( $stroke ), esc_attr( $path )
        );
        $result[] = [
            'url'      => esc_url( $url ),
            'label'    => $label,
            'platform' => $platform,
            'icon'     => $icon,
        ];
    }

    return $result;
}

/* ── Share Buttons ── */
function bbm_share_buttons(): void {
    $encoded_url  = urlencode( get_permalink() );
    $encoded_text = urlencode( get_the_title() . ' — ' . get_bloginfo('name') );
    $fb_href      = esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url );
    $tw_href      = esc_url( 'https://twitter.com/intent/tweet?text=' . $encoded_text . '&url=' . $encoded_url );
    $wa_href      = esc_url( 'https://wa.me/?text=' . $encoded_text . '%20' . $encoded_url );
    ?>
    <div class="bbm-share-buttons" role="group" aria-label="<?php _e('Paylaş','bitebimuv-dernek'); ?>">
        <span class="bbm-share-label"><?php _e('Paylaş:','bitebimuv-dernek'); ?></span>
        <a href="<?php echo $fb_href; ?>" target="_blank" rel="noopener" class="bbm-share-btn bbm-share-btn--facebook" aria-label="Facebook'ta paylaş">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            Facebook
        </a>
        <a href="<?php echo $tw_href; ?>" target="_blank" rel="noopener" class="bbm-share-btn bbm-share-btn--twitter" aria-label="Twitter'da paylaş">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
            Twitter
        </a>
        <a href="<?php echo $wa_href; ?>" target="_blank" rel="noopener" class="bbm-share-btn bbm-share-btn--whatsapp" aria-label="WhatsApp'ta paylaş">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
            WhatsApp
        </a>
        <button class="bbm-share-btn bbm-share-btn--copy" data-url="<?php echo esc_attr(get_permalink()); ?>" aria-label="Bağlantıyı kopyala">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            <?php _e('Kopyala','bitebimuv-dernek'); ?>
        </button>
    </div>
    <?php
}

/* ── Breadcrumb ── */
function bbm_breadcrumb(): void {
    if ( is_front_page() ) return;
    $items   = [];
    $items[] = '<li class="bbm-breadcrumb__item"><a href="' . home_url() . '">' . __('Ana Sayfa','bitebimuv-dernek') . '</a></li>';

    if ( is_singular() ) {
        if ( $cat = get_the_category() ) {
            $items[] = '<li class="bbm-breadcrumb__item"><a href="' . esc_url(get_category_link($cat[0]->term_id)) . '">' . esc_html($cat[0]->name) . '</a></li>';
        }
        $items[] = '<li class="bbm-breadcrumb__item bbm-breadcrumb__item--current" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif ( is_post_type_archive() ) {
        $items[] = '<li class="bbm-breadcrumb__item bbm-breadcrumb__item--current">' . esc_html(post_type_archive_title('',false)) . '</li>';
    } elseif ( is_category() || is_tag() ) {
        $items[] = '<li class="bbm-breadcrumb__item bbm-breadcrumb__item--current">' . single_cat_title('',false) . '</li>';
    } elseif ( is_search() ) {
        $items[] = '<li class="bbm-breadcrumb__item bbm-breadcrumb__item--current">' . sprintf(__('Arama: %s','bitebimuv-dernek'), get_search_query()) . '</li>';
    } elseif ( is_404() ) {
        $items[] = '<li class="bbm-breadcrumb__item bbm-breadcrumb__item--current">404</li>';
    }

    echo '<nav class="bbm-breadcrumb" aria-label="' . __('Bulunduğunuz yer','bitebimuv-dernek') . '"><ol class="bbm-breadcrumb__list">' . implode('<li class="bbm-breadcrumb__sep" aria-hidden="true">/</li>', $items) . '</ol></nav>';
}
