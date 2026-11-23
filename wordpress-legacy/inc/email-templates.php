<?php
/**
 * Extended Email Templates v4.0
 * Branded HTML templates for all automated notifications
 */

defined('ABSPATH') || exit;

add_action('bbm_membership_approved',    'bbm_email_membership_approved', 10, 1);
add_action('init',                       'bbm_schedule_email_crons');
add_action('bbm_event_reminder_emails',  'bbm_process_event_reminder_emails', 10, 1);
add_action('bbm_renewal_reminder_check', 'bbm_process_renewal_reminders');

function bbm_schedule_email_crons(): void {
    if (!wp_next_scheduled('bbm_renewal_reminder_check')) {
        wp_schedule_event(strtotime('first day of this month 10:00:00'), 'monthly', 'bbm_renewal_reminder_check');
    }
}

/* ── Master Email Builder v4 ── */
function bbm_build_email_v4(array $args): string {
    $primary  = sanitize_hex_color(get_theme_mod('bbm_primary_color',   '#E8435A')) ?: '#E8435A';
    $sec      = sanitize_hex_color(get_theme_mod('bbm_secondary_color', '#2D3561')) ?: '#2D3561';
    $site     = get_bloginfo('name');
    $url      = home_url();
    $logo     = get_site_icon_url(80);
    $year     = date('Y');
    $title    = $args['title']    ?? $site;
    $subtitle = $args['subtitle'] ?? '';
    $greeting = $args['greeting'] ?? '';
    $body     = $args['body']     ?? '';
    $cta_url  = $args['cta_url']  ?? '';
    $cta_text = $args['cta_text'] ?? '';
    $fields   = $args['fields']   ?? [];
    $note     = $args['note']     ?? '';
    $icon     = $args['icon']     ?? '✉️';
    $rows     = '';
    foreach ($fields as $label => $value) {
        if (!$label || $value === null || $value === false) continue;
        $rows .= "<tr><td style='padding:12px 20px;font-weight:700;color:{$sec};width:40%;border-bottom:1px solid #eef2f7;font-size:13px;'>{$label}</td>"
               . "<td style='padding:12px 20px;color:#374151;border-bottom:1px solid #eef2f7;font-size:14px;line-height:1.6;'>{$value}</td></tr>";
    }
    $table  = $rows ? "<table style='width:100%;border-collapse:collapse;margin:24px 0;background:#f9fafb;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;'>{$rows}</table>" : '';
    $cta_btn = ($cta_url && $cta_text)
        ? "<div style='text-align:center;margin:32px 0;'><a href='{$cta_url}' style='display:inline-block;padding:16px 40px;background:linear-gradient(135deg,{$primary} 0%,{$sec} 100%);color:#fff;text-decoration:none;border-radius:50px;font-weight:700;font-size:16px;box-shadow:0 8px 24px rgba(0,0,0,.15);'>{$cta_text} &rarr;</a></div>"
        : '';
    return "<!DOCTYPE html><html lang='tr'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>{$title}</title></head>"
        . "<body style='margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif;background:#f0f4f8;'>"
        . "<div style='display:none;max-height:0;overflow:hidden;'>{$subtitle}</div>"
        . "<div style='max-width:620px;margin:40px auto;padding:0 16px;'>"
        . "<div style='background:linear-gradient(135deg,{$primary} 0%,{$sec} 100%);border-radius:24px 24px 0 0;padding:48px 40px 40px;text-align:center;'>"
        . "<div style='font-size:48px;line-height:1;margin-bottom:16px;'>{$icon}</div>"
        . ($logo ? "<div style='margin-bottom:12px;'><img src='{$logo}' width='52' height='52' style='border-radius:50%;border:3px solid rgba(255,255,255,.4);'></div>" : '')
        . "<h1 style='color:#fff;margin:0 0 8px;font-size:26px;font-weight:800;letter-spacing:-.5px;'>{$title}</h1>"
        . ($subtitle ? "<p style='color:rgba(255,255,255,.8);margin:0;font-size:15px;'>{$subtitle}</p>" : '')
        . "</div>"
        . "<div style='background:#fff;padding:40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;'>"
        . ($greeting ? "<p style='font-size:18px;color:#1f2937;margin:0 0 12px;font-weight:700;'>{$greeting}</p>" : '')
        . ($body     ? "<div style='font-size:15px;color:#4b5563;line-height:1.8;margin-bottom:8px;'>{$body}</div>" : '')
        . $table . $cta_btn
        . ($note ? "<p style='font-size:13px;color:#9ca3af;line-height:1.6;margin:24px 0 0;padding-top:24px;border-top:1px solid #f3f4f6;'>{$note}</p>" : '')
        . "</div>"
        . "<div style='background:#f9fafb;border-radius:0 0 24px 24px;padding:28px 40px;text-align:center;border:1px solid #e5e7eb;border-top:none;'>"
        . "<p style='margin:0 0 6px;font-size:13px;color:#9ca3af;'>Bu e-posta <a href='{$url}' style='color:{$primary};font-weight:600;text-decoration:none;'>{$site}</a> tarafından gönderilmiştir.</p>"
        . "<p style='margin:0;font-size:12px;color:#d1d5db;'>&copy; {$year} {$site} &middot; Tüm hakları saklıdır.</p>"
        . "</div></div></body></html>";
}

