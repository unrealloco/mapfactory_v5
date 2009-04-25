
    <!-- INCLUDE bloc/top.tpl -->

    <!-- INCLUDE bloc/search.tpl -->

    <!-- INCLUDE bloc/ie6warning.tpl -->

    <div id="colLeft_map">
        <!-- INCLUDE bloc/map.tpl -->
    </div>

    <div id="colRight_map">
        <div id="pub_middle">
            <script type="text/javascript"><!--
            google_ad_client = "pub-3305665136679236";
            /* 300x250, date de création 22/01/09 */
            google_ad_slot = "1216484762";
            google_ad_width = 300;
            google_ad_height = 250;
            //-->
            </script>
            <script type="text/javascript"
            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
        </div>

        <h3>{game} maps:</h3>

        <ul class="block more maplist" id="moreMapGame">
            <!-- INCLUDE cached/more_map_game.tpl 60 -->
                    </ul>

        <h3>All from: {author}</h3>

        <ul class="block more maplist">
            <!-- INCLUDE cached/more_map_from.tpl 60 -->
                    </ul>
    </div>

    <div class="clear"></div>

