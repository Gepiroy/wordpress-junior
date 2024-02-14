<?php
/**
 * Title: Posts
 * Slug: juniortheme/posts
 */
?>


<?php
writePosts2('Посты без темы:','cat=1');
writePosts2('Посты на темы экономики:','cat=7');

function writePosts($req){
    echo '<h3>Посты ('.$req.'):<h3>';
    $query = new WP_Query( $req );
    if( $query->have_posts() ){
        while( $query->have_posts() ){
            $query->the_post();
            ?>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_content(); ?>
            <?php
        }
        wp_reset_postdata(); // it resets $post, not the $wp_query.
    }
    else echo 'Записей нет.';
}
function writePosts2($title, $req){
    echo '<h3>'.$title.'<h3>';
    $query = new WP_Query( $req );
    if( $query->have_posts() ){
        while( $query->have_posts() ){
            $query->the_post();
            echo '<h2><a href="';
            the_permalink();
            echo '">';
            the_title();
            echo '</a></h2>'; // Н-да, не особо красивее стало. Хотя... Не, проще читать немного.
        }
        wp_reset_postdata(); // it resets $post, not the $wp_query.
    }
    else echo 'Записей нет.';
}

?>