/* ── 1. Üyelik Onayı ── */
function bbm_email_membership_approved(int $application_id): void {
    $email   = get_post_meta($application_id, 'email',   true);
    $name    = get_post_meta($application_id, 'name',    true);
    $surname = get_post_meta($application_id, 'surname', true);
    $type    = get_post_meta($application_id, 'type',    true) ?: 'standart';
    if (!is_email($email)) return;
    $full    = esc_html(trim("$name $surname"));
    wp_mail(
        $email,
        sprintf(__('🎉 %s Üyeliğiniz Onaylandı!', 'bitebimuv-dernek'), get_bloginfo('name')),
        bbm_build_email_v4([
            'icon'     => '🎉',
            'title'    => 'Üyeliğiniz Onaylandı!',
            'subtitle' => get_bloginfo('name') . ' ailesine hoş geldiniz',
            'greeting' => "Merhaba {$full},",
            'body'     => '<p>Derneğimize üyelik başvurunuz onaylandı. Artık ailemizin bir parçasısınız! Etkinliklerimize katılabilir, projelerimize katkıda bulunabilirsiniz.</p>',
            'fields'   => [
                'Üye Adı'       => $full,
                'Üyelik Tipi'   => ucfirst(esc_html($type)),
                'Üyelik Tarihi' => current_time('d.m.Y'),
                'Durum'         => '<span style="color:#10b981;font-weight:700;">✓ Aktif</span>',
            ],
            'cta_url'  => home_url('/etkinlikler/'),
            'cta_text' => 'Etkinlikleri Keşfet',
            'note'     => 'Üyeliğinizle ilgili sorularınız için bize ulaşabilirsiniz.',
        ]),
        ['Content-Type: text/html; charset=UTF-8']
    );
}

/* ── 2. Etkinlik Kayıt Onayı (geliştirilmiş) ── */
function bbm_email_event_registration_confirmed(string $email, string $name, int $event_id, int $count = 1): void {
    if (!is_email($email)) return;
    $title    = get_the_title($event_id);
    $date     = get_post_meta($event_id, '_bbm_event_date',     true);
    $location = get_post_meta($event_id, '_bbm_event_location', true);
    $fmt_date = $date ? date_i18n('d F Y, H:i', strtotime($date)) : '';
    $fields   = ['Etkinlik' => esc_html($title), 'Katılımcı' => (string)$count];
    if ($fmt_date) $fields['Tarih ve Saat'] = $fmt_date;
    if ($location) $fields['Konum']        = esc_html($location);
    wp_mail(
        $email,
        sprintf(__('✅ Etkinlik Kaydı Onaylandı: %s', 'bitebimuv-dernek'), $title),
        bbm_build_email_v4([
            'icon'     => '🎟️',
            'title'    => 'Kaydınız Alındı!',
            'subtitle' => esc_html($title),
            'greeting' => 'Merhaba ' . esc_html($name) . ',',
            'body'     => '<p><strong>' . esc_html($title) . '</strong> etkinliğine kaydınız başarıyla tamamlandı. Sizi aramızda görmekten mutluluk duyacağız!</p>',
            'fields'   => $fields,
            'cta_url'  => get_permalink($event_id),
            'cta_text' => 'Etkinlik Detayları',
            'note'     => 'Etkinliğe katılamayacaksanız lütfen önceden bilgi verin.',
        ]),
        ['Content-Type: text/html; charset=UTF-8']
    );
}

/* ── 3. Etkinlik Hatırlatma (WP-Cron tabanlı) ── */
function bbm_process_event_reminder_emails(int $event_id): void {
    $registrations = (array) get_post_meta($event_id, '_bbm_registrations', true);
    $title         = get_the_title($event_id);
    $date          = get_post_meta($event_id, '_bbm_event_date',     true);
    $location      = get_post_meta($event_id, '_bbm_event_location', true);
    $fmt_date      = $date ? date_i18n('d F Y, H:i', strtotime($date)) : '';
    foreach ($registrations as $reg) {
        $email = $reg['email'] ?? '';
        $name  = $reg['name']  ?? '';
        if (!is_email($email)) continue;
        $fields = ['Etkinlik' => esc_html($title)];
        if ($fmt_date) $fields['Tarih'] = $fmt_date;
        if ($location) $fields['Yer']   = esc_html($location);
        wp_mail(
            $email,
            sprintf(__('⏰ Yarın: %s', 'bitebimuv-dernek'), $title),
            bbm_build_email_v4([
                'icon'     => '⏰',
                'title'    => 'Etkinlik Yarın!',
                'subtitle' => 'Hatırlatma bildirimi',
                'greeting' => 'Merhaba ' . esc_html($name) . ',',
                'body'     => '<p><strong>' . esc_html($title) . '</strong> etkinliği yarın! Sizi aramızda görmekten çok mutlu olacağız.</p>',
                'fields'   => $fields,
                'cta_url'  => get_permalink($event_id),
                'cta_text' => 'Etkinlik Bilgileri',
                'note'     => 'Katılamayacaksanız lütfen bizi bilgilendirin.',
            ]),
            ['Content-Type: text/html; charset=UTF-8']
        );
        usleep(200000);
    }
}

