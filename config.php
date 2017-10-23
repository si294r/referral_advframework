<?php

include("/var/www/mysql-config2.php");

$mydatabase = $IS_DEVELOPMENT ? "advframeworkdev" : "advframework";

$SHORT_DOMAIN = $IS_DEVELOPMENT ? "www.alegrium.com/sgi/1" : "www.alegrium.com/sgi/1";

define('CACHE_USER_DEV', "advdev_user_");
define('CACHE_USER', "adv_user_");
define('CACHE_REFERRAL_DEV', "advdev_ref_");
define('CACHE_REFERRAL', "adv_ref_");

// IN-GAME COPIES

define('STR_VERIFIED_INSTALL_CRYSTALS1', "FREE CRYSTALS!");
define('STR_VERIFIED_INSTALL_CRYSTALS2', "Your friend has installed Advframework.");
define('STR_VERIFIED_INSTALL_CASH1', "CASH REWARD!");
define('STR_VERIFIED_INSTALL_CASH2', "Your friend has installed Advframework.");

// REFERRAL REWARD

$referral_reward = array(
    "1" => "0.06,CASH,INVITE_REWARD_1", // reward = 6% from networth user
    "2" => "50,CRYSTAL,INVITE_REWARD_2", // reward = 50 crystal
    "3" => "0.06,CASH,INVITE_REWARD_3", // reward = 6% from networth user
    "4" => "50,CRYSTAL,INVITE_REWARD_4", // reward = 50 crystal
);