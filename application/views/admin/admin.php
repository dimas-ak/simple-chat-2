<?PHP
    defined("BASEPATH") OR exit("rika mau ngapa kang? :/");
    $now = date("Y-m-d H:i:s");
    $str = strtotime($now);
?>
<div class="__admin" id-admin="<?PHP echo userdata('admin') ?>" url-chat="<?PHP echo $open_chat ?>">
    <?PHP echo $top_menu ?>
    <div class="__wrap-admin">
        <?PHP
         echo $wrap_menu;
         echo $account_view;
        ?>
    </div>
</div>