add_action('save_post_bbm_event', function(int $post_id): void {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;
    $event_date = get_post_meta($post_id, '_bbm_event_date', true);
    if (!$event_date) return;
    $remind_at = strtotime($event_date) - DAY_IN_SECONDS;
    if ($remind_at <= time()) return;
    $hook     = 'bbm_event_reminder_emails';
    $existing = wp_next_scheduled($hook, [$post_id]);
    if ($existing) wp_unschedule_event($existing, $hook, [$post_id]);
    wp_schedule_single_event($remind_at, $hook, [$post_id]);
}, 20);

/* ── 4. Üyelik Yenileme Hatırlatması ── */
function bbm_process_renewal_reminders(): void {
    $members = get_posts([
        'post_type'      => 'bbm_member',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [[
            'key'     => '_bbm_membership_expiry',
            'value'   => [date('Y-m-d', strtotime('+29 days')), date('Y-m-d', strtotime('+31 days'))],
            'compare' => 'BETWEEN',
            'type'    => 'DATE',
        ]],
    ]);
    foreach ($members as $member) {
        $email  = get_post_meta($member->ID, '_bbm_member_email', true);
        $name   = get_the_title($member->ID);
        $expiry = get_post_meta($member->ID, '_bbm_membership_expiry', true);
        if (!is_email($email)) continue;
        $key = '_bbm_renewal_notified_' . date('Y-m');
        if (get_post_meta($member->ID, $key, true)) continue;
        wp_mail(
            $email,
            __('🔔 Üyeliğinizin Yenileme Zamanı Yaklaşıyor', 'bitebimuv-dernek'),
            bbm_build_email_v4([
                'icon'     => '🔔',
                'title'    => 'Üyelik Yenileme',
                'subtitle' => 'Üyeliğiniz yakında sona eriyor',
                'greeting' => 'Merhaba ' . esc_html($name) . ',',
                'body'     => '<p>Dernek üyeliğinizin süresi yakında dolmaktadır. Üyeliğinizi yenileyerek bizimle kalmaya devam etmenizi diliyoruz!</p>',
                'fields'   => [
                    'Üye'            => esc_html($name),
                    'Son Geçerlilik' => $expiry ? date_i18n('d F Y', strtotime($expiry)) : '—',
                ],
                'cta_url'  => home_url('/uyelik/'),
                'cta_text' => 'Üyeliği Yenile',
                'note'     => 'Üyeliğiniz sona erdiğinde etkinlik önceliğiniz ve üye ayrıcalıklarınız askıya alınacaktır.',
            ]),
            ['Content-Type: text/html; charset=UTF-8']
        );
        update_post_meta($member->ID, $key, '1');
    }
}

/* ── 5. Hoş Geldiniz E-postası ── */
add_action('bbm_member_created', function(int $member_id): void {
    $email = get_post_meta($member_id, '_bbm_member_email', true);
    $name  = get_the_title($member_id);
    if (!is_email($email)) return;
    $site  = get_bloginfo('name');
    wp_mail(
        $email,
        sprintf(__('👋 %s\'e Hoş Geldiniz!', 'bitebimuv-dernek'), $site),
        bbm_build_email_v4([
            'icon'     => '👋',
            'title'    => 'Hoş Geldiniz!',
            'subtitle' => "{$site} ailesinin yeni üyesi",
            'greeting' => 'Merhaba ' . esc_html($name) . ',',
            'body'     => "<p>Derneğimize katıldığınız için çok mutluyuz! Etkinliklere göz atabilir, projeleri inceleyebilir ve gönüllü olmak için başvurabilirsiniz.</p>",
            'fields'   => [
                'Üye'           => esc_html($name),
                'Üyelik Tarihi' => current_time('d.m.Y'),
                'E-posta'       => esc_html($email),
            ],
            'cta_url'  => home_url('/etkinlikler/'),
            'cta_text' => 'Etkinliklere Katıl',
            'note'     => 'Herhangi bir sorunuz olursa bize ulaşmaktan çekinmeyin.',
        ]),
        ['Content-Type: text/html; charset=UTF-8']
    );
});

