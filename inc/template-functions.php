<?php
/**
 * Şablon Yardımcı Fonksiyonları
 *
 * @package bitebimuv-dernek
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Belirtilen şablon parçasını dahil et
 */
function bbm_get_section( $section ) {
    get_template_part( 'template-parts/sections/' . $section );
}

/**
 * İnteraktif smiley SVG'yi döndür
 */
function bbm_get_smiley( $size = 'medium', $id = 'bbm-smiley' ) {
    $sizes = [ 'small' => '80px', 'medium' => '140px', 'large' => '220px', 'hero' => '300px' ];
    $w = $sizes[ $size ] ?? '140px';
    ob_start();
    ?>
    <svg id="<?php echo esc_attr( $id ); ?>" class="bbm-smiley bbm-smiley--<?php echo esc_attr( $size ); ?>" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="<?php esc_attr_e( 'BiteBiMuv maskotu', 'bitebimuv-dernek' ); ?>" style="width:<?php echo esc_attr( $w ); ?>;height:<?php echo esc_attr( $w ); ?>">
        <defs>
            <radialGradient id="bbm-face-grad-<?php echo esc_attr( $id ); ?>" cx="42%" cy="38%" r="60%">
                <stop offset="0%" stop-color="#FFE566"/>
                <stop offset="100%" stop-color="#FFB800"/>
            </radialGradient>
            <radialGradient id="bbm-cheek-grad" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="#FF9BB5" stop-opacity="0.9"/>
                <stop offset="100%" stop-color="#FF9BB5" stop-opacity="0"/>
            </radialGradient>
            <filter id="bbm-shadow">
                <feDropShadow dx="0" dy="3" stdDeviation="4" flood-color="rgba(0,0,0,0.2)"/>
            </filter>
        </defs>

        <!-- Gölge -->
        <ellipse cx="50" cy="95" rx="36" ry="5" fill="rgba(0,0,0,0.08)"/>

        <!-- Ana yüz -->
        <circle cx="50" cy="48" r="44" fill="url(#bbm-face-grad-<?php echo esc_attr( $id ); ?>)" filter="url(#bbm-shadow)"/>
        <circle cx="50" cy="48" r="44" fill="none" stroke="#E8A800" stroke-width="1.2" opacity="0.6"/>

        <!-- Sol göz -->
        <ellipse cx="33" cy="38" rx="9" ry="10.5" fill="white"/>
        <circle id="<?php echo esc_attr( $id ); ?>-left-pupil" cx="33" cy="38" r="5.5" fill="#1A1A2E"/>
        <circle cx="30.5" cy="35.5" r="2" fill="white" opacity="0.9"/>

        <!-- Sağ göz -->
        <ellipse cx="67" cy="38" rx="9" ry="10.5" fill="white"/>
        <circle id="<?php echo esc_attr( $id ); ?>-right-pupil" cx="67" cy="38" r="5.5" fill="#1A1A2E"/>
        <circle cx="64.5" cy="35.5" r="2" fill="white" opacity="0.9"/>

        <!-- Sol göz kapağı (kırpma için) -->
        <ellipse id="<?php echo esc_attr( $id ); ?>-left-lid" cx="33" cy="29" rx="9" ry="8.5" fill="#FFD93D" class="bbm-eyelid"/>
        <!-- Sağ göz kapağı -->
        <ellipse id="<?php echo esc_attr( $id ); ?>-right-lid" cx="67" cy="29" rx="9" ry="8.5" fill="#FFD93D" class="bbm-eyelid"/>

        <!-- Sol kaş -->
        <path id="<?php echo esc_attr( $id ); ?>-left-brow" d="M 24 24 Q 33 19 42 24" stroke="#8B6914" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <!-- Sağ kaş -->
        <path id="<?php echo esc_attr( $id ); ?>-right-brow" d="M 58 24 Q 67 19 76 24" stroke="#8B6914" stroke-width="2.5" fill="none" stroke-linecap="round"/>

        <!-- Burun -->
        <path d="M 48.5 52 Q 46 56 48.5 58 Q 51.5 56 50.5 52" fill="#CC8800" opacity="0.55"/>

        <!-- Gülümseme -->
        <path id="<?php echo esc_attr( $id ); ?>-smile" d="M 28 65 Q 50 78 72 65" stroke="#8B5E14" stroke-width="3.5" fill="none" stroke-linecap="round"/>

        <!-- Dişler (yüksek scroll'da görünür) -->
        <path id="<?php echo esc_attr( $id ); ?>-teeth" d="M 30 67 Q 50 80 70 67 L 70 69 Q 50 83 30 69 Z" fill="white" opacity="0" stroke="#DDD" stroke-width="0.5"/>

        <!-- Sol yanak pembesi -->
        <ellipse id="<?php echo esc_attr( $id ); ?>-left-cheek" cx="18" cy="56" rx="11" ry="7" fill="url(#bbm-cheek-grad)" opacity="0" style="transition:opacity 0.4s ease"/>
        <!-- Sağ yanak pembesi -->
        <ellipse id="<?php echo esc_attr( $id ); ?>-right-cheek" cx="82" cy="56" rx="11" ry="7" fill="url(#bbm-cheek-grad)" opacity="0" style="transition:opacity 0.4s ease"/>

        <!-- Çil noktaları -->
        <circle cx="16" cy="58" r="1.5" fill="#FF8FAB" opacity="0.35"/>
        <circle cx="21" cy="61" r="1" fill="#FF8FAB" opacity="0.28"/>
        <circle cx="84" cy="58" r="1.5" fill="#FF8FAB" opacity="0.35"/>
        <circle cx="79" cy="61" r="1" fill="#FF8FAB" opacity="0.28"/>

        <!-- Saç (üst) -->
        <path d="M 28 6 Q 23 1 20 6" stroke="#C8960A" stroke-width="2" fill="none" stroke-linecap="round" opacity="0.7"/>
        <path d="M 42 3 Q 39 -2 36 3" stroke="#C8960A" stroke-width="2" fill="none" stroke-linecap="round" opacity="0.7"/>
        <path d="M 58 3 Q 61 -2 64 3" stroke="#C8960A" stroke-width="2" fill="none" stroke-linecap="round" opacity="0.7"/>
        <path d="M 72 6 Q 77 1 80 6" stroke="#C8960A" stroke-width="2" fill="none" stroke-linecap="round" opacity="0.7"/>
    </svg>
    <?php
    return ob_get_clean();
}

/**
 * Sosyal medya ikonlarını döndür
 */
function bbm_get_social_links( $class = '' ) {
    $links = [
        'facebook'  => [ get_theme_mod( 'bbm_facebook' ),  'Facebook',  '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>' ],
        'instagram' => [ get_theme_mod( 'bbm_instagram' ), 'Instagram', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>' ],
        'twitter'   => [ get_theme_mod( 'bbm_twitter' ),   'X (Twitter)', '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>' ],
        'youtube'   => [ get_theme_mod( 'bbm_youtube' ),   'YouTube',   '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>' ],
        'linkedin'  => [ get_theme_mod( 'bbm_linkedin' ),  'LinkedIn',  '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>' ],
    ];

    $output = '';
    foreach ( $links as $key => [ $url, $label, $icon ] ) {
        if ( empty( $url ) ) continue;
        $output .= sprintf(
            '<a href="%s" class="bbm-social-link bbm-social-link--%s %s" target="_blank" rel="noopener noreferrer" aria-label="%s">%s</a>',
            esc_url( $url ), esc_attr( $key ), esc_attr( $class ), esc_attr( $label ), $icon
        );
    }
    return $output;
}

/**
 * Yaklaşan etkinlikleri getir
 */
function bbm_get_upcoming_events( $count = 3 ) {
    return new WP_Query( [
        'post_type'      => 'bbm_event',
        'posts_per_page' => $count,
        'meta_key'       => 'bbm_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'     => 'bbm_event_date',
                'value'   => date( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ],
        ],
    ] );
}

/**
 * Etkinlik tarihi formatlı döndür
 */
function bbm_get_event_date_formatted( $post_id, $format = 'd M Y' ) {
    $date = get_post_meta( $post_id, 'bbm_event_date', true );
    if ( ! $date ) return '';
    $months_tr = [
        'Jan' => 'Oca', 'Feb' => 'Şub', 'Mar' => 'Mar',
        'Apr' => 'Nis', 'May' => 'May', 'Jun' => 'Haz',
        'Jul' => 'Tem', 'Aug' => 'Ağu', 'Sep' => 'Eyl',
        'Oct' => 'Eki', 'Nov' => 'Kas', 'Dec' => 'Ara',
    ];
    $formatted = date( $format, strtotime( $date ) );
    return strtr( $formatted, $months_tr );
}

/**
 * Siteye eklenen sayfa mı?
 */
function bbm_is_page_template_active( $template ) {
    return is_page_template( $template );
}

/**
 * Paylaşım butonları
 */
function bbm_share_buttons() {
    $url   = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );
    ?>
    <div class="bbm-share-buttons">
        <span class="bbm-share-label"><?php _e( 'Paylaş:', 'bitebimuv-dernek' ); ?></span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener" class="bbm-share-btn bbm-share-btn--facebook" aria-label="Facebook'ta paylaş">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" class="bbm-share-btn bbm-share-btn--twitter" aria-label="Twitter'da paylaş">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="https://api.whatsapp.com/send?text=<?php echo $title . ' ' . $url; ?>" target="_blank" rel="noopener" class="bbm-share-btn bbm-share-btn--whatsapp" aria-label="WhatsApp'ta paylaş">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </a>
        <button class="bbm-share-btn bbm-share-btn--copy" onclick="navigator.clipboard.writeText('<?php echo esc_js( get_permalink() ); ?>').then(()=>this.classList.add('copied'))" aria-label="Linki kopyala">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
        </button>
    </div>
    <?php
}

