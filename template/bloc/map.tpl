
            <h1>{page_title}</h1>

            <div id="map">
                <div id="preview">
                    <!-- SECTION previewList -->
                    <ul id="previewList">
                        <!-- LOOP preview -->
                        <li><img src="{ROOT_PATH}screenshot/80x60/{map_guid}-{preview.id}.jpg" width="80px" height="60px" name="{preview.id}" class="{preview.class}"></li>
                        <!-- END preview -->
                    </ul>
                    <!-- END previewList -->
                    <img src="{ROOT_PATH}screenshot/640x480/{map_guid}-{image}.jpg" width="640px" height="480px" alt="{map_title}" title="{map_game} {map_title}" id="previewImage">
                </div>

                <script type="text/javascript">map_id = {id};</script>

                <div class="info">
                    <div id="ratting">
                        <!-- LOOP ratting -->
                        <div class="{ratting.active}" name="{ratting.score}">
                            <strong>{ratting.title} :</strong>
                            <span class="{ratting.star_1}"></span>
                            <span class="{ratting.star_2}"></span>
                            <span class="{ratting.star_3}"></span>
                            <span class="{ratting.star_4}"></span>
                            <span class="{ratting.star_5}"></span>
                        </div>
                        <!-- END ratting -->
                    </div>
                    
                    <div class="pub">
                        <script type="text/javascript"><!--
						google_ad_client = "pub-3305665136679236";
						/* 125x125, date de création 29/04/08 */
						google_ad_slot = "8713095069";
						google_ad_width = 125;
						google_ad_height = 125;
						//-->
						</script>
						<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                    </div>
                    
                    <ol class="detail">
                        <li>by <a href="{ROOT_PATH}author/{author_guid}-{author_id}" title="{author}" alt="{author} maps">{author}</a></li>
                        <li>gametype : <a href="{ROOT_PATH}{game_guid}/{gametype_guid}" title="{gametype}" alt="{gametype} maps">{gametype}</a></li>
                        <li>downloaded : <span id="download_count">{download}</span> time{download_s}</li>
                        <li><a href="{ROOT_PATH}download/{game_guid}-{map_guid}-{id}.zip" id="download_link" class="download" onclick="javascript:downloadClick();">Download this map - {size}Mb</a></li>
                    </ol>
                    
                    <div class="clear"></div>
                </div>
            </div>

            <!-- SECTION description -->
            <div class="toggleBlock">
                <strong class="toggle off" onclick="javascript:toogleBlock('description');">Description</strong>

                <div id="description" style="display: none;" class="off">
                    <div>{description}</div>
                </div>
            </div>
            <!-- END description -->

            <div class="toggleBlock">
                <strong class="toggle {commentFormClass}" onclick="javascript:toogleBlock('commentPost');">Comment on this map</strong>

                <div id="commentPost" class="{commentFormClass}" style="display: {commentFormDisplay};">
                    <form action="javascript:commentPost();" name="commentForm">
                        <div>
                            <label>Name:</label><input type="text" value="" name="name" class="field" />
                        </div>
                        <div>
                            <label>Message:</label><textarea name="message"></textarea>
                        </div>
                        <input type="submit" value="Post comment" class="submit" />
                    <form>
                </div>
            </div>

            <div class="toggleBlock">
                <strong class="toggle {commentClass}" onclick="javascript:toogleBlock('commentList');">Comments ({comment})</strong>

                <div id="commentList" class="{commentClass}" style="display: {commentDisplay};">
                    <!-- INCLUDE bloc/commentList.tpl -->
                </div>
            </div>
