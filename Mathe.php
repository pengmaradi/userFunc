<?php
class user_mathe {
    private $content;   
    public function main( $content, $conf ) {
        $this->content .=  $content;
       
        for($i = 0; $i < 360; $i +=30) {
                $this->content .= '<p style="position:absolute;top:100px;left:100px; width:180px;background: #ccf; transform:rotate('.$i.'deg);">'.cos($i).'</p>';
                $this->content .= '<p style="position:absolute;top:100px;left:300px; width:180px;background: #fcc; transform:rotate(-'.$i.'deg);">'.sin($i).'</p>';
        }
       
        $this->content  =  '<div class="userMathe" style="background: #ccc;">'.$this->content. '</div>';
        return $this->content;
    }
} 
