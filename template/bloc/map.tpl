
    <script type="text/javascript">map_id = {id};</script>

    <div id="center">
        <div id="colLeft_map">
            <h1 id="mapTitle">
                <img src="{ROOT_PATH}screenshot/80x60/{map_guid}-{image}.jpg" width="80px" height="60px" alt="{map_title}" title="{map_game} {map_title}" id="previewImage">
                {page_title}
            </h1>

            <div id="mapInfo">
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

                <ol class="detail">
                    <li>realised by <a href="{ROOT_PATH}author/{author_guid}-{author_id}" title="{author}" alt="{author} maps">{author}</a></li>
                    <li>gametype : <a href="{ROOT_PATH}{game_guid}/{gametype_guid}" title="{gametype}" alt="{gametype} maps">{gametype}</a></li>
                    <li>downloaded : <span id="download_count">{download}</span> time{download_s}</li>
                    <li class="download"><a href="{ROOT_PATH}download/{game_guid}-{map_guid}-{id}.zip" id="download_link" class="download"><img src="{ROOT_PATH}media/image/layout/download_icon.png" /><span>Download this map - {size}Mb</span></a></li>
                </ol>
            </div>

            <p class="backButton"><a href="{ROOT_PATH}{game_guid}">&lt;&lt; back to {game} map list</a></p>

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

                <a name="comment"></a>

                <div id="commentList" class="{commentClass}" style="display: {commentDisplay};">
                    <!-- INCLUDE bloc/commentList.tpl -->
                </div>
            </div>
        </div>

        <div id="colRight_map">
            <div id="preview"><!-- LOOP preview --><img src="{ROOT_PATH}screenshot/640x480/{map_guid}-{preview.id}.jpg" name="{map_guid}-{preview.id}" class="{preview.class}"><!-- END preview --></div>

            <!-- SECTION preview_nav -->
            <div id="preview_nav">
                <a href="javascript:previewScroll(-1);" id="preview_nav_previous"><img src="{ROOT_PATH}media/image/layout/preview_nav_previous.png" /></a>
                <a href="javascript:previewScroll(+1);" id="preview_nav_next"><img src="{ROOT_PATH}media/image/layout/preview_nav_next.png" /></a>
            </div>
            <!-- END preview_nav -->

            <h3>Latest {game} maps:</h3>

            <ul class="block more maplist" id="moreMapGame">
                <!-- INCLUDE cached/more_map_game.tpl 60 -->
            </ul>

            <h3>All maps from {author}:</h3>

            <ul class="block more maplist">
                <!-- INCLUDE cached/more_map_from.tpl 60 -->
            </ul>
        </div>
    </div>

