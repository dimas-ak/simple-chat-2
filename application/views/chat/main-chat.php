<?PHP
    defined('BASEPATH') or exit('rika mau ngapa kang? :/');
?>
<?PHP 
// keep chat
if($user['status'] == 1):
echo isReview();
?>
<div class="__chat">
    <div class="__wrap-chat">
        <?PHP 
        $no = 0;
        $id_admin = 0;
        foreach($get_chat as $gc):

        $isFromAdmin = ($gc->msg_from == 1) ? ' right' : ' left';
        $photo       = ($gc->msg_from == 1) ? photo('cs.png') : photo('users.png');
        $name        = ($gc->msg_from == 1) ? $gc->name_admin . ' <strong>(CS)</strong>' : $gc->name_user;

        ?>
        <div class="__user<?PHP echo $isFromAdmin ?>">
                <div class="__inner-user">
                    <div class="__img">
                        <img src="<?PHP echo $photo ?>">
                    </div>
                    <div class="__info-user">
                        <div class="__name">
                            <?PHP echo $name ?>
                        </div>
                        <div class="__date">
                            <?PHP echo format_tanggal($gc->date) ?>
                        </div>
                    </div>
                    <div class="__msg">
                        <pre><?PHP echo $gc->message ?></pre>
                    </div>
                </div>
        </div>
    <?PHP endforeach; ?>
    </div>
</div>
<?PHP echo $form_send ?>
    <div id="msg-msg"></div>
    <textarea name="msg" class="__send-input" id-user="<?PHP echo $user['id'] ?>" name-user="<?PHP echo $user['name'] ?>" id-admin="<?PHP echo userdata('review_for') ?>" placeholder="Type your message..."></textarea>
</form>
<?PHP
else:
    echo $this->menu->_helper()['statusChatUser'][$user['status']];
endif;
?>