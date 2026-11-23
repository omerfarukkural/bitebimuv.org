<?php
/**
 * Template Name: Belgeler Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header(
        get_the_title() ?: __( 'Belgeler', 'bitebimuv' ),
        get_the_excerpt() ?: __( 'Tüzük, faaliyet raporları ve kararlar', 'bitebimuv' )
    ); ?>

    <section class="bbm-section bbm-documents-page">
        <div class="bbm-container">

            <!-- Type Filter Tabs -->
            <?php
            $doc_types = get_terms( [ 'taxonomy' => 'bbm_document_type', 'hide_empty' => true ] );
            if ( $doc_types && ! is_wp_error( $doc_types ) ) :
            ?>
            <div class="bbm-docs-filter" role="navigation" aria-label="<?php esc_attr_e( 'Belge filtrele', 'bitebimuv' ); ?>">
                <button class="bbm-filter-tab active" data-type=""><?php _e( 'Tümü', 'bitebimuv' ); ?></button>
                <?php foreach ( $doc_types as $dt ) : ?>
                <button class="bbm-filter-tab" data-type="<?php echo esc_attr( $dt->slug ); ?>"><?php echo esc_html( $dt->name ); ?></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Year Filter -->
            <?php
            $years = [];
            $all_docs_for_years = get_posts( [ 'post_type' => 'bbm_document', 'posts_per_page' => -1, 'fields' => 'ids' ] );
            foreach ( $all_docs_for_years as $did ) {
                $y = get_post_meta( $did, '_bbm_document_year', true );
                if ( $y ) $years[] = (int) $y;
            }
            $years = array_unique( $years );
            rsort( $years );
            if ( $years ) :
            ?>
            <div class="bbm-docs-years">
                <label for="bbm-doc-year"><?php _e( 'Yıl:', 'bitebimuv' ); ?></label>
                <select id="bbm-doc-year" class="bbm-select">
                    <option value=""><?php _e( 'Tüm Yıllar', 'bitebimuv' ); ?></option>
                    <?php foreach ( $years as $y ) : ?>
                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Documents Grid -->
            <?php
            $docs_q = new WP_Query( [
                'post_type'      => 'bbm_document',
                'post_status'    => 'publish',
                'posts_per_page' => 20,
                'orderby'        => [ 'meta_value_num' => 'DESC', 'title' => 'ASC' ],
                'meta_key'       => '_bbm_document_year',
            ] );
            ?>
            <div class="bbm-docs-grid" id="bbm-docs-grid">
            <?php if ( $docs_q->have_posts() ) :
                while ( $docs_q->have_posts() ) : $docs_q->the_post();
                    $file_url   = get_post_meta( get_the_ID(), '_bbm_document_file_url', true );
                    $file_size  = get_post_meta( get_the_ID(), '_bbm_document_file_size', true );
                    $doc_year   = get_post_meta( get_the_ID(), '_bbm_document_year', true );
                    $doc_lang   = get_post_meta( get_the_ID(), '_bbm_document_language', true );
                    $dl_count   = (int) get_post_meta( get_the_ID(), '_bbm_document_download_count', true );
                    $doc_types_assigned = wp_get_post_terms( get_the_ID(), 'bbm_document_type' );
                    $doc_type_slug = $doc_types_assigned && ! is_wp_error( $doc_types_assigned ) ? $doc_types_assigned[0]->slug : '';
                    $ext = $file_url ? strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) ) : 'PDF';
                    $icon_map = [ 'PDF' => '📄', 'DOC' => '📝', 'DOCX' => '📝', 'XLS' => '📊', 'XLSX' => '📊', 'PPT' => '📋', 'PPTX' => '📋', 'ZIP' => '🗜' ];
                    $icon = $icon_map[ $ext ] ?? '📄';
                ?>
                <div class="bbm-doc-card" data-type="<?php echo esc_attr( $doc_type_slug ); ?>" data-year="<?php echo esc_attr( $doc_year ); ?>">
                    <div class="bbm-doc-card__icon"><?php echo $icon; ?></div>
                    <div class="bbm-doc-card__body">
                        <h3 class="bbm-doc-card__title"><?php the_title(); ?></h3>
                        <p class="bbm-doc-card__excerpt"><?php the_excerpt(); ?></p>
                        <div class="bbm-doc-card__meta">
                            <?php if ( $doc_year ) : ?>
                            <span>📅 <?php echo esc_html( $doc_year ); ?></span>
                            <?php endif; ?>
                            <?php if ( $file_size ) : ?>
                            <span>💾 <?php echo esc_html( $file_size ); ?></span>
                            <?php endif; ?>
                            <?php if ( $doc_lang ) : ?>
                            <span><?php echo $doc_lang === 'en' ? '🇬🇧 EN' : '🇹🇷 TR'; ?></span>
                            <?php endif; ?>
                            <span title="<?php esc_attr_e( 'İndirme sayısı', 'bitebimuv' ); ?>">⬇ <?php echo $dl_count; ?></span>
                        </div>
                    </div>
                    <?php if ( $file_url ) : ?>
                    <a href="<?php echo esc_url( $file_url ); ?>"
                       class="bbm-doc-card__download bbm-btn bbm-btn--outline bbm-btn--sm"
                       target="_blank" rel="noopener"
                       data-doc-id="<?php echo get_the_ID(); ?>"
                       download
                       aria-label="<?php echo esc_attr( sprintf( __( '%s belgesini indir', 'bitebimuv' ), get_the_title() ) ); ?>">
                        <?php _e( 'İndir', 'bitebimuv' ); ?> <?php echo esc_html( $ext ); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endwhile; wp_reset_postdata();
            else : ?>
                <div class="bbm-empty-state">
                    <div class="bbm-empty-state__icon">📂</div>
                    <h3><?php _e( 'Henüz belge eklenmedi', 'bitebimuv' ); ?></h3>
                    <p><?php _e( 'Tüzük, faaliyet raporu ve diğer belgeler yakında eklenecek.', 'bitebimuv' ); ?></p>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer();
