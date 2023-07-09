<?php
// Custom query to retrieve bio data
$args = array(
    'post_type' => 'ta_bio_data',
    'posts_per_page' => -1
);
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    switch ($sort) {
        case 'date':
            $args['orderby'] = 'post_date'; // Sort by published date
        break;
        case 'date':
            $args['orderby'] = 'title'; // Sort by title
        break;
    }
}

if (isset($_GET['sortby'])) {
    $sortby = $_GET['sortby'];
    switch ($sortby) {
        case 'asc':
            $args['order'] = 'ASC'; // Order in Ascending order
        break;
        case 'desc':
            $args['order'] = 'DESC'; // Order in descending order
        break;
    }
}

if (isset($_GET['filter-category']) && !empty($_GET['filter-category'])) {
    $category_filter = sanitize_text_field($_GET['filter-category']);

    $args['tax_query'] = array(
        array(
            'taxonomy' => 'ta_occupation_type',
            'field' => 'term_id',
            'terms' => $category_filter,
        ),
    );
}

$bio_data_query = new WP_Query($args);
if ($bio_data_query->have_posts()) :
?>
 <form class="bio-filter-form">
    <h4>Filter</h4>
    <div class="filter-group">
        <label for="filter-sort">Sort By:</label>
        <select id="filter-sort" name="sort">
            <option value="">Select Sort By</option>
            <option value="date">Published Date</option>
            <option value="title">Title</option>
        </select>
    </div>
    <div class="filter-group">
    <label for="filter-sort">Sort Type:</label>
        <select id="filter-sort-by" name="sortby">
            <option value="">Select Sort Type</option>    
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
    </div>
    <div class="filter-group">
        <label for="filter-category">Occupation Type :</label>
        <select id="filter-category" name="filter-category">
            <option value="">All</option>
            <?php
            $ta_occupation_type_cats = get_terms(array(
                'taxonomy' => 'ta_occupation_type',
                'hide_empty' => true,
            ));

            if (!empty($ta_occupation_type_cats) && !is_wp_error($ta_occupation_type_cats)) {
                foreach ($ta_occupation_type_cats as $ta_occupation_type_cat) {
                    echo '<option value="' . $ta_occupation_type_cat->term_id . '">' . $ta_occupation_type_cat->name . '</option>';
                }
            }
            ?>
        </select>
    </div>
    <div class="filter-group">
        <button type="submit">Apply Filters</button>
    </div>
</form>
<div class="bio-list-wrap">
    <h4>Bio Lists</h4>
    <form class="bio-eport-to-csv" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="export_custom_posts_csv">
            <button type="submit">Export to CSV</button>
    </form>
    <ul class="bio-list">
        <?php while ($bio_data_query->have_posts()) : $bio_data_query->the_post(); ?>
        <?php $bio_data =  get_post_meta( get_the_ID(), 'bio_data', true );?>
            <li class="bio-item">
                <h2 class="post-title"><?php the_title(); ?></h2>
                <p class="bio-content"><?php echo isset($bio_data['basic']['description'])?$bio_data['basic']['description']:''; ?></p>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
        <?php
        wp_reset_postdata();
else :
    echo 'No posts found.';
endif;
?>