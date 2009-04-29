
    <!-- INCLUDE bloc/top.tpl -->

    <!-- INCLUDE bloc/search.tpl -->

    <!-- INCLUDE bloc/ie6warning.tpl -->

    <div id="colCenter">
        <h1>Submit a Map !</h1>

        <div class="niceText">
            <p>You made a new map or came across a good map and you want it to be on Map Factory ?</p>
            <p>Simply fill this form with all the informations (only description is not needed) and submit it.
                <ul>
                    <li><strong>SCREENSHOTS</strong>: only JPG, GIF and PNG lower than 2Mb (size should be at last 640x480)</li>
                    <li><strong>FILE</strong>: must be compressed using ZIP format</li>
                    <li><strong>FILE</strong>: must be lower than 80Mb</li>
                    <li>The map file must contains all the data needed to play the map</li>
                    <li>Once you clicked on the submit button wait for the upload precess to be complet (may take a few minutes)</li>
                    <li>Use suggestions when they appear under fields if they are apropriated</li>
                </ul>
            </p>
            <p><strong>If the Game or Gametype of your map is not in the list, <a href="{ROOT_PATH}infos/contact-us">contact us</a> and we'll add it for you !</strong></p>
        </div>

        <!-- SECTION notIE6 -->
        <div id="submitMapProgress"><div></div><span></span><a href="javascript:submitCancel();">cancel</a></div>

        <div id="submitMapComplete" class="niceText">
            <p>Thank you !</p>
            <p>Your map has been sent to the team. It will be posted as soon as it is validated.</p>
            <p><img src="" id="submitMapPreview"></p>
            <p><a href="{ROOT_PATH}">>> Back to the homepage</a></p>
        </div>

        <form id="submitMap" action="javascript:submitMap();" name="submitMap">
            <div id="submitMapDimer"></div>
            <div class="line">
                <span>(*) Field required</span>
            </div>
            <div class="line">
                <label><span>*</span> Title:</label><input maxlength="32" type="text" value="" name="title" class="field" />
            </div>
            <div class="line">
                <label><span>*</span> Game:</label>
                <select name="game">
                    <!-- LOOP submit_game -->
                    <option value="{submit_game.id}">{submit_game.name}</option>
                    <!-- END submit_game -->
                </select>
            </div>
            <div class="line">
                <label><span>*</span> Gametype:</label>
                <select name="gametype">
                    <!-- LOOP submit_gametype -->
                    <option value="{submit_gametype.id}">{submit_gametype.name}</option>
                    <!-- END submit_gametype -->
                </select>
            </div>
            <div class="line">
                <label><span>*</span> Author of the map:</label><input maxlength="32" type="text" value="" name="author" class="field" />
            </div>
            <div class="line">
                <label>Description of the map:</label><textarea name="description"></textarea>
            </div>
            <div class="line">
                <label><span>*</span> Screenshots:</label><em>JPG/PNG/GIF 2Mb max</em><div id="submitScreenshot"></div><div id="submitScreenshotList"></div>
            </div>
            <div class="line">
                <label><span>*</span> File:</label><em>ZIP 80Mb max</em><div id="submitFile"></div><div id="submitFileList"></div>
            </div>
            <div class="line">
                <label></label><input type="submit" value="Submit this map !" name="submit" class="submit" />
            </div>
        </form>
        <!-- END notIE6 -->

        <!-- SECTION ie6warning -->
        <p id="submit_ie6warning">SORRY, UPLOAD IS NOT POSSIBLE WITH YOUR BROWSER.</p>
        <!-- END ie6warning -->
    </div>

    <div class="clear"></div>

