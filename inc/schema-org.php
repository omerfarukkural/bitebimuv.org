<?php
/**
 * BiteBiMuv — Schema.org Structured Data (JSON-LD)
 */

function bbm_schema_organization(): array {
    return [
        '@context' => 'https://schema.org',
        '@type'    => 'NGO',
        '@id'      => home_url('/#organization'),
        'name'     => get_bloginfo('name'),
        'url'      => home_url(),
        'logo'     => [
            '@type' => 'ImageObject',
            'url'   => get_site_icon_url(512) ?: '',
        ],
        'description'    => get_bloginfo('description'),
        'email'          => get_theme_mod('bbm_contact_info_email', ''),
        'telephone'      => get_theme_mod('bbm_contact_info_phone', ''),
        'address'        => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Türkiye',
            'streetAddress'   => get_theme_mod('bbm_contact_info_address', ''),
        ],
        'sameAs' => array_filter( [
            get_theme_mod('bbm_social_facebook',  ''),
            get_theme_mod('bbm_social_instagram', ''),
            get_theme_mod('bbm_social_twitter',   ''),
            get_theme_mod('bbm_social_youtube',   ''),
            get_theme_mod('bbm_social_linkedin',  ''),
        ] ),
    ];
}

function bbm_output_schema(): void {
    $schemas = [];

    // Always output Organization schema
    $schemas[] = bbm_schema_organization();

    // BreadcrumbList on all pages
    if ( ! is_front_page() ) {
        $list = [ [ '@type'=>'ListItem','position'=>1,'name'=>get_bloginfo('name'),'item'=>home_url() ] ];
        if ( is_singular() ) {
            $list[] = [ '@type'=>'ListItem','position'=>2,'name'=>get_the_title(),'item'=>get_permalink() ];
        } elseif ( is_post_type_archive() ) {
            $list[] = [ '@type'=>'ListItem','position'=>2,'name'=>post_type_archive_title('',false),'item'=>get_post_type_archive_link( get_query_var('post_type') ) ];
        }
        $schemas[] = [ '@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>$list ];
    }

    // Event schema
    if ( is_singular('bbm_event') ) {
        $id     = get_the_ID();
        $date   = get_post_meta($id,'_bbm_event_date',true);
        $time   = get_post_meta($id,'_bbm_event_time',true) ?: '00:00';
        $online = get_post_meta($id,'_bbm_event_is_online',true);
        $schemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Event',
            'name'        => get_the_title(),
            'description' => get_the_excerpt(),
            'image'       => get_the_post_thumbnail_url($id,'bbm-card') ?: '',
            'startDate'   => $date ? "{$date}T{$time}:00" : '',
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => $online
                ? 'https://schema.org/OnlineEventAttendanceMode'
                : 'https://schema.org/OfflineEventAttendanceMode',
            'location'    => $online ? [
                '@type' => 'VirtualLocation',
                'url'   => get_post_meta($id,'_bbm_event_online_url',true) ?: get_permalink(),
            ] : [
                '@type'   => 'Place',
                'name'    => get_post_meta($id,'_bbm_event_location',true) ?: '',
                'address' => get_post_meta($id,'_bbm_event_address',true) ?: '',
            ],
            'organizer' => [
                '@type' => 'Organization',
                '@id'   => home_url('/#organization'),
                'name'  => get_bloginfo('name'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => get_post_meta($id,'_bbm_event_price',true) ?: '0',
                'priceCurrency' => 'TRY',
                'availability'  => 'https://schema.org/InStock',
                'url'           => get_permalink(),
            ],
        ];
    }

    // Article schema for posts
    if ( is_singular('post') ) {
        $schemas[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'Article',
            'headline'        => get_the_title(),
            'image'           => get_the_post_thumbnail_url(get_the_ID(),'bbm-hero') ?: '',
            'datePublished'   => get_the_date('c'),
            'dateModified'    => get_the_modified_date('c'),
            'author'          => [ '@type'=>'Person','name'=>get_the_author() ],
            'publisher'       => [
                '@type' => 'Organization',
                '@id'   => home_url('/#organization'),
                'name'  => get_bloginfo('name'),
                'logo'  => [ '@type'=>'ImageObject','url'=>get_site_icon_url(512) ?: '' ],
            ],
            'description'     => get_the_excerpt(),
            'mainEntityOfPage'=> get_permalink(),
        ];
    }

    foreach ( $schemas as $schema ) {
        echo "<script type='application/ld+json'>" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'bbm_output_schema' );
