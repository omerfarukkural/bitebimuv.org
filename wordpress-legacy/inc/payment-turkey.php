<?php
/**
 * Turkish Payment Integration — iyzico + QR/IBAN support
 */

defined('ABSPATH') || exit;

class BBM_Payment_Turkey {

    private static ?self $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_bbm_init_payment',            [$this, 'handle_init_payment']);
        add_action('wp_ajax_nopriv_bbm_init_payment',     [$this, 'handle_init_payment']);
        add_action('wp_ajax_bbm_payment_callback',        [$this, 'handle_payment_callback']);
        add_action('wp_ajax_nopriv_bbm_payment_callback', [$this, 'handle_payment_callback']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings(): void {
        register_setting('bbm_payment_settings', 'bbm_iyzico_api_key');
        register_setting('bbm_payment_settings', 'bbm_iyzico_secret_key');
        register_setting('bbm_payment_settings', 'bbm_iyzico_sandbox');
        register_setting('bbm_payment_settings', 'bbm_membership_prices');
    }

    private function get_api_key(): string {
        return (string) get_option('bbm_iyzico_api_key', '');
    }

    private function get_secret_key(): string {
        return (string) get_option('bbm_iyzico_secret_key', '');
    }

    private function is_sandbox(): bool {
        return (bool) get_option('bbm_iyzico_sandbox', true);
    }

    private function get_base_url(): string {
        return $this->is_sandbox()
            ? 'https://sandbox-api.iyzipay.com'
            : 'https://api.iyzipay.com';
    }

    private function get_membership_price(string $type): float {
        $prices   = (array) get_option('bbm_membership_prices', []);
        $defaults = ['standard' => 250.00, 'student' => 100.00, 'premium' => 500.00];
        return (float) ($prices[$type] ?? $defaults[$type] ?? 250.00);
    }

    private function generate_auth_header(string $uri, string $body): string {
        $rnd   = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);
        $ts    = (string) time() . '000';
        $hash  = base64_encode(hash('sha256', $this->get_api_key() . $rnd . $ts . $uri . $body . $this->get_secret_key(), true));
        return "IYZWSv2 authVersion=1&clientVersion=PHP/1.0.0&apiKey={$this->get_api_key()}&randomKey={$rnd}&timestamp={$ts}&signature={$hash}";
    }

