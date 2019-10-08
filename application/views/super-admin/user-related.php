<h1>Users chat related</h1>
<?PHP foreach($user as $user): ?>
<a href="<?PHP echo $open_chat . $user->id_user ?>" category="open-chat" class="__btn-admin user">
    <div class="__inner-btn">
        <div class="__info">

            <strong>Name</strong>
            <span><?PHP echo $user->name ?></span>

            <strong>E-Mail</strong>
            <span><?PHP echo $user->email ?></span>

            <strong>No. Telephon</strong>
            <span><?PHP echo $user->telephon ?></span>

            <strong>Sign Up Date</strong>
            <span><?PHP echo format_tanggal($user->date_join) ?></span>

            <strong>Last Date Chat</strong>
            <span><?PHP echo format_tanggal($user->last_chat) ?></span>

            <strong>User Star</strong>
            <div class="__star">
                <div class="__star-inner">
                    <div style="background:#FAA;width:<?PHP echo $user->user_star * 20 ?>%" class="__star-color"></div>
                    <?PHP 
                        $left = 0;
                        for($i = 0; $i < 5; $i++){ ?>
                        <img style="left:<?PHP echo $left ?>px" src="<?PHP echo photo('star.png') ?>">
                    <?php $left += 30; } ?>
                </div>
            </div>

            <strong>Reason</strong>
            <span><?PHP echo $user->reason?></span>
            
            <strong>Status User</strong>
            <span><?PHP echo $status[$user->status_user] ?></span>
        </div>
    </div>
</a>
<?PHP endforeach; ?>