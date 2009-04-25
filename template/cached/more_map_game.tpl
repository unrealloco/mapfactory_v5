
    <!-- LOOP moreMapGame -->
    <li class="line {moreMapGame.class}">
        <span class="info"><span class="star"><span style="width:{moreMapGame.ratting}px;"></span></span></span>

        <a href="{ROOT_PATH}{moreMapGame.game_guid}/{moreMapGame.gametype_guid}/{moreMapGame.map_guid}-{moreMapGame.id}" class="preview">
        <img src="{ROOT_PATH}screenshot/80x60/{moreMapGame.map_guid}-{moreMapGame.image}.jpg" width="80px" height="60px" alt="{moreMapGame.title}" title="{moreMapGame.game} {moreMapGame.title}">
        </a>

        <ul>
            <li>
                <a href="{ROOT_PATH}{moreMapGame.game_guid}/{moreMapGame.gametype_guid}/{moreMapGame.map_guid}-{moreMapGame.id}" alt="{moreMapGame.title}" title="{moreMapGame.game} {moreMapGame.title}">
                <strong>{moreMapGame.title}</strong>
                </a>
            </li>
            <li>
                <a href="{ROOT_PATH}{moreMapGame.game_guid}/{moreMapGame.gametype_guid}" alt="{moreMapGame.gametype}" title="{moreMapGame.game} {moreMapGame.gametype}">{moreMapGame.gametype}</a>
            </li>
        </ul>
    </li>
    <!-- END moreMapGame -->

    <li>
        <div class="pagination" id="moreMapGame_pagination">
            <div class="center">{pagination_page} / {pagination_total}</div>

            <!-- SECTION pagination_next -->
            <a href="javascript:moreMapGameShowPage({pagination_game_id}, {pagination_next});" class="next">next >></a>
            <!-- END pagination_next -->

            <!-- SECTION pagination_prev -->
            <a href="javascript:moreMapGameShowPage({pagination_game_id}, {pagination_prev});" class="prev"><< prev</a>
            <!-- END pagination_prev -->
        </div>
    </li>

    <li><div class="clear"></div></li>

