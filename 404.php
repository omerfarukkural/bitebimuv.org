<?php
/**
 * 404 Sayfa Bulunamadı Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();
?>
<div class="bbm-404 container">
    <div class="bbm-404__inner">
        <div class="bbm-404__smiley">
            <?php echo bbm_get_smiley( 'large', 'bbm-404-smiley' ); ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 404'te üzün görünüm
                const smile = document.getElementById('bbm-404-smiley-smile');
                const lBrow = document.getElementById('bbm-404-smiley-left-brow');
                const rBrow = document.getElementById('bbm-404-smiley-right-brow');
                if (smile) smile.setAttribute('d', 'M 30 75 Q 50 65 70 75');
                if (lBrow) lBrow.setAttribute('d', 'M 24 28 Q 33 24 42 28');
                if (rBrow) rBrow.setAttribute('d', 'M 58 28 Q 67 24 76 28');
            });
            </script>
        </div>
        <div class="bbm-404__content">
            <span class="bbm-404__code">404</span>
            <h1 class="bbm-404__title"><?php _e( 'Hay aksi! Bu sayfa bulunamadı.', 'bitebimuv-dernek' ); ?></h1>
            <p class="bbm-404__desc"><?php _e( 'Aradığınız sayfa taşınmış, silinmiş ya da hiç var olmamış olabilir.', 'bitebimuv-dernek' ); ?></p>
            <div class="bbm-404__actions">
                <a href="<?php echo home_url( '/' ); ?>" class="bbm-btn bbm-btn--primary">
                    <?php _e( 'Anasayfaya Dön', 'bitebimuv-dernek' ); ?>
                </a>
            </div>
            <div class="bbm-404__search">
                <p><?php _e( 'Aradığınızı bulmak için arama yapın:', 'bitebimuv-dernek' ); ?></p>
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
