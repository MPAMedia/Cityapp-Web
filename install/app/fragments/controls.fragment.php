


        <form action="install.php" id="controls" class="step">

            <div id="welcome">
                <h1 class="title">Last step!</h1>
                <p>Enter your server informations </p>
            </div>


            <div class="form-errors color-red">
                
            </div>

            <div class="inner-wrapper">
                <div class="subsection">
                    <div class="section-title">License:</div>

                    <?php
                        if(INIT_PLATFORM=="ns-android"){
                            $message = "<b>(Android item)</b>";
                        }else if(INIT_PLATFORM=="ns-ios"){
                            $message = "<b>(iOS item)</b>";
                        }else
                            $message = "";
                    ?>

                    <div class="clearfix mb-20">
                        <div class="col s12 m6 l6 mb-10">
                            <label class="form-label">Purchase Code</label>
                            <div class="input-tip">
                                Please include your purchase code <?=$message?>.
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l6 l-last">
                            <input type="text" class="input required" name="key" value="">
                        </div>
                    </div>
                </div>
            </div>


            <div class="inner-wrapper">
                <div class="subsection">
                    <div class="section-title">Database connection details:</div>

                    <div class="clearfix mb-20">
                        <div class="col s12 m6 l6 mb-10">
                            <label class="form-label">Database Host</label>
                            <div class="input-tip">
                                You should be able to get this info from your
                                web host, if localhost doesn't work
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l6 l-last">
                            <input type="text" class="input required" name="db-host" value="localhost">
                        </div>
                    </div>

                    <div class="clearfix mb-20">
                        <div class="col s12 m6 l6 mb-10">
                            <label class="form-label">Database Name</label>
                            <div class="input-tip">
                                be sure that the database exist before indicating the her name
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l6 l-last">
                            <input type="text" class="input required" name="db-name" value="">
                        </div>
                    </div>

                    <div class="clearfix mb-20">
                        <div class="col s12 m6 l6 mb-10">
                            <label class="form-label">Username</label>
                            <div class="input-tip">
                                Your MySQL username
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l6 l-last">
                            <input type="text" class="input required" name="db-username" value="">
                        </div>
                    </div>

                    <div class="clearfix mb-20">
                        <div class="col s12 m6 l6 mb-10">
                            <label class="form-label">Password</label>
                            <div class="input-tip">
                                Your MySQL password
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l6 l-last">
                            <input type="password" class="input" name="db-password" value="">
                        </div>
                    </div>

                </div>
            </div>



            <div class="inner-wrapper">
                <div class="subsection mb-0 install-only">
                    <div class="section-title">Administrative account details:</div>

                    <div class="clearfix">

                        <div class="col s12 m6 l6 mb-20">
                            <input type="hidden" class="input" name="crypto-key" value="<?=md5(time().rand(0,999))?>">
                        </div>

                        <div class="col s12 m6 l6 mb-20">
                            <label class="form-label">Admin path</label>
                            <div style="font-size: 14px;margin-top: 12px;"><?=APPURL."/"?></div>
                       </div>

                        <div class="col s12 m6 m-last l6 l-last mb-20">
                            <label class="form-label" style="opacity: 0">Admin path</label>
                            <input type="text" class="input" name="admin-path" value="<?="dashboard"?>">
                        </div>
                    </div>
                    <div class="clearfix">

                        <div class="col s12 m6 l6 mb-20">
                            <label class="form-label">Name</label>
                            <input type="text" class="input" name="user-name" value="">
                        </div>

                        <div class="col s12 m6 m-last l6 l-last mb-20">
                            <label class="form-label">Username</label>
                            <input type="text" class="input required" name="user-username" value="admin">
                        </div>
                    </div>

                    <div class="clearfix">
                        <div class="col s12 m6 l6 mb-20">
                            <label class="form-label">Email</label>
                            <input type="text" class="input required" name="user-email" value="">
                        </div>

                        <div class="col s12 m6 m-last l6 l-last mb-20">
                            <label class="form-label">Password</label>
                            <input type="password" class="input required" name="user-password" value="">
                        </div>
                    </div>

                    <div class="clearfix">
                        <div class="col s12 m6 l6 mb-20">
                            <label class="form-label">Time Zone</label>
                            <select name="user-timezone" class="input required">
                                <?php foreach (getTimezones() as $k => $v): ?>
                                    <option value="<?= $k ?>" <?= $k == "UTC" ? "selected" : "" ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

                <div class="gotonext mt-40">
                    <div class="clearfix">
                        <div class="col s12 m6 offset-m3 m-last l4 offset-l4 l-last">
                            <input type="submit" value="Finish Installation" class=" fluid button">
                        </div>
                    </div>
                </div>
        </form>