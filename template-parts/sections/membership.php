<?php
/**
 * Üyelik Planları ve Başvuru Formu Bölümü
 *
 * @package bitebimuv-dernek
 */

$fee_standard  = get_theme_mod( 'bbm_fee_standard', '150' );
$fee_premium   = get_theme_mod( 'bbm_fee_premium', '300' );
$fee_corporate = get_theme_mod( 'bbm_fee_corporate', '1000' );

$plans = [
    [
        'id'       => 'standard',
        'name'     => __( 'Standart Üyelik', 'bitebimuv-dernek' ),
        'price'    => $fee_standard,
        'period'   => __( '/yıl', 'bitebimuv-dernek' ),
        'icon'     => '🌟',
        'featured' => false,
        'features' => [
            __( 'Etkinliklere katılım hakkı', 'bitebimuv-dernek' ),
            __( 'Aylık bülten', 'bitebimuv-dernek' ),
            __( 'Üye kartı', 'bitebimuv-dernek' ),
            __( 'Online toplantılara erişim', 'bitebimuv-dernek' ),
            __( 'Dernek arşivine erişim', 'bitebimuv-dernek' ),
        ],
        'missing' => [
            __( 'Yönetim kurulu seçme/seçilme', 'bitebimuv-dernek' ),
            __( 'Özel etkinlik indirimi', 'bitebimuv-dernek' ),
            __( 'Kurumsal sertifika', 'bitebimuv-dernek' ),
        ],
    ],
    [
        'id'       => 'premium',
        'name'     => __( 'Premium Üyelik', 'bitebimuv-dernek' ),
        'price'    => $fee_premium,
        'period'   => __( '/yıl', 'bitebimuv-dernek' ),
        'icon'     => '💎',
        'featured' => true,
        'badge'    => __( 'En Popüler', 'bitebimuv-dernek' ),
        'features' => [
            __( 'Etkinliklere katılım hakkı', 'bitebimuv-dernek' ),
            __( 'Aylık bülten', 'bitebimuv-dernek' ),
            __( 'Üye kartı', 'bitebimuv-dernek' ),
            __( 'Online toplantılara erişim', 'bitebimuv-dernek' ),
            __( 'Dernek arşivine erişim', 'bitebimuv-dernek' ),
            __( 'Yönetim kurulu seçme/seçilme', 'bitebimuv-dernek' ),
            __( 'Özel etkinlik %20 indirimi', 'bitebimuv-dernek' ),
        ],
        'missing' => [
            __( 'Kurumsal sertifika', 'bitebimuv-dernek' ),
        ],
    ],
    [
        'id'       => 'corporate',
        'name'     => __( 'Kurumsal Üyelik', 'bitebimuv-dernek' ),
        'price'    => $fee_corporate,
        'period'   => __( '/yıl', 'bitebimuv-dernek' ),
        'icon'     => '🏢',
        'featured' => false,
        'features' => [
            __( 'Etkinliklere katılım hakkı', 'bitebimuv-dernek' ),
            __( 'Aylık bülten', 'bitebimuv-dernek' ),
            __( 'Üye kartı', 'bitebimuv-dernek' ),
            __( 'Online toplantılara erişim', 'bitebimuv-dernek' ),
            __( 'Dernek arşivine erişim', 'bitebimuv-dernek' ),
            __( 'Yönetim kurulu seçme/seçilme', 'bitebimuv-dernek' ),
            __( 'Özel etkinlik %30 indirimi', 'bitebimuv-dernek' ),
            __( 'Kurumsal sertifika', 'bitebimuv-dernek' ),
            __( '5 çalışan için üyelik hakkı', 'bitebimuv-dernek' ),
        ],
        'missing' => [],
    ],
];
?>