/* ── 6. Bülten E-postası Builder ── */
function bbm_build_newsletter(array $args): string {
    $primary    = sanitize_hex_color(get_theme_mod('bbm_primary_color',   '#E8435A')) ?: '#E8435A';
    $sec        = sanitize_hex_color(get_theme_mod('bbm_secondary_color', '#2D3561')) ?: '#2D3561';
    $site       = get_bloginfo('name');
    $url        = home_url();
    $logo       = get_site_icon_url(80);
    $year       = date('Y');
    $issue      = $args['issue']    ?? date('F Y');
    $headline   = $args['headline'] ?? '';
    $intro_text = $args['intro']    ?? '';
    $articles   = $args['articles'] ?? [];
    $events     = $args['events']   ?? [];
    $unsub_url  = $args['unsubscribe_url'] ?? home_url('/bulten-iptal/');
    $articles_html = '';
    foreach ($articles as $article) {
        $thumb   = $article['thumb']   ?? '';
        $t       = $article['title']   ?? '';
        $excerpt = $article['excerpt'] ?? '';
        $a_url   = $article['url']     ?? '#';
        $img_td  = $thumb ? "<td style='width:140px;vertical-align:top;'><img src='{$thumb}' width='140' style='display:block;height:100px;object-fit:cover;border-radius:8px 0 0 8px;'></td>" : '';
        $articles_html .= "<table style='width:100%;border-collapse:collapse;margin-bottom:20px;background:#f9fafb;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;'><tr>{$img_td}<td style='padding:16px;vertical-align:top;'><h3 style='margin:0 0 6px;font-size:15px;color:#1f2937;'><a href='{$a_url}' style='color:inherit;text-decoration:none;'>{$t}</a></h3><p style='margin:0 0 8px;font-size:13px;color:#6b7280;line-height:1.5;'>{$excerpt}</p><a href='{$a_url}' style='font-size:13px;color:{$primary};font-weight:600;text-decoration:none;'>Devamını oku &rarr;</a></td></tr></table>";
    }
    $events_html = '';
    if ($events) {
        $events_html = "<h2 style='font-size:17px;color:#1f2937;margin:28px 0 14px;font-weight:800;'>📅 Yaklaşan Etkinlikler</h2>";
        foreach ($events as $ev) {
            $events_html .= "<div style='padding:14px;margin-bottom:10px;background:#f9fafb;border-radius:10px;border-left:4px solid {$primary};'><strong style='color:#1f2937;font-size:14px;'><a href='{$ev['url']}' style='color:inherit;text-decoration:none;'>{$ev['title']}</a></strong>" . (!empty($ev['date']) ? " <span style='color:#6b7280;font-size:13px;margin-left:8px;'>{$ev['date']}</span>" : '') . (!empty($ev['location']) ? "<div style='font-size:12px;color:#6b7280;margin-top:3px;'>📍 {$ev['location']}</div>" : '') . "</div>";
        }
    }
    return "<!DOCTYPE html><html lang='tr'><head><meta charset='UTF-8'><title>{$headline}</title></head><body style='margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif;background:#f0f4f8;'><div style='max-width:640px;margin:32px auto;padding:0 16px;'><div style='background:linear-gradient(135deg,{$primary} 0%,{$sec} 100%);border-radius:24px 24px 0 0;padding:40px;text-align:center;'>" . ($logo ? "<img src='{$logo}' width='56' height='56' style='border-radius:50%;border:3px solid rgba(255,255,255,.4);margin-bottom:14px;display:block;margin-left:auto;margin-right:auto;'>" : '') . "<div style='color:rgba(255,255,255,.7);font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;'>{$site} Bülteni &middot; {$issue}</div><h1 style='color:#fff;margin:0;font-size:24px;font-weight:800;line-height:1.2;'>{$headline}</h1></div><div style='background:#fff;padding:36px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;'>" . ($intro_text ? "<p style='font-size:15px;color:#374151;line-height:1.8;margin:0 0 28px;'>{$intro_text}</p>" : '') . "{$articles_html}{$events_html}</div><div style='background:#f9fafb;border-radius:0 0 24px 24px;padding:24px 36px;text-align:center;border:1px solid #e5e7eb;border-top:none;'><p style='margin:0 0 6px;font-size:13px;color:#6b7280;'>Bu bülteni <a href='{$url}' style='color:{$primary};font-weight:600;text-decoration:none;'>{$site}</a> gönderdi.</p><p style='margin:0;font-size:12px;color:#9ca3af;'><a href='{$unsub_url}' style='color:#9ca3af;'>Aboneliği iptal et</a> &middot; &copy; {$year} {$site}</p></div></div></body></html>";
}
