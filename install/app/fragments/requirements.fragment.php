        <?php $installable = true; ?>
        <div id="server_status" class="step" style="display: block">
            <div class="subsection">
                <div class="section-title">1.	Please configure PHP to match the following requirements / settings:</div>

                <table>
                    <thead>
                        <tr>
                            <th>PHP Settings</th>
                            <th>State</th>
                            <th class="status">&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td><span class="fw-700">PHP Version</span></td>
                            <td><?= PHP_VERSION ?></td>
                            <td class="status">
                                <?php if (version_compare(PHP_VERSION, '5.4', '>') AND version_compare(PHP_VERSION, '7.1', '<')): ?>
                                    <span class="color-green">OK</span>
                                <?php else: ?>
                                    <span class="color-red">FAILD (We recommend: PHP 5.4 - 7.0.0)</span>
                                    <?php $installable = false; ?>
                                <?php endif ?>
                            </td>
                        </tr>

                        <tr>
                            <td><span class="fw-700">allow_url_fopen</span></td>
                            <td><?= ini_get("allow_url_fopen") ? "Enabled" : "Disabled" ?></td>
                            <td class="status">
                                <?php if (ini_get("allow_url_fopen")): ?>
                                    <span class="color-green">OK</span>
                                <?php else: ?>
                                    <span class="mdi color-red">FAILD</span>
                                    <?php $installable = false; ?>
                                <?php endif ?>
                            </td>
                        </tr>

                        <?php

                            if(function_exists("apache_get_modules") && in_array('mod_rewrite', apache_get_modules())){
                                $mod_rewrite = true;
                            }else{
                                ob_start();
                                phpinfo();
                                $phpinfo = ob_get_contents();
                                ob_end_clean();
                                if(strpos($phpinfo, "mod_rewrite")){
                                    $mod_rewrite = true;
                                }else{
                                    $mod_rewrite = false;
                                }
                            }



                        ?>

                        <tr>
                            <td><span class="fw-700">mod_rewrite</span></td>
                            <td><?= $mod_rewrite ? "Enabled" : "Disabled" ?></td>
                            <td class="status">
                                <?php if ($mod_rewrite): ?>
                                    <span class="color-green">OK</span>
                                <?php else: ?>
                                    <span class="mdi color-orange">FAILD</span>
                                <?php endif ?>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="subsection">
                <div class="section-title">2.	Please make sure that the following extensions  are installed and enabled:</div>

                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>State</th>
                            <th class="status">&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <?php $curl = function_exists("curl_version") ? curl_version() : false; ?>
                            <td><span class="fw-700">cURL</span></td>
                            <td><?= !empty($curl["version"]) ? $curl["version"] : "Not installed"; ?></td>
                            <td class="status">
                                <?php if (!empty($curl["version"]) && version_compare($curl["version"], '7.19.4') >= 0): ?>
                                    <span class="color-green">OK</span>
                                <?php else: ?>
                                    <span class="color-red">FAILD</span>
                                    <?php $installable = false; ?>
                                <?php endif ?>
                            </td>
                        </tr>
<!---->
<!--                        <tr>-->
<!--                            --><?php
//                                $openssl = extension_loaded('openssl');
//                                if ( $openssl AND defined("OPENSSL_VERSION_NUMBER") AND !empty(OPENSSL_VERSION_NUMBER)  ) {
//                                    $installed_openssl_version = get_openssl_version_number(OPENSSL_VERSION_NUMBER);
//                                }
//                            ?>
<!--                            <td><span class="fw-700">OpenSSL</span></td>-->
<!--                            <td>--><?//= !empty($installed_openssl_version) ? $installed_openssl_version : "Outdated or not installed"; ?><!--</td>-->
<!--                            <td class="status">-->
<!--                                --><?php //if (!empty($installed_openssl_version) && $installed_openssl_version >= "1.0.1c"): ?>
<!--                                    <span class="color-green">OK</span>-->
<!--                                --><?php //else: ?>
<!--                                    <span class="color-red">FAILD</span>-->
<!--                                    --><?php //$installable = false; ?>
<!--                                --><?php //endif ?>
<!--                            </td>-->
<!--                        </tr>-->

                        <tr>
                            <?php $pdo = defined('PDO::ATTR_DRIVER_NAME'); ?>
                            <td><span class="fw-700">PDO</span></td>
                            <td><?= $pdo ? "Enabled" : "Disabled"; ?></td>
                            <td class="status">
                                <?php if ($pdo): ?>
                                    <span class="color-green">OK</span>
                                <?php else: ?>
                                    <span class="color-red">FAILD</span>>
                                    <?php $installable = false; ?>
                                <?php endif ?>
                            </td>
                        </tr>

                        <tr>
                            <?php $gd = extension_loaded('gd') && function_exists('gd_info') ?>
                            <td><span class="fw-700">GD</span></td>
                            <td><?= $gd ? "Enabled" : "Disabled"; ?></td>
                            <td class="status">
                                <?php if ($gd): ?>
                                    <span class="color-green">OK</span>
                                <?php else: ?>
                                    <span class="color-red">FAILD</span>
                                    <?php $installable = false; ?>
                                <?php endif ?>
                            </td>
                        </tr>

                        <tr>
                            <?php $mbstring = extension_loaded('mbstring') && function_exists('mb_get_info') ?>
                            <td><span class="fw-700">mbstring</span></td>
                            <td><?= $mbstring ? "Enabled" : "Disabled"; ?></td>
                            <td class="status">
                                <?php if ($mbstring): ?>
                                    <span class="color-green">OK</span>
                                <?php else: ?>
                                    <span class="color-red">FAILD</span>
                                    <?php
                                    // $installable = false;
                                    ?>
                                <?php endif ?>
                            </td>
                        </tr>

                    </tbody>


                </table>

                <div class="section-title">Please make sure that the following requirement are enabled in your server config</div>
                <table>
                    <tr>
                        <td colspan="3">Config folder has permission 0777</td>
                    </tr>
                    <tr>
                        <td colspan="3">All privileges mysql user are guaranteed</td>
                    </tr>
                    <tr>
                        <td colspan="3">be sure that the option ONLY_FULL_GROUP_BY is disabled in your MySQL database</td>
                    </tr>
                </table>

            </div>
            

            <div class="gotonext">
                <?php if ($installable): ?>
                    <div class="clearfix">
                        <div class="col s12 m6 offset-m3 m-last l4 offset-l4 l-last">
                            <a href="javascript:void(0)" class=" fluid button next-btn" data-next="#controls">START</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="error color-red">
                        We are sorry! Your server configuration didn't match the application requirements!
                    </div>
                <?php endif; ?>
            </div>
        </div>