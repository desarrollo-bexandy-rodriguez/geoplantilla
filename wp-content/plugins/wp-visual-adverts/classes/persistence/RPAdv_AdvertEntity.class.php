<?php
use Webcodin\WPVisualAdverts\Core\Agp_Entity;

class RPAdv_AdvertEntity extends Agp_Entity {

    private $title;
    private $description;
    private $link;
    private $linkTarget;
    private $image;
    private $categories;
    private $color;
    
    public function __construct($data) {
        $default = array(
            'ID' => NULL, 
            'title' => NULL,             
            'description' => NULL, 
            'link' => NULL, 
            'linkTarget' => NULL, 
            'image' => NULL, 
            'categories' => NULL, 
        );

        parent::__construct($data, $default); 
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getLink() {
        return $this->link;
    }

    public function getImage() {
        return $this->image;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setLink($link) {
        $this->link = $link;
        return $this;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getCategories() {
        return $this->categories;
    }

    public function setCategories($categories) {
        $this->categories = $categories;
        return $this;
    }
    
    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $this->color = $color;
        return $this;
    }
        
    public function getLinkTarget() {
        return $this->linkTarget;
    }

    public function setLinkTarget($linkTarget) {
        $this->linkTarget = $linkTarget;
        return $this;
    }

    public function hasCategories($data = array()) {
        $result = FALSE;
        if (!empty($data)) {
            if (!is_array($data)) {
                $data = array($data);
            }
            foreach($data as $category) {
               $result = $result || in_array($category, $this->categories); 
            }
        }
        return $result;
    }
}
