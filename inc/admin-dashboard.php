<?php
/**
 * Admin Dashboard Widget – Hızlı İstatistikler
 * Bite Bi Muv Derneği Teması v4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_dashboard_setup', 'bbm_register_dashboard_widgets' );
add_action( 'admin_enqueue_scripts', 'bbm_admin_dashboard_styles' );

function bbm_register_dashboard_widgets() {
    wp_add_dashboard_widget(
        'bbm_quick_stats',
        __( '🎯 Bitebimuv – Hızlı Bakış', 'bitebimuv' ),
        'bbm_dashboard_widget_content'
    );
    // Move our widget to top
    global $wp_meta_boxes;
    $widget = $wp_meta_boxes['dashboard']['normal']['core']['bbm_quick_stats'] ?? null;
    if ( $widget ) {
        unset( $wp_meta_boxes['dashboard']['normal']['core']['bbm_quick_stats'] );
        $wp_meta_boxes['dashboard']['normal']['high']['bbm_quick_stats'] = $widget;
    }
}

function bbm_dashboard_widget_content() {
    $total_members      = wp_count_posts( 'bbm_member' )->publish ?? 0;
    $total_events       = wp_count_posts( 'bbm_event' )->publish ?? 0;
    $total_projects     = wp_count_posts( 'bbm_project' )->publish ?? 0;
    $total_volunteers   = wp_count_posts( 'bbm_volunteer' )->publish ?? 0;
    $total_sponsors     = wp_count_posts( 'bbm_sponsor' )->publish ?? 0;
    $total_docs         = wp_count_posts( 'bbm_document' )->publish ?? 0;

    // Pending applications
    $pending_apps = new WP_Query( [
        'post_type'      => 'bbm_member_application',
        'post_status'    => 'pending',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ] );
    $pending_count = $pending_apps->found_posts;

    // Upcoming events (next 30 days)
    $today     = current_time( 'Y-m-d' );
    $in30days  = date( 'Y-m-d', strtotime( '+30 days' ) );
    $upcoming  = new WP_Query( [
        'post_type'      => 'bbm_event',
        'posts_per_page' => 5,
        'meta_query'     => [
            [
                'key'     => '_bbm_event_date',
                'value'   => [ $today, $in30days ],
                'compare' => 'BETWEEN',
                'type'    => 'DATE',
            ],
        ],
        'meta_key'  => '_bbm_event_date',
        'orderby'   => 'meta_value',
        'order'     => 'ASC',
    ] );

    // Recent membership applications
    $recent_apps = new WP_Query( [
        'post_type'      => 'bbm_member_application',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
    ?>
    <style>
    .bbm-dash { font-family: -apple-system, sans-serif; }
    .bbm-dash-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
    .bbm-dash-stat { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 10px; padding: 14px; text-align: center; }
    .bbm-dash-stat.green  { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .bbm-dash-stat.orange { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .bbm-dash-stat.blue   { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .bbm-dash-stat.teal   { background: linear-gradient(135deg, #43e97b, #38f9d7); }
    .bbm-dash-stat.rose   { background: linear-gradient(135deg, #fa709a, #fee140); }
    .bbm-dash-num  { font-size: 32px; font-weight: 700; line-height: 1; }
    .bbm-dash-lbl  { font-size: 11px; opacity: .85; margin-top: 4px; text-transform: uppercase; letter-spacing: .5px; }
    .bbm-dash-section { margin-bottom: 16px; }
    .bbm-dash-section h4 { margin: 0 0 8px; font-size: 13px; color: #1e1e1e; border-bottom: 2px solid #f0f0f0; padding-bottom: 6px; }
    .bbm-dash-list { list-style: none; margin: 0; padding: 0; }
    .bbm-dash-list li { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid #f5f5f5; font-size: 13px; }
    .bbm-dash-list li:last-child { border: 0; }
    .bbm-dash-badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .bbm-badge-pending { background: #fef3c7; color: #92400e; }
    .bbm-badge-approved { background: #d1fae5; color: #065f46; }
    .bbm-alert { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; padding: 10px 14px; margin-bottom: 14px; font-size: 13px; }
    </style>

    <div class="bbm-dash">
        <?php if ( $pending_count > 0 ) : ?>
        <div class="bbm-alert">
            ⚠️ <strong><?php echo $pending_count; ?></strong> bekleyen üyelik başvurusu var.
            <a href="<?php echo admin_url( 'edit.php?post_type=bbm_member_application' ); ?>">İncele →</a>
        </div>
        <?php endif; ?>

        <div class="bbm-dash-grid">
            <div class="bbm-dash-stat">
                <div class="bbm-dash-num"><?php echo $total_members; ?></div>
                <div class="bbm-dash-lbl">👥 Üye</div>
            </div>
            <div class="bbm-dash-stat green">
                <div class="bbm-dash-num"><?php echo $total_events; ?></div>
                <div class="bbm-dash-lbl">📅 Etkinlik</div>
            </div>
            <div class="bbm-dash-stat orange">
                <div class="bbm-dash-num"><?php echo $total_projects; ?></div>
                <div class="bbm-dash-lbl">🚀 Proje</div>
            </div>
            <div class="bbm-dash-stat blue">
                <div class="bbm-dash-num"><?php echo $total_volunteers; ?></div>
                <div class="bbm-dash-lbl">🤝 Gönüllü</div>
            </div>
            <div class="bbm-dash-stat teal">
                <div class="bbm-dash-num"><?php echo $total_sponsors; ?></div>
                <div class="bbm-dash-lbl">⭐ Destekçi</div>
            </div>
            <div class="bbm-dash-stat rose">
                <div class="bbm-dash-num"><?php echo $total_docs; ?></div>
                <div class="bbm-dash-lbl">📄 Belge</div>
            </div>
        </div>

        <?php if ( $upcoming->have_posts() ) : ?>
        <div class="bbm-dash-section">
            <h4>📅 Yaklaşan Etkinlikler (30 gün)</h4>
            <ul class="bbm-dash-list">
            <?php while ( $upcoming->have_posts() ) : $upcoming->the_post();
                $edate = get_post_meta( get_the_ID(), '_bbm_event_date', true );
                $elocation = get_post_meta( get_the_ID(), '_bbm_event_location', true );
            ?>
                <li>
                    <span>
                        <a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a>
                        <?php if ( $elocation ) echo '<small style="color:#888"> — ' . esc_html( $elocation ) . '</small>'; ?>
                    </span>
                    <span class="bbm-dash-badge bbm-badge-approved"><?php echo $edate ? date_i18n( 'j M', strtotime( $edate ) ) : '–'; ?></span>
                </li>
            <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ( $recent_apps->have_posts() ) : ?>
        <div class="bbm-dash-section">
            <h4>📬 Son Başvurular</h4>
            <ul class="bbm-dash-list">
            <?php while ( $recent_apps->have_posts() ) : $recent_apps->the_post();
                $status = get_post_status();
                $badge_class = $status === 'pending' ? 'bbm-badge-pending' : 'bbm-badge-approved';
                $status_label = $status === 'pending' ? 'Bekliyor' : 'İşlendi';
            ?>
                <li>
                    <a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a>
                    <span class="bbm-dash-badge <?php echo $badge_class; ?>"><?php echo $status_label; ?></span>
                </li>
            <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <p style="margin:0;text-align:right;font-size:11px;color:#999">
            <a href="<?php echo admin_url( 'themes.php?page=bbm-settings' ); ?>">Tema Ayarları</a> ·
            <a href="<?php echo home_url( '/' ); ?>" target="_blank">Siteyi Görüntüle</a>
        </p>
    </div>
    <?php
}

function bbm_admin_dashboard_styles( $hook ) {
    if ( 'index.php' !== $hook ) return;
}
