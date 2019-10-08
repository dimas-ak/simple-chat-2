<div class="__account account-admin">
    <div class="container">
        <div class="wrap clearfix">
            <div class="col-7">
                <div class="f-input">
                    <span>Username</span>
                    <input type="text" id="username" value="">
                </div>
                <div class="f-input">
                    <span>Level</span>
                    <input type="text" id="name" value="">
                </div>
            </div>
            <div class="col-7">
                <h1>Your Star</h1>
                <span>Average your star : <span id="rate-star"></span> out of 5 stars</span>
                <div class="__star">
                    <div class="__star-inner">
                        <div class="__star-color"></div>
                        <?PHP 
                            $left = 0;
                            for($i = 0; $i < 5; $i++){ ?>
                            <img style="left:<?PHP echo $left ?>px" src="<?PHP echo photo('star.png') ?>">
                        <?php $left += 30; } ?>
                    </div>
                </div>
                <h1>Customers Review</h1>
                <span>Total User : <span id="total-user"></span></span>
                <table class="review-star">
                    <tbody>
                        <?PHP for($i = 5; $i >= 1; $i--) { ?>
                            <tr id="review-<?PHP echo $i ?>">
                                <td><?PHP echo $i ?> Star</td>
                                <td class="meter-star">
                                    <div class="meter">
                                        <div class="meter-value" style="width:0%"></div>
                                    </div>
                                    <div class="meter-user">
                                        <span>Mencoba</span>
                                    </div>
                                </td>
                                <td class="percent">0%</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>