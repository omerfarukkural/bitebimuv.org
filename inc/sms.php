<?php
/**
 * Netgsm SMS Integration — event reminders, membership confirmations
 */

defined('ABSPATH') || exit;

class BBM_SMS {

    private static ?self $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('bbm_membership_approved', [$this, 'send_membership_approved_sms'], 10, 2);
        add_action('bbm_event_registered',    [$this, 'send_event_registered_sms'],    10, 3);
        add_action('bbm_event_reminder_sms',  [$this, 'send_event_reminder_bulk'],     10, 1);
        add_action('admin_init',              [$this, 'register_settings']);
        if (!wp_next_scheduled('bbm_daily_event_reminders')) {
            wp_schedule_event(strtotime('tomorrow 09:00:00'), 'daily', 'bbm_daily_event_reminders');
        }
        add_action('bbm_daily_event_reminders', [$this, 'process_daily_reminders']);
    }

    public function register_settings(): void {
        register_setting('bbm_sms_settings', 'bbm_netgsm_usercode');
        register_setting('bbm_sms_settings', 'bbm_netgsm_password');
        register_setting('bbm_sms_settings', 'bbm_netgsm_msgheader');
        register_setting('bbm_sms_settings', 'bbm_sms_enabled');
    }

    private function is_enabled(): bool {
        return (bool) get_option('bbm_sms_enabled', false)
            && !empty(get_option('bbm_netgsm_usercode'))
            && !empty(get_option('bbm_netgsm_password'));
    }

    private function normalize_phone(string $phone): string {
        $c = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($c, '90') && strlen($c) === 12) return $c;
        if (str_starts_with($c, '0')  && strlen($c) === 11) return '90' . substr($c, 1);
        if (strlen($c) === 10) return '90' . $c;
        return $c;
    }

    public function send(string $to, string $message): bool {
        if (!$this->is_enabled()) return false;
        $phone = $this->normalize_phone($to);
        if (strlen($phone) < 11) return false;
        $usercode  = (string) get_option('bbm_netgsm_usercode', '');
        $password  = (string) get_option('bbm_netgsm_password', '');
        $header    = (string) get_option('bbm_netgsm_msgheader', 'BITEBIMUV');
        $msg_enc   = mb_convert_encoding(mb_substr($message, 0, 160), 'ISO-8859-9', 'UTF-8');
        $xml = "<?xml version='1.0' encoding='ISO-8859-9'?>
<mainbody>
  <header>
    <company>Netgsm</company>
    <usercode>{$usercode}</usercode>
    <password>{$password}</password>
    <startdate></startdate>
    <stopdate></stopdate>
    <type>1:n</type>
    <msgheader>{$header}</msgheader>
  </header>
  <body>
    <msg><![CDATA[{$msg_enc}]]></msg>
    <no>{$phone}</no>
  </body>
</mainbody>";
        $response = wp_remote_post('https://api.netgsm.com.tr/sms/send/xml', [
            'body'    => $xml,
            'headers' => ['Content-Type' => 'text/xml; charset=ISO-8859-9'],
            'timeout' => 15,
        ]);
        if (is_wp_error($response)) {
            error_log('[BBM SMS] ' . $response->get_error_message());
            return false;
        }
        return str_starts_with(trim(wp_remote_retrieve_body($response)), '00');
    }

    public function send_bulk(array $recipients, string $message): int {
        $sent = 0;
        foreach ($recipients as $phone) {
            if ($this->send($phone, $message)) {
                $sent++;
                usleep(100000);
            }
        }
        return $sent;
    }

    public function send_membership_approved_sms(int $member_id, string $phone): void {
        $name = get_the_title($member_id);
        $this->send($phone, sprintf(
            'Sayin %s, BiteBiMuv dernegine uyeliginiz onaylandi. Hosgeldiniz! bitebimuv.org',
            $name
        ));
    }

    public function send_event_registered_sms(string $phone, string $event_title, string $event_date): void {
        $this->send($phone, sprintf(
            'BiteBiMuv: "%s" etkinligine kaydiniz alindi. Tarih: %s. Goresuruz!',
            mb_substr($event_title, 0, 30),
            $event_date
        ));
    }

    public function send_event_reminder_bulk(int $event_id): void {
        $registrations = (array) get_post_meta($event_id, '_bbm_registrations', true);
        $title         = get_the_title($event_id);
        $date          = get_post_meta($event_id, '_bbm_event_date', true);
        $time_str      = $date ? date('H:i', strtotime($date)) : '';
        foreach ($registrations as $reg) {
            $phone = $reg['phone'] ?? '';
            if (!$phone) continue;
            $this->send($phone, sprintf(
                'BiteBiMuv Hatirlatma: "%s" etkinligi yarin %s. Sizi bekliyoruz!',
                mb_substr($title, 0, 25),
                $time_str
            ));
        }
    }

    public function process_daily_reminders(): void {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $events   = new WP_Query([
            'post_type'      => 'bbm_event',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => [['key' => '_bbm_event_date', 'value' => $tomorrow, 'compare' => 'LIKE']],
        ]);
        foreach ($events->posts as $event) {
            if (!get_post_meta($event->ID, '_bbm_sms_reminder_sent', true)) {
                do_action('bbm_event_reminder_sms', $event->ID);
                update_post_meta($event->ID, '_bbm_sms_reminder_sent', '1');
            }
        }
    }
}

BBM_SMS::get_instance();

function bbm_send_sms(string $phone, string $message): bool {
    return BBM_SMS::get_instance()->send($phone, $message);
}
