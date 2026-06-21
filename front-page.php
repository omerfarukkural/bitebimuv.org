<?php
/**
 * Ana Sayfa Şablonu - v3.0 Şaheser Sürüm
 *
 * @package bitebimuv-dernek
 */

get_header();

// Hero bölümü
get_template_part( 'template-parts/sections/hero' );

// İstatistikler
get_template_part( 'template-parts/sections/stats' );

// Hakkımızda
get_template_part( 'template-parts/sections/about' );

// Yaklaşan etkinlikler
get_template_part( 'template-parts/sections/events' );

// Galeri (en son fotoğraflar)
get_template_part( 'template-parts/sections/gallery' );

// Üyelik planları
get_template_part( 'template-parts/sections/membership' );

// Yönetim kurulu / ekip üyeleri
get_template_part( 'template-parts/sections/members' );

// Tarihçe zaman tüneli
get_template_part( 'template-parts/sections/timeline' );

// Son haberler
get_template_part( 'template-parts/sections/news' );

// İletişim
get_template_part( 'template-parts/sections/contact' );

get_footer();
