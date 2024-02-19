<?php

class OwnPostTypeClass{
    const ID = 'own_post'; //Статика по-сути, коль константа. Вообще можно было бы весь класс статиком сделать в данной ситуации.

    function add_actions(){
        add_action( 'init', [$this, 'register_type'] );
        add_action( 'admin_head', [$this, 'add_help_tabs'] );

        add_filter( 'manage_edit-'.self::ID.'_columns', [$this, 'add_columns']); // Задавание хедеров колонок
        add_filter( 'manage_'.self::ID.'_custom_column', [$this, 'fill_column']); // Заполнение (отображение).
    }

    function register_type(){
        $labels = array(
            'name' => 'Кастомпосты',
            'singular_name' => 'Кастомпост',
            'add_new' => 'Добавить кастомпост',
            'add_new_item' => 'Добавить кастомпост',
            'edit_item' => 'Редактировать кастомпост',
            'new_item' => 'Новый кастомпост',
            'all_items' => 'Все кастомпосты',
            'search_items' => 'Искать кастомпост',
            'not_found' =>  'Кастомпостов по заданным критериям не найдено.',
            'not_found_in_trash' => 'В корзине нет кастомпостов.',
            'menu_name' => 'Кастомпосты'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'has_archive' => false,
            'menu_position' => 2,
            'hierarchical' => true,
            'supports' => array( 'title', 'editor' )
        );

        register_post_type( self::ID, $args );
    }
    
    function add_help_tabs() {

        $screen = get_current_screen();

        if ( $screen->post_type !== self::ID ) {
            $screen->add_help_tab( array(
                'id'      => 'well',
                'title'   => 'Хуки, они такие, да...',
                'content' => '<h3>Хуки</h3>'.
                '<p>Да, они требуют ручных проверок, их нельзя автоматом настроить на свой тип записей. Так что теперь эта вкладка есть везде кроме хелперки '.self::ID.'.</p>'
            ) );
            return;
        }

        $screen->add_help_tab( array(
            'id'      => self::ID.'_tab_1',
            'title'   => 'Общая информация',
            'content' => '<h3>Общая информация</h3><p>Здесь у нас будут мои чудовищные эксперименты со своими типами постов.</p><p>Я буду изучать их. Изучать их полностью!</p>'
        ) );

    }

    // А дальше - лютые эксперименты с колонками! Хочу добавить системную (скрытую с админ-панели) колонку, колонку с типом даты/времени, иерархию записи! Код-конспект.
    // add_filter( 'manage_edit-{НЕЧТО}_columns', '{ФУНКЦИЯ}'); - общий вид добавления своих табличек.
    // Туда передадут переменную-колонки, вернуть нужно её же после модификации.
    // Вообще полезно изучить php-функцию array_slice. Указываешь массив, старт, длину (null = до конца, отрицательное число = индекс ласта с конца),
    // в конце preserve_keys можно выставить на true, тогда ключи (по дефолту - номера позиций в массиве) сохранятся.
    function add_columns($columns){
        $to_add = array( 
            'system_column' => 'Системная колонка', // Полагаю, это добро нужно не в columns совать, а куда-то ещё, чтобы оно было скрыто...
            'time_column' => 'Колонка времени',
            'parent_column' => 'Колонка родителя', // А это скорее всего есть где-то в основных параметрах, но почему-то указание hierarchical => true не дало ничего подобного.
        ); 


        $columns = array_slice($columns, 0, 2, true) + $to_add + array_slice($columns, 2, null, true);

        return $columns;
    }

    function fill_column($column){
        switch ( $column ) { // Походу он вызывает эту функцию столько раз, сколько столбцов в интерфейсе. Оптимизацией не пахнет.
            case 'system_column': {
                $meta_value = get_post_meta( get_the_ID(), 'meta_value', true ); // get_the_ID(). Походу тут где-то витает переменная $post, из которой можно доставать инфу поста.
                // Собственно, всякие геттеры - опциональный вариант, дающий false вместо nullpointerexception при переборе в цикле.
                echo $meta_value ? $meta_value : 'Зачем мы пришли сюда? Здесь ничего нет! Спасите...';
                // Э, стопЭ... А как редачить мету поста через админпанель шаблонизированно?!
                break;
            }
        }
    }
}

?>