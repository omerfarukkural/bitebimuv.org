<?php
/**
 * BiteBiMuv — WordPress Customizer (genişletilmiş)
 */

function bbm_customize_register( WP_Customize_Manager $wp_customize ): void {

    // ── Genel ──
    $wp_customize->add_section( 'bbm_general', [
        'title'    => __('⚡ BiteBiMuv — Genel','bitebimuv-dernek'),
        'priority' => 30,
    ] );
    $general_fields = [
        'hero_title'    => ['Hero Başlığı',          'Birlikte Daha Güçlüyüz'],
        'hero_subtitle' => ['Hero Alt Başlığı',      'Derneğimiz, toplumun her kesiminden insanı bir araya getirerek ortak değerler etrafında güçlü bir topluluk oluşturmaktadır.'],
        'hero_cta_text' => ['Hero Buton Metni',      'Üye Ol'],
        'hero_cta_url'  => ['Hero Buton URL',        '#uye-ol'],
        'hero_cta2_text'=> ['Hero Buton 2 Metni',    'Daha Fazla Bilgi'],
        'hero_cta2_url' => ['Hero Buton 2 URL',      '#hakkimizda'],
        'tagline'       => ['Site Sloganı',          'Gönüllü Derneği'],
        'founded_year'  => ['Kuruluş Yılı',          '2016'],
        'member_count'  => ['Üye Sayısı (gösterim)', '500'],
    ];
    foreach ( $general_fields as $key => [$label, $default] ) {
        $wp_customize->add_setting( "bbm_{$key}", ['default'=>$default,'sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage'] );
        $wp_customize->add_control( "bbm_{$key}", ['label'=>$label,'section'=>'bbm_general','type'=>'text'] );
    }

    // ── Renkler ──
    $wp_customize->add_section( 'bbm_colors', [ 'title'=>__('🎨 Renkler','bitebimuv-dernek'),'priority'=>31 ] );
    $colors = [
        'bbm_primary_color'   => ['Ana Renk',        '#E8435A'],
        'bbm_secondary_color' => ['İkincil Renk',    '#2D3561'],
        'bbm_accent_color'    => ['Vurgu Rengi',     '#FFD93D'],
        'bbm_dark_color'      => ['Koyu Renk',       '#1A1A2E'],
        'bbm_success_color'   => ['Başarı Rengi',    '#4CAF50'],
    ];
    foreach ( $colors as $key => [$label, $default] ) {
        $wp_customize->add_setting( $key, ['default'=>$default,'sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage'] );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, ['label'=>$label,'section'=>'bbm_colors'] ) );
    }

    // ── İstatistikler ──
    $wp_customize->add_section( 'bbm_stats', [ 'title'=>__('📊 İstatistikler','bitebimuv-dernek'),'priority'=>32 ] );
    for ( $i = 1; $i <= 4; $i++ ) {
        $defaults = [
            1 => ['500+','Üyemiz'],
            2 => ['120+','Etkinlik'],
            3 => ['8','Yıllık Deneyim'],
            4 => ['50+','Tamamlanan Proje'],
        ];
        $wp_customize->add_setting( "bbm_stat{$i}_number", ['default'=>$defaults[$i][0],'sanitize_callback'=>'sanitize_text_field'] );
        $wp_customize->add_setting( "bbm_stat{$i}_label",  ['default'=>$defaults[$i][1],'sanitize_callback'=>'sanitize_text_field'] );
        $wp_customize->add_control( "bbm_stat{$i}_number", ['label'=>"İstatistik {$i} — Sayı",'section'=>'bbm_stats','type'=>'text'] );
        $wp_customize->add_control( "bbm_stat{$i}_label",  ['label'=>"İstatistik {$i} — Etiket",'section'=>'bbm_stats','type'=>'text'] );
    }

    // ── İletişim ──
    $wp_customize->add_section( 'bbm_contact_info', [ 'title'=>__('📞 İletişim Bilgileri','bitebimuv-dernek'),'priority'=>33 ] );
    foreach ( ['address'=>'Adres','phone'=>'Telefon','email'=>'E-posta','maps_url'=>'Google Maps URL','working_hours'=>'Çalışma Saatleri'] as $key=>$label ) {
        $wp_customize->add_setting( "bbm_contact_{$key}", ['default'=>'','sanitize_callback'=>'sanitize_text_field'] );
        $wp_customize->add_control( "bbm_contact_{$key}", ['label'=>$label,'section'=>'bbm_contact_info','type'=>'text'] );
    }

    // ── Bağış / IBAN ──
    $wp_customize->add_section( 'bbm_donate', [ 'title'=>__('💳 Bağış / IBAN','bitebimuv-dernek'),'priority'=>33 ] );
    $wp_customize->add_setting( 'bbm_bank_holder', [
        'default'           => 'Bir Tebessüm Bin Mutluluk Derneği',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_setting( 'bbm_bank_iban', [
        'default'           => 'TR47 0001 0011 5898 3279 6950 01',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_setting( 'bbm_bank_name', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'bbm_bank_holder', ['label'=>'Hesap Sahibi (Alıcı Adı)','section'=>'bbm_donate','type'=>'text'] );
    $wp_customize->add_control( 'bbm_bank_iban',   ['label'=>'IBAN','section'=>'bbm_donate','type'=>'text'] );
    $wp_customize->add_control( 'bbm_bank_name',   ['label'=>'Banka Adı','description'=>'Örn: Ziraat Bankası','section'=>'bbm_donate','type'=>'text'] );

    // ── Sosyal Medya ──
    $wp_customize->add_section( 'bbm_social', [ 'title'=>__('📱 Sosyal Medya','bitebimuv-dernek'),'priority'=>34 ] );
    foreach ( ['facebook','instagram','twitter','youtube','linkedin','whatsapp','telegram','tiktok'] as $platform ) {
        $wp_customize->add_setting( "bbm_social_{$platform}", ['default'=>'','sanitize_callback'=>'esc_url_raw'] );
        $wp_customize->add_control( "bbm_social_{$platform}", ['label'=>ucfirst($platform).' URL','section'=>'bbm_social','type'=>'url'] );
    }

    // ── Hakkımızda ──
    $wp_customize->add_section( 'bbm_about', [ 'title'=>__('🏛️ Hakkımızda','bitebimuv-dernek'),'priority'=>35 ] );
    $wp_customize->add_setting( 'bbm_about_title', ['default'=>'Hakkımızda','sanitize_callback'=>'sanitize_text_field'] );
    $wp_customize->add_setting( 'bbm_about_text',  ['default'=>'Bir Tebessüm Bin Mutluluk Derneği olarak; ihtiyaç sahibi çocuklara, ailelere ve toplumun her kesimine dokunarak bir gülümsemenin bin mutluluğa dönüşebileceğine inanıyoruz. Gönüllülerimizle birlikte eğitim, sağlık, sosyal yardım ve dayanışma alanlarında projeler yürütüyoruz.','sanitize_callback'=>'wp_kses_post'] );
    $wp_customize->add_setting( 'bbm_about_mission', ['default'=>'','sanitize_callback'=>'sanitize_textarea_field'] );
    $wp_customize->add_setting( 'bbm_about_vision',  ['default'=>'','sanitize_callback'=>'sanitize_textarea_field'] );
    $wp_customize->add_control( 'bbm_about_title',    ['label'=>'Bölüm Başlığı','section'=>'bbm_about','type'=>'text'] );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'bbm_about_text', ['label'=>'Açıklama','section'=>'bbm_about','type'=>'textarea'] ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'bbm_about_mission', ['label'=>'Misyon','section'=>'bbm_about','type'=>'textarea'] ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'bbm_about_vision',  ['label'=>'Vizyon','section'=>'bbm_about','type'=>'textarea'] ) );

    // ── Üyelik ──
    $wp_customize->add_section( 'bbm_membership', [ 'title'=>__('🤝 Üyelik','bitebimuv-dernek'),'priority'=>36 ] );
    foreach ( [
        'fee_standard' => ['Standart Üyelik Ücreti (₺)','150'],
        'fee_premium'  => ['Premium Üyelik Ücreti (₺)', '300'],
        'fee_corporate'=> ['Kurumsal Üyelik Ücreti (₺)','1000'],
    ] as $key => [$label,$default] ) {
        $wp_customize->add_setting( "bbm_{$key}", ['default'=>$default,'sanitize_callback'=>'sanitize_text_field'] );
        $wp_customize->add_control( "bbm_{$key}", ['label'=>$label,'section'=>'bbm_membership','type'=>'text'] );
    }
    $wp_customize->add_setting( 'bbm_membership_page_url', ['default'=>'','sanitize_callback'=>'sanitize_text_field'] );
    $wp_customize->add_control( 'bbm_membership_page_url', ['label'=>'Üyelik Sayfası URL','section'=>'bbm_membership','type'=>'text'] );

    // ── Üst Bilgi Çubuğu ──
    $wp_customize->add_section( 'bbm_topbar', [ 'title'=>__('📢 Üst Bilgi Çubuğu','bitebimuv-dernek'),'priority'=>29 ] );
    $wp_customize->add_setting( 'bbm_topbar_enabled', ['default'=>'1','sanitize_callback'=>'sanitize_text_field'] );
    $wp_customize->add_setting( 'bbm_topbar_text',    ['default'=>'','sanitize_callback'=>'sanitize_text_field'] );
    $wp_customize->add_setting( 'bbm_topbar_url',     ['default'=>'','sanitize_callback'=>'esc_url_raw'] );
    $wp_customize->add_setting( 'bbm_topbar_bg',      ['default'=>'#E8435A','sanitize_callback'=>'sanitize_hex_color'] );
    $wp_customize->add_control( 'bbm_topbar_enabled', ['label'=>'Üst Çubuğu Göster','section'=>'bbm_topbar','type'=>'checkbox'] );
    $wp_customize->add_control( 'bbm_topbar_text',    ['label'=>'Çubuk Metni','section'=>'bbm_topbar','type'=>'text'] );
    $wp_customize->add_control( 'bbm_topbar_url',     ['label'=>'Bağlantı URL','section'=>'bbm_topbar','type'=>'url'] );
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize,'bbm_topbar_bg',['label'=>'Arka Plan Rengi','section'=>'bbm_topbar']) );
}
add_action( 'customize_register', 'bbm_customize_register' );
