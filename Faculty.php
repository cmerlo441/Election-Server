<?php

class Faculty {
    private $id;
    private $first;
    private $last;
    private $banner;

    public function __construct( $id, $first, $last, $banner ) {
        $this->id = $id;
        $this->first = $first;
        $this->last = $last;
        $this->banner = $banner;
    }

    public function __toString() {
        return "$this->first $this->last";
    }

    public function fLast() {
        return "{$this->first[ 0 ]} $this->last";
    }
}

?>