/**
 * Breadcrumb
 */
function bbm_breadcrumb() {
    if ( is_front_page() ) return;
    ?>
    <nav class="bbm-breadcrumb" aria-label="<?php esc_attr_e( 'Sayfa yolu', 'bitebimuv-dernek' ); ?>">
        <ol class="bbm-breadcrumb__list">
            <li class="bbm-breadcrumb__item">
                <a href="<?php echo home_url(); ?>" class="bbm-breadcrumb__link"><?php _e( 'Anasayfa', 'bitebimuv-dernek' ); ?></a>
            </li>
            <?php if ( is_singular() ) : ?>
            <li class="bbm-breadcrumb__item bbm-breadcrumb__item--current" aria-current="page"><?php the_title(); ?></li>
            <?php elseif ( is_category() ) : ?>
            <li class="bbm-breadcrumb__item bbm-breadcrumb__item--current"><?php single_cat_title(); ?></li>
            <?php elseif ( is_tag() ) : ?>
            <li class="bbm-breadcrumb__item bbm-breadcrumb__item--current"><?php single_tag_title(); ?></li>
            <?php elseif ( is_search() ) : ?>
            <li class="bbm-breadcrumb__item bbm-breadcrumb__item--current"><?php printf( __( 'Arama: "%s"', 'bitebimuv-dernek' ), get_search_query() ); ?></li>
            <?php elseif ( is_archive() ) : ?>
            <li class="bbm-breadcrumb__item bbm-breadcrumb__item--current"><?php the_archive_title(); ?></li>
            <?php endif; ?>
        </ol>
    </nav>
    <?php
}
