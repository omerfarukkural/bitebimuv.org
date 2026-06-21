<?php
/**
 * BiteBiMuv — KVKK / Çerez Uyumu
 */

function bbm_kvkk_is_accepted(): bool {
    return isset( $_COOKIE['bbm_kvkk'] ) && $_COOKIE['bbm_kvkk'] === 'accepted';
}

function bbm_kvkk_show_banner(): bool {
    return ! bbm_kvkk_is_accepted();
}

function bbm_output_kvkk_banner(): void {
    if ( ! bbm_kvkk_show_banner() ) return;
    ?>
    <div id="bbm-kvkk-banner" class="bbm-kvkk-banner" role="dialog" aria-live="polite"
         aria-label="<?php esc_attr_e('Çerez Bildirimi','bitebimuv-dernek'); ?>">
        <div class="bbm-kvkk-inner">
            <div class="bbm-kvkk-icon">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <circle cx="20" cy="20" r="20" fill="var(--bbm-primary)" opacity=".12"/>
                    <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-size="20">🍪</text>
                </svg>
            </div>
            <div class="bbm-kvkk-content">
                <h4><?php _e('Çerez Politikası &amp; KVKK','bitebimuv-dernek'); ?></h4>
                <p>
                    <?php _e('Bu web sitesi, deneyiminizi iyileştirmek için çerezler kullanmaktadır. Kullanım koşulları ve','bitebimuv-dernek'); ?>
                    <a href="<?php echo esc_url( get_privacy_policy_url() ?: home_url('/gizlilik-politikasi/') ); ?>" target="_blank" rel="noopener">
                        <?php _e('KVKK Aydınlatma Metni','bitebimuv-dernek'); ?>
                    </a>
                    <?php _e('için tıklayın.','bitebimuv-dernek'); ?>
                </p>
            </div>
            <div class="bbm-kvkk-actions">
                <button id="bbm-kvkk-decline" class="bbm-kvkk-btn bbm-kvkk-btn--outline">
                    <?php _e('Reddet','bitebimuv-dernek'); ?>
                </button>
                <button id="bbm-kvkk-accept" class="bbm-kvkk-btn bbm-kvkk-btn--primary">
                    <?php _e('Kabul Et','bitebimuv-dernek'); ?>
                </button>
            </div>
            <button id="bbm-kvkk-close" class="bbm-kvkk-close" aria-label="<?php esc_attr_e('Kapat','bitebimuv-dernek'); ?>">
                <svg width="18" height="18" viewBox="0 0 18 18"><line x1="2" y1="2" x2="16" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="16" y1="2" x2="2" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'bbm_output_kvkk_banner' );
