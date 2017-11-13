<?php
use Webcodin\WPVisualAdverts\Core\Agp_RepositoryAbstract;

class RPAdv_AdvertRepository extends Agp_RepositoryAbstract {
    
    public $entityClass ='RPAdv_AdvertEntity';

    private $categoryFilter = array();
    
    private $index;
    
    public function init() {
    }
    
    public function refreshRepository() {
        $data = array();                        
        
        $args = array(
            'post_type' => 'adverts',
            'orderby'   => 'ID',
            'order'     => 'ASC',
            'posts_per_page' => -1,
            'nopaging' => true,
        );
        $query = new WP_Query($args);
        
        while ($query->have_posts()) : $query->the_post(); 
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'adverts-thumb');
        
            $data[get_the_ID()] = array(
                'ID' => get_the_ID(),
                'title' => get_the_title(),
                'description' => get_the_content(),
                'link' => get_post_meta(get_the_ID(), 'rpadv_link', TRUE),
                'linkTarget' => (get_post_meta(get_the_ID(), 'rpadv_link_target', TRUE)) ? get_post_meta(get_the_ID(), 'rpadv_link_target', TRUE) : '_blank',
                'image' => !empty($image[0]) ? $image[0] : '',
            );

            if (taxonomy_exists('advert-category')) {
                $terms = wp_get_post_terms( get_the_ID(), 'advert-category' );
                $categories = array();
                if (!empty($terms)) {
                    foreach($terms as $term) {
                        $categories[] = $term->term_id;
                    }
                }
                $data[get_the_ID()]['categories'] = $categories;
            } else {
                $data[get_the_ID()]['categories'] = array();
            }
            
        endwhile;
        wp_reset_query();
        
        parent::refresh($data);        
    }
    
    public function shuffleAll() {
        $data = $this->getAll();
        srand((float)microtime() * 1000000);
        shuffle($data);
        return $data;
    }

    public function getCategoryFilter() {
        return $this->categoryFilter;
    }

    public function setCategoryFilter($categoryFilter) {
        $this->categoryFilter = array();
        if (!empty($categoryFilter)) {
            if (is_array($categoryFilter)) {
                $this->categoryFilter = $categoryFilter;    
            } else {
                $this->categoryFilter = array($categoryFilter);    
            }
        }
        return $this;
    }
    
    public function applyCategoryFilter($data) {
        $result = array();
        $filter = $this->getCategoryFilter();
        foreach ($data as $id => $entity) {
            if (empty($filter) || $entity->hasCategories($filter)) {
                $result[$id] = $entity;
            }
        }
        return $result;
    }

    public function applyColor($color) {
        foreach ($this->getAll() as $entity) {
            $entity->setColor($color);
        }
    }
    
    public function getAll () {
        return array_values($this->applyCategoryFilter(parent::getAll())); 
    }

    public function getFirst () {
        $data = $this->getAll();
        if (!empty($data)) {
            return $result = reset($data);    
        }
    }        
    
    public function getCount () {
        return count($this->getAll());
    }        
    
    public function getIndex() {
        return $this->index;
    }

    public function setIndex($index) {
        $this->index = $index;
        return $this;
    }
}
