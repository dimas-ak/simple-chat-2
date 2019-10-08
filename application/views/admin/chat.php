<?PHP
    defined('BASEPATH') or exit('rika mau ngapa kang? :/');
?>
<div class="__chat" id-user="<?PHP echo $user['id'] ?>">
    <div class="__wrap-chat">
        <?PHP 
        foreach($get_chat as $gc):

        $isFromAdmin = ($gc->msg_from == 1) ? ' left' : ' right';
        $photo       = ($gc->msg_from == 1) ? photo('cs.png') : photo('users.png');
        $name        = ($gc->msg_from == 1) ? $gc->name_admin . ' <strong>(You)</strong>' : $gc->name_user;
        
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
<?PHP
echo $form_send;

if($user['status'] == 1): ?>
    <div id="msg-msg"></div>
    <textarea name="msg" class="__send-input" id-admin="<?PHP echo $admin['id'] ?>" name-admin="<?PHP echo $admin['name'] ?>" id-user="<?PHP echo $user['id'] ?>" name-user="<?PHP echo $user['name'] ?>" placeholder="Type your message..."></textarea>
<?PHP 
else:
    echo $this->menu->_helper()['statusChatAdmin'][$user['status']];
endif;
?>
</form>