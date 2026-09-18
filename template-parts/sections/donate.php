<?php
/**
 * Bağış / IBAN Bölümü
 *
 * @package bitebimuv-dernek
 */

$bank_holder = get_theme_mod( 'bbm_bank_holder', 'Bir Tebessüm Bin Mutluluk Derneği' );
$bank_iban   = get_theme_mod( 'bbm_bank_iban', 'TR47 0001 0011 5898 3279 6950 01' );
$bank_name   = get_theme_mod( 'bbm_bank_name', '' );

if ( ! $bank_iban ) {
    return;
}

$iban_raw = strtoupper( preg_replace( '/\s+/', '', $bank_iban ) );
?>
<section class="bbm-section bbm-donate" id="bagis-yap" aria-labelledby="bbm-donate-title">
    <div class="bbm-container">
        <div class="bbm-donate__card" data-aos="fade-up">
            <div class="bbm-donate__text">
                <span class="bbm-section-badge"><?php echo bbm_get_smiley( 'small', 'bbm-donate-smiley' ); ?> <?php esc_html_e( 'Bağışlarınızla Büyüyoruz', 'bitebimuv-dernek' ); ?></span>
                <h2 id="bbm-donate-title" class="bbm-donate__title"><?php esc_html_e( 'Bağış Yaparak Destek Olun', 'bitebimuv-dernek' ); ?></h2>
                <p class="bbm-donate__subtitle">
                    <?php esc_html_e( 'Yapacağınız her bağış, derneğimizin yürüttüğü projelere ve ihtiyaç sahiplerine doğrudan ulaşır. Aşağıdaki IBAN üzerinden havale/EFT ile bağış yapabilirsiniz.', 'bitebimuv-dernek' ); ?>
                </p>
            </div>

            <div class="bbm-donate__iban-box">
                <div class="bbm-donate__iban-row">
                    <span class="bbm-donate__iban-label"><?php esc_html_e( 'Alıcı Adı', 'bitebimuv-dernek' ); ?></span>
                    <strong class="bbm-donate__iban-value"><?php echo esc_html( $bank_holder ); ?></strong>
                </div>
                <?php if ( $bank_name ) : ?>
                <div class="bbm-donate__iban-row">
                    <span class="bbm-donate__iban-label"><?php esc_html_e( 'Banka', 'bitebimuv-dernek' ); ?></span>
                    <strong class="bbm-donate__iban-value"><?php echo esc_html( $bank_name ); ?></strong>
                </div>
                <?php endif; ?>
                <div class="bbm-donate__iban-row bbm-donate__iban-row--main">
                    <span class="bbm-donate__iban-label"><?php esc_html_e( 'IBAN', 'bitebimuv-dernek' ); ?></span>
                    <strong class="bbm-donate__iban-value bbm-iban" id="bbm-donate-iban-text"><?php echo esc_html( $bank_iban ); ?></strong>
                </div>
                <button type="button" class="bbm-btn bbm-btn--primary bbm-btn--lg bbm-copy-iban" data-iban="<?php echo esc_attr( $iban_raw ); ?>">
                    <span class="bbm-btn__text"><?php esc_html_e( 'IBAN\'ı Kopyala', 'bitebimuv-dernek' ); ?></span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