    private function request(string $endpoint, array $data): array {
        $url  = $this->get_base_url() . $endpoint;
        $body = wp_json_encode($data);
        $response = wp_remote_post($url, [
            'body'    => $body,
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => $this->generate_auth_header($endpoint, $body),
            ],
            'timeout' => 30,
        ]);
        if (is_wp_error($response)) {
            return ['status' => 'failure', 'errorMessage' => $response->get_error_message()];
        }
        $decoded = json_decode(wp_remote_retrieve_body($response), true);
        return is_array($decoded) ? $decoded : ['status' => 'failure', 'errorMessage' => 'Geçersiz yanıt'];
    }

    public function handle_init_payment(): void {
        check_ajax_referer('bbm-nonce', 'nonce');
        if (!$this->get_api_key() || !$this->get_secret_key()) {
            wp_send_json_error(['message' => 'Ödeme sistemi henüz yapılandırılmamış.']);
        }
        $type    = sanitize_text_field($_POST['membership_type'] ?? 'standard');
        $name    = sanitize_text_field($_POST['name']    ?? '');
        $surname = sanitize_text_field($_POST['surname'] ?? '');
        $email   = sanitize_email($_POST['email']        ?? '');
        $phone   = sanitize_text_field($_POST['phone']   ?? '');
        $city    = sanitize_text_field($_POST['city']    ?? '');
        $address = sanitize_textarea_field($_POST['address'] ?? '');
        $app_id  = absint($_POST['application_id'] ?? 0);
        if (!$name || !$surname || !is_email($email)) {
            wp_send_json_error(['message' => 'Ad, soyad ve e-posta zorunludur.']);
        }
        $price     = $this->get_membership_price($type);
        $price_str = number_format($price, 2, '.', '');
        $conv_id   = 'BBM-MBR-' . ($app_id ?: time());
        $payload = [
            'locale'              => 'tr',
            'conversationId'      => $conv_id,
            'price'               => $price_str,
            'paidPrice'           => $price_str,
            'currency'            => 'TRY',
            'basketId'            => 'BBM-' . time(),
            'paymentGroup'        => 'SUBSCRIPTION',
            'callbackUrl'         => admin_url('admin-ajax.php?action=bbm_payment_callback&nonce=' . wp_create_nonce('bbm-payment-callback')),
            'enabledInstallments' => [1, 2, 3, 6, 9],
            'buyer' => [
                'id'                  => 'USR-' . md5($email),
                'name'                => $name,
                'surname'             => $surname,
                'gsmNumber'           => preg_replace('/[^0-9+]/', '', $phone),
                'email'               => $email,
                'identityNumber'      => '11111111111',
                'registrationAddress' => $address ?: $city ?: 'Türkiye',
                'ip'                  => bbm_get_client_ip(),
                'city'                => $city ?: 'İstanbul',
                'country'             => 'Turkey',
            ],
            'shippingAddress' => ['contactName' => "$name $surname", 'city' => $city ?: 'İstanbul', 'country' => 'Turkey', 'address' => $address ?: 'Türkiye'],
            'billingAddress'  => ['contactName' => "$name $surname", 'city' => $city ?: 'İstanbul', 'country' => 'Turkey', 'address' => $address ?: 'Türkiye'],
            'basketItems' => [[
                'id'        => 'MEMBERSHIP-' . strtoupper($type),
                'name'      => get_bloginfo('name') . ' Üyelik — ' . ucfirst($type),
                'category1' => 'Üyelik',
                'itemType'  => 'VIRTUAL',
                'price'     => $price_str,
            ]],
        ];
        $result = $this->request('/payment/iyzipos/checkoutform/initialize/auth/ecom', $payload);
        if (($result['status'] ?? '') !== 'success') {
            wp_send_json_error(['message' => 'Ödeme formu başlatılamadı: ' . esc_html($result['errorMessage'] ?? 'Bilinmeyen hata')]);
        }
        set_transient('bbm_pending_payment_' . $conv_id, [
            'application_id'  => $app_id,
            'membership_type' => $type,
            'email'           => $email,
            'name'            => "$name $surname",
            'amount'          => $price,
        ], HOUR_IN_SECONDS);
        wp_send_json_success([
            'token'     => $result['token'] ?? '',
            'form_html' => $result['checkoutFormContent'] ?? '',
            'amount'    => $price_str,
        ]);
    }

    public function handle_payment_callback(): void {
        check_ajax_referer('bbm-payment-callback', 'nonce');
        $token = sanitize_text_field($_POST['token'] ?? '');
        if (!$token) wp_die(__('Geçersiz ödeme token.', 'bitebimuv-dernek'));
        $result = $this->request('/payment/iyzipos/checkoutform/auth/ecom/detail', ['locale' => 'tr', 'token' => $token]);
        if (($result['paymentStatus'] ?? '') !== 'SUCCESS') {
            wp_die(sprintf(__('Ödeme başarısız: %s', 'bitebimuv-dernek'), esc_html($result['errorMessage'] ?? 'Bilinmeyen hata')));
        }
        $conv_id = $result['conversationId'] ?? '';
        $pending = get_transient('bbm_pending_payment_' . $conv_id);
        if ($pending) {
            delete_transient('bbm_pending_payment_' . $conv_id);
            $log   = (array) get_option('bbm_payment_log', []);
            $log[] = [
                'conversation_id' => $conv_id,
                'payment_id'      => $result['paymentId'] ?? '',
                'application_id'  => $pending['application_id'],
                'email'           => $pending['email'],
                'name'            => $pending['name'],
                'amount'          => $pending['amount'],
                'type'            => $pending['membership_type'],
                'date'            => current_time('mysql'),
                'status'          => 'paid',
            ];
            update_option('bbm_payment_log', $log);
            if ($pending['application_id']) {
                update_post_meta($pending['application_id'], '_bbm_payment_status', 'paid');
                update_post_meta($pending['application_id'], '_bbm_payment_id',     $result['paymentId'] ?? '');
                update_post_meta($pending['application_id'], '_bbm_payment_date',   current_time('mysql'));
                update_post_meta($pending['application_id'], '_bbm_payment_amount', $pending['amount']);
            }
        }
        wp_redirect(home_url('/uyelik/?odeme=basarili'));
        exit;
    }
}

BBM_Payment_Turkey::get_instance();

function bbm_render_qr_payment_info(float $amount = 0.00, string $type = 'standard'): string {
    $iban   = get_theme_mod('bbm_bank_iban',   '');
    $bank   = get_theme_mod('bbm_bank_name',   'Ziraat Bankası');
    $holder = get_theme_mod('bbm_bank_holder', get_bloginfo('name'));
    if (!$iban) return '';
    $price = $amount > 0 ? number_format($amount, 2, ',', '.') . ' ₺' : '';
    $desc  = esc_html(get_bloginfo('name')) . ' ' . esc_html(ucfirst($type)) . ' Üyelik';
    return sprintf(
        '<div class="bbm-qr-payment">
            <h4 class="bbm-qr-payment__title">%s</h4>
            <div class="bbm-qr-payment__fields">
                <div class="bbm-qr-payment__row"><span>Banka:</span><strong>%s</strong></div>
                <div class="bbm-qr-payment__row"><span>Hesap Sahibi:</span><strong>%s</strong></div>
                <div class="bbm-qr-payment__row"><span>IBAN:</span><strong class="bbm-iban">%s</strong></div>
                %s
                <div class="bbm-qr-payment__row"><span>Açıklama:</span><strong>%s</strong></div>
            </div>
            <button type="button" class="bbm-btn bbm-btn--outline bbm-copy-iban" data-iban="%s">IBAN\'ı Kopyala</button>
        </div>',
        esc_html__('Havale/EFT ile Ödeme', 'bitebimuv-dernek'),
        esc_html($bank),
        esc_html($holder),
        esc_html($iban),
        $price ? "<div class='bbm-qr-payment__row'><span>Tutar:</span><strong>{$price}</strong></div>" : '',
        esc_html($desc),
        esc_attr($iban)
    );
}