<section class="bbm-section bbm-membership-section" id="uye-ol" aria-labelledby="bbm-membership-title">
    <div class="bbm-container">

        <header class="bbm-section-header" data-aos="fade-up">
            <span class="bbm-section-badge">
                <?php echo bbm_get_smiley( 'small', 'bbm-membership-smiley' ); ?>
                <?php esc_html_e( 'Ailemize Katıl', 'bitebimuv-dernek' ); ?>
            </span>
            <h2 id="bbm-membership-title" class="bbm-section-title">
                <?php esc_html_e( 'Üyelik', 'bitebimuv-dernek' ); ?>
                <span class="bbm-gradient-text"><?php esc_html_e( 'Planları', 'bitebimuv-dernek' ); ?></span>
            </h2>
            <p class="bbm-section-subtitle">
                <?php esc_html_e( 'Size en uygun planı seçin ve topluluğumuzun bir parçası olun.', 'bitebimuv-dernek' ); ?>
            </p>
        </header>

        <!-- Üyelik planları grid -->
        <div class="bbm-plans" role="list" data-aos="fade-up" data-aos-delay="100">
            <?php foreach ( $plans as $plan ) : ?>
            <div class="bbm-plan <?php echo $plan['featured'] ? 'bbm-plan--featured' : ''; ?>"
                 role="listitem" data-plan-id="<?php echo esc_attr( $plan['id'] ); ?>">

                <?php if ( ! empty( $plan['badge'] ) ) : ?>
                <div class="bbm-plan__badge" aria-label="<?php echo esc_attr( $plan['badge'] ); ?>">
                    <?php echo esc_html( $plan['badge'] ); ?>
                </div>
                <?php endif; ?>

                <div class="bbm-plan__header">
                    <div class="bbm-plan__icon" aria-hidden="true"><?php echo esc_html( $plan['icon'] ); ?></div>
                    <h3 class="bbm-plan__name"><?php echo esc_html( $plan['name'] ); ?></h3>
                    <div class="bbm-plan__price">
                        <span class="bbm-plan__currency">₺</span>
                        <span class="bbm-plan__amount"><?php echo esc_html( number_format( intval( $plan['price'] ), 0, ',', '.' ) ); ?></span>
                        <span class="bbm-plan__period"><?php echo esc_html( $plan['period'] ); ?></span>
                    </div>
                </div>

                <ul class="bbm-plan__features" role="list">
                    <?php foreach ( $plan['features'] as $feature ) : ?>
                    <li class="bbm-plan__feature bbm-plan__feature--yes" role="listitem">
                        <svg class="bbm-plan__check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php echo esc_html( $feature ); ?>
                    </li>
                    <?php endforeach; ?>
                    <?php foreach ( $plan['missing'] as $missing ) : ?>
                    <li class="bbm-plan__feature bbm-plan__feature--no" role="listitem">
                        <svg class="bbm-plan__x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        <s><?php echo esc_html( $missing ); ?></s>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="bbm-plan__footer">
                    <button class="bbm-btn <?php echo $plan['featured'] ? 'bbm-btn--primary' : 'bbm-btn--outline'; ?> bbm-btn--full bbm-plan-select-btn"
                            data-plan-name="<?php echo esc_attr( $plan['name'] ); ?>"
                            data-plan-id="<?php echo esc_attr( $plan['id'] ); ?>">
                        <?php esc_html_e( 'Bu Planı Seç', 'bitebimuv-dernek' ); ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Üyelik başvuru formu -->
        <div class="bbm-membership-form-wrap" id="bbm-membership-form-wrap" data-aos="fade-up" data-aos-delay="200">
            <div class="bbm-membership-form-header">
                <h3><?php esc_html_e( 'Üyelik Başvuru Formu', 'bitebimuv-dernek' ); ?></h3>
                <p><?php esc_html_e( 'Aşağıdaki formu doldurarak üyelik başvurunuzu yapabilirsiniz. En kısa sürede size dönüş yapacağız.', 'bitebimuv-dernek' ); ?></p>
            </div>
            <form class="bbm-form bbm-membership-form" id="bbm-membership-form" novalidate>
                <?php wp_nonce_field( 'bbm_membership_nonce', 'bbm_membership_nonce_field' ); ?>
                <input type="hidden" name="membership_type" id="bbm-membership-type" value="standard">

                <div class="bbm-form__row">
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-mem-name"><?php esc_html_e( 'Ad Soyad *', 'bitebimuv-dernek' ); ?></label>
                        <input type="text" id="bbm-mem-name" name="name" class="bbm-form-control" required
                               autocomplete="name" placeholder="<?php esc_attr_e( 'Adınız ve soyadınız', 'bitebimuv-dernek' ); ?>">
                    </div>
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-mem-email"><?php esc_html_e( 'E-posta *', 'bitebimuv-dernek' ); ?></label>
                        <input type="email" id="bbm-mem-email" name="email" class="bbm-form-control" required
                               autocomplete="email" placeholder="<?php esc_attr_e( 'ornek@eposta.com', 'bitebimuv-dernek' ); ?>">
                    </div>
                </div>
                <div class="bbm-form__row">
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-mem-phone"><?php esc_html_e( 'Telefon *', 'bitebimuv-dernek' ); ?></label>
                        <input type="tel" id="bbm-mem-phone" name="phone" class="bbm-form-control" required
                               autocomplete="tel" placeholder="<?php esc_attr_e( '0555 000 00 00', 'bitebimuv-dernek' ); ?>">
                    </div>
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-mem-city"><?php esc_html_e( 'Şehir *', 'bitebimuv-dernek' ); ?></label>
                        <input type="text" id="bbm-mem-city" name="city" class="bbm-form-control" required
                               autocomplete="address-level2" placeholder="<?php esc_attr_e( 'İstanbul', 'bitebimuv-dernek' ); ?>">
                    </div>
                </div>
                <div class="bbm-form__row">
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-mem-occupation"><?php esc_html_e( 'Meslek', 'bitebimuv-dernek' ); ?></label>
                        <input type="text" id="bbm-mem-occupation" name="occupation" class="bbm-form-control"
                               placeholder="<?php esc_attr_e( 'Mesleğiniz', 'bitebimuv-dernek' ); ?>">
                    </div>
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-mem-type-select"><?php esc_html_e( 'Üyelik Türü', 'bitebimuv-dernek' ); ?></label>
                        <select id="bbm-mem-type-select" name="membership_type_select" class="bbm-form-control bbm-form-select">
                            <option value="standard"><?php esc_html_e( 'Standart Üyelik', 'bitebimuv-dernek' ); ?></option>
                            <option value="premium"><?php esc_html_e( 'Premium Üyelik', 'bitebimuv-dernek' ); ?></option>
                            <option value="corporate"><?php esc_html_e( 'Kurumsal Üyelik', 'bitebimuv-dernek' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="bbm-form__group">
                    <label class="bbm-form__label" for="bbm-mem-motivation"><?php esc_html_e( 'Neden üye olmak istiyorsunuz?', 'bitebimuv-dernek' ); ?></label>
                    <textarea id="bbm-mem-motivation" name="motivation" class="bbm-form-control" rows="4"
                              placeholder="<?php esc_attr_e( 'Kısaca kendini tanıt ve motivasyonunu paylaş...', 'bitebimuv-dernek' ); ?>"></textarea>
                </div>
                <label class="bbm-form__checkbox">
                    <input type="checkbox" name="kvkk_consent" required>
                    <span><?php printf(
                        esc_html__( '%s kapsamında kişisel verilerimin işlenmesini kabul ediyorum.', 'bitebimuv-dernek' ),
                        '<a href="' . esc_url( get_privacy_policy_url() ) . '" target="_blank">' . esc_html__( 'KVKK Aydınlatma Metni', 'bitebimuv-dernek' ) . '</a>'
                    ); ?></span>
                </label>
                <div class="bbm-form-msg" id="bbm-membership-msg" role="alert" aria-live="polite"></div>
                <div class="bbm-form__submit">
                    <button type="submit" class="bbm-btn bbm-btn--primary bbm-btn--lg" id="bbm-membership-submit">
                        <span class="bbm-btn__text"><?php esc_html_e( 'Başvurumu Gönder', 'bitebimuv-dernek' ); ?></span>
                        <svg class="bbm-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
