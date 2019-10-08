<div class="__chat">
    <div class="__wrap-chat">
        <?PHP 
        foreach($get_chat as $gc):

        $isFromAdmin = ($gc->msg_from == 1) ? ' left' : ' right';
        $photo       = ($gc->msg_from == 1) ? photo('cs.png') : photo('users.png');
        $name        = ($gc->msg_from == 1) ? $gc->name_admin . ' <strong>(CS)</strong>' : $gc->name_user;
        
        ?>
        <div class="__user<?PHP echo $isFromAdmin ?>">
                <div class="__inner-user">
                    <div class="__img">
                        <img src="<?PHP echo $photo ?>">
                    </div>
                    <div class="__info-user">
                        <div class="__name"><?PHP echo $name ?></div>
                        <div class="__date"><?PHP echo format_tanggal($gc->date) ?></div>
                    </div>
                    <div class="__msg">
                        <pre><?PHP echo $gc->message ?></pre>
                    </div>
                </div>
        </div>
    <?PHP endforeach; ?>
    </div>
</div>