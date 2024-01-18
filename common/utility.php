<?php
    function pagination($numberPage, $currentPage, $addition = ''){

        if($addition != '') {
            $pos = strpos($addition, '&id=');
            if($pos === false)
                $addition = '&search='.$addition;
        }
        if($numberPage > 1) {
            echo '<ul class="pagination">';

            if($currentPage > 1)
               echo '<li class="page-item"><a class="page-link" href="?page='.($currentPage-1).$addition.'">Previous</a></li>';
        
            $displayPage = [1, $currentPage - 1, $currentPage, $currentPage + 1, $numberPage];
            $isFirst = $isLast = false;
        
            for($i = 1; $i <= $numberPage; $i++){
                if(!in_array($i, $displayPage)) {
                    if(!$isFirst && $currentPage > 3) {
                        echo '<li class="page-item"><a class="page-link" href="?page='.($currentPage-2).$addition.'">...</a></li>';
                        $isFirst = true;
                    }
                    if(!$isLast && $i > $currentPage+1 ) {
                        echo '<li class="page-item"><a class="page-link" href="?page='.($currentPage+2).$addition.'">...</a></li>';
                        $isLast = true;
                    }
                    continue;
                }
                if($currentPage == $i)
                    echo '<li class="page-item active"><a class="page-link" href="?page='.$i.$addition.'">'.$i.'</a></li>';
                else 
                    echo '<li class="page-item"><a class="page-link" href="?page='.$i.$addition.'">'.$i.'</a></li>';   
            }

            if($currentPage < $numberPage)
               echo '<li class="page-item"><a class="page-link" href="?page='.($currentPage+1).$addition.'">Next</a></li>';
            echo '</ul>';
        } 
    }
    //Lấy ra số sản phẩm trong giỏ hàng của user
    function quantityProductInCart() {
         if(isset($_SESSION['cart'])) {
            echo '('.count($_SESSION['cart']).')';
        }
    }
?>
