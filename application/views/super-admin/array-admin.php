<?PHP foreach($admin as $admin): ?>
<a href="<?PHP echo $open_admin . $admin->id ?>" class="__btn-admin" category="open-admin">
    <div class="__inner-btn">
        <div class="__img">
            <img src="<?PHP echo photo('cs.png') ?>">
        </div>
        <div class="__info">
            <strong>Username</strong>
            <span><?PHP echo $admin->username ?></span>
            <strong>Name</strong>
            <span><?PHP echo $admin->name ?></span>
        </div>
    </div>
</a>
<?PHP endforeach; ?>