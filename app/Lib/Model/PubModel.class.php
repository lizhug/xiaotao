<?php

class PubModel extends Model{
    protected $_auto = array (
        // 新增的时候把status字段设置为1
        array('scan', '0'),
        array('comment', '0'),
        array('is_verity', '0')
    );
}



?>
