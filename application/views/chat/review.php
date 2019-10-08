<?PHP echo $form ?>
    <div class="__col-chat">
        <span>Rate our Officer WebChat</span>
        <div id="msg-star"></div>
        <div class="__star">
            <div class="__star-inner" data-star="">
                <div class="__star-color"></div>
                <?PHP
                    $left = 0;
                    for($i = 0; $i < 5; $i++) 
                    { 
                ?>
                        <img style="left:<?PHP echo $left . "px"?>" src="<?php echo photo('star.png') ?>">
                <?php 
                    $left += 30;
                    } 
                ?>
            </div>
            <input type="text" name="star" id="star" hidden="">
        </div>
    </div>
    <div class="__col-chat">
        <div id="msg-msg"></div>
        <textarea placeholder="Please give us your reason!!!" name="msg"></textarea>
    </div>
    <div class="__col-chat">
        <button>SEND</button>
    </div>
</form>