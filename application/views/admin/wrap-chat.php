<div class="__left">
    <div class="__inner-body">
        <?PHP 
            if($get_chat):
                foreach($get_chat as $gc): ?>
                <a id-user="<?PHP echo $gc->id_user ?>" class="__open-chat" href="<?PHP echo $open_chat . $gc->id_user ?>">
                    <div class="__contact">
                        <div class="img">
                            <img src="<?PHP echo photo('users.png') ?>">
                        </div>
                        <div class="__name"><?PHP echo $gc->name ?></div>
                        <div class="__info"></div>
                    </div>
                </a>
        <?PHP 
                endforeach;
            endif;
        ?>
    </div>
</div>
<div class="__right">
    <div class="__inner-body"></div>
</div>