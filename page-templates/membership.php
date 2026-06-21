<?php
/**
 * Template Name: Üyelik Sayfası
 * Template Post Type: page
 *
 * @package bitebimuv-dernek
 */

get_header();

// Üyelik başlık bölümü
$page_title    = get_the_title();
$page_subtitle = get_post_meta( get_the_ID(), '_bbm_page_subtitle', true );
?>

<div class="bbm-page-header bbm-page-header--membership">
    <div class="bbm-page-header__bg" aria-hidden="true"></div>
    <div class="bbm-container">
        <?php bbm_breadcrumb(); ?>
        <div class="bbm-page-header__content">
            <span class="bbm-page-header__badge">
                <?php echo bbm_get_smiley( 'small', 'bbm-membership-page-smiley' ); ?>
                <?php esc_html_e( 'Ailemize Katıl', 'bitebimuv-dernek' ); ?>
            </span>
            <h1 class="bbm-page-header__title"><?php echo esc_html( $page_title ); ?></h1>
            <?php if ( $page_subtitle ) : ?>
            <p class="bbm-page-header__subtitle"><?php echo esc_html( $page_subtitle ); ?></p>
            <?php else : ?>
            <p class="bbm-page-header__subtitle">
                <?php esc_html_e( 'Topluluğumuzun bir parçası olun. Size en uygun üyelik planını seçin.', 'bitebimuv-dernek' ); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Sayfa içeriği (varsa) -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
    if ( get_the_content() ) : ?>
<section class="bbm-section bbm-section--sm">
    <div class="bbm-container bbm-prose">
        <?php the_content(); ?>
    </div>
</section>
<?php endif; endwhile; endif; ?>

<!-- Üyelik planları ve başvuru formu -->
<?php get_template_part( 'template-parts/sections/membership' ); ?>

<!-- SSS - Sıkça Sorulan Sorular -->
<section class="bbm-section bbm-faq-section" aria-labelledby="bbm-faq-title">
    <div class="bbm-container">
        <header class="bbm-section-header" data-aos="fade-up">
            <h2 id="bbm-faq-title" class="bbm-section-title">
                <?php esc_html_e( 'Sıkça Sorulan', 'bitebimuv-dernek' ); ?>
                <span class="bbm-gradient-text"><?php esc_html_e( 'Sorular', 'bitebimuv-dernek' ); ?></span>
            </h2>
        </header>
        <div class="bbm-faq-list" role="list">
            <?php
            $faqs = apply_filters( 'bbm_membership_faqs', [
                [
                    'q' => __( 'Üyelik başvurusu ne kadar sürer?', 'bitebimuv-dernek' ),
                    'a' => __( 'Başvurunuz alındıktan sonra en geç 5 iş günü içinde size dönüş yapılır. Yönetim kurulu onayının ardından üyeliğiniz aktif hale gelir.', 'bitebimuv-dernek' ),
                ],
                [
                    'q' => __( 'Üyelik ücretini nasıl öderim?', 'bitebimuv-dernek' ),
                    'a' => __( 'Başvurunuz onaylandıktan sonra banka havalesi veya EFT ile ödeme yapabilirsiniz. Ödeme bilgileri size e-posta ile iletilecektir.', 'bitebimuv-dernek' ),
                ],
                [
                    'q' => __( 'Üyelikten ayrılabilir miyim?', 'bitebimuv-dernek' ),
                    'a' => __( 'Evet, istediğiniz zaman yazılı başvuru ile üyeliğinizi sonlandırabilirsiniz. Kalan süre için ücret iadesi yapılmaz.', 'bitebimuv-dernek' ),
                ],
                [
                    'q' => __( 'Farklı bir üyelik tipine geçiş yapabilir miyim?', 'bitebimuv-dernek' ),
                    'a' => __( 'Evet, üyelik döneminin başında üyelik tipinizi değiştirmek için dernek büromuza başvurabilirsiniz.', 'bitebimuv-dernek' ),
                ],
                [
                    'q' => __( '18 yaş altı üyelik mümkün mü?', 'bitebimuv-dernek' ),
                    'a' => __( '18 yaşından küçükler ebeveyn/vasi onayıyla öğrenci üyeliği başvurusu yapabilirler. Detaylı bilgi için bizimle iletişime geçin.', 'bitebimuv-dernek' ),
                ],
            ] );
            foreach ( $faqs as $i => $faq ) : ?>
            <div class="bbm-faq-item" role="listitem" data-aos="fade-up" data-aos-delay="<?php echo $i * 80; ?>">
                <button class="bbm-faq-item__question" aria-expanded="false"
                        aria-controls="bbm-faq-answer-<?php echo $i; ?>"
                        id="bbm-faq-question-<?php echo $i; ?>">
                    <?php echo esc_html( $faq['q'] ); ?>
                    <svg class="bbm-faq-item__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20" height="20" aria-hidden="true">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div class="bbm-faq-item__answer" id="bbm-faq-answer-<?php echo $i; ?>"
                     role="region" aria-labelledby="bbm-faq-question-<?php echo $i; ?>" hidden>
                    <p><?php echo esc_html( $faq['a'] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
