
    <!-- INCLUDE bloc/top.tpl -->

    <!-- INCLUDE bloc/search.tpl -->

    <div id="colLeft_map">
        <div id="adminCommentList">
            <ul class="comments">
                <!-- LOOP comment -->
                <li class="comment {comment.class}">
                    <strong>{comment.name}</strong> <span>{comment.time}</span> - <a href="{ROOT_PATH}{comment.game_guid}/{comment.gametype_guid}/{comment.map_guid}-{comment.map_id}">{comment.map_title}</a>
                    <p>{comment.message}</p>
                </li>
                <!-- END comment -->
            </ul>
        </div>
    </div>

    <div id="colRight_map">
    </div>

    <div class="clear"></div>

