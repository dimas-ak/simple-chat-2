<?PHP
    if(isset($info))
    {
        echo $info;
    }
?>
<?PHP echo $form ?>
    <div class="__colomn-chat">
        <span>Full Name</span>
        <div id="msg-name"></div>
        <input type="text" name="name" placeholder="Full Name...">
    </div>
    <div class="__colomn-chat">
        <span>E-Mail</span>
        <div id="msg-email"></div>
        <input type="email" name="email" placeholder="E-Mail...">
    </div>
    <div class="__colomn-chat">
        <span>No. Telephon</span>
        <div id="msg-telephon"></div>
        <input type="number" name="telephon" placeholder="No. Telephon...">
    </div>
    <div class="__colomn-chat">
        <span>Adress</span>
        <div id="msg-address"></div>
        <input type="text" name="address" placeholder="Address...">
    </div>
    <div class="__colomn-chat">
        <button>LOGIN</button>
    </div>
</form>