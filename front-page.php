<?php
/**
 * Ana Sayfa Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();

// Hero bölümü
get_template_part( 'template-parts/sections/hero' );

// İstatistikler bölümü
get_template_part( 'template-parts/sections/stats' );

// Hakkımızda bölümü
get_template_part( 'template-parts/sections/about' );

// Etkinlikler bölümü
get_template_part( 'template-parts/sections/events' );

// Haberler bölümü
get_template_part( 'template-parts/sections/news' );

// İletişim bölümü
get_template_part( 'template-parts/sections/contact' );

get_footer